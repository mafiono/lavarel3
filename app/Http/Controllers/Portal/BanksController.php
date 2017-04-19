<?php

namespace App\Http\Controllers\Portal;

use App\Enums\DocumentTypes;
use App\Enums\ValidFileTypes;
use App\Http\Traits\GenericResponseTrait;
use App\ListSelfExclusion;
use App\Models\TransactionTax;
use App\User;
use Response;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use View, Session, Validator, Auth, Route, Hash, Redirect;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\UserTransaction;
use App\UserBankAccount;

class BanksController extends Controller {

    use GenericResponseTrait;

    protected $authUser;

    protected $request;

    protected $userSessionId;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');

        View::share('authUser', $this->authUser, 'request', $request);        
    }

    /**
     * Display banco saldo index page
     *
     * @return \View
     */
    public function balance()
    {
        return view('portal.bank.balance');
    }

    /**
     * @return JsonResponse
     */
    public function getTaxes()
    {
        $taxes = TransactionTax::getByMethod('deposit');
        return Response::json(compact('taxes'));
    }
    /**
     * Display portal deposit index page
     *
     * @return \View
     */
    public function deposit()
    {
        /*
        * Validar user com identidade valida
        */
        $canDeposit = $this->authUser->checkCanDeposit();
        /*
        * Validar autoexclusão
        */
        $data['document_number'] = $this->authUser->profile->document_number;
        $selfExclusion = ListSelfExclusion::validateSelfExclusion($data)
            || $this->authUser->getSelfExclusion();

        $taxes = [];
        if ($canDeposit) {
            $taxes = TransactionTax::getByMethod('deposit');
        }

        return view('portal.bank.deposit', compact('selfExclusion', 'canDeposit', 'taxes'));
    }

    /**
     * Handle deposit POST
     *
     * @return RedirectResponse | Response | \Illuminate\Http\Response
     */
    public function depositPost()
    {
        /*
        * Validar user com identidade valida
        */
        $canDeposit = $this->authUser->checkCanDeposit();
        if (!$canDeposit) {
            $messages = [
                'deposit_value' => 'A sua conta ainda não foi validada.'
            ];
            return $this->respType('error', $messages);
        }
        /*
        * Validar autoexclusão
        */
        $data['document_number'] = $this->authUser->profile->document_number;
        $selfExclusion = ListSelfExclusion::validateSelfExclusion($data);
        if ($selfExclusion) {
            $messages = [
                'deposit_value' => 'Existe uma autoexclusão em vigor, que não o permite fazer depósitos.'
            ];
            return $this->respType('error', $messages);
        }
        $selfExclusion = $this->authUser->getSelfExclusion();
        if ($selfExclusion !== null && $selfExclusion->exists){
            $messages = [
                'deposit_value' => 'Existe uma autoexclusão em vigor, que não o permite fazer depósitos.'
            ];
            return $this->respType('error', $messages);
        }

        $inputs = $this->request->only(['payment_method','deposit_value']);
        $inputs['deposit_value'] = str_replace(' ', '', $inputs['deposit_value']);

        $validator = Validator::make($inputs, UserTransaction::$rulesForDeposit, UserTransaction::$messages);
        if ($validator->fails()) {
            $messages = UserTransaction::buildValidationMessageArray($validator);
            return $this->respType('error', $messages);
        }
        $messages = $this->authUser->checkInDepositLimit($inputs['deposit_value']);
        if (!empty($messages))
            return $this->respType('error', $messages);

        if ($inputs['payment_method'] === 'paypal') {
            $request = Request::create('/perfil/banco/depositar/paypal', 'POST');
            return Route::dispatch($request);
        } else if (in_array($inputs['payment_method'], ['mb', 'meo_wallet'])) {
            $request = Request::create('/perfil/banco/depositar/meowallet', 'POST');
            return Route::dispatch($request);
        } else if (in_array($inputs['payment_method'], ['cc', 'mc'])) {
            $request = Request::create('/perfil/banco/depositar/swift-pay', 'POST');
            return Route::dispatch($request);
        }
        return $this->respType('error', 'Não Implementado!');
    }
    /**
     * Display banco levantar page
     *
     * @return \View
     */
    public function withdrawal()
    {
        $canWithdraw = $this->authUser->checkCanWithdraw();
        $whyWithdraw = $this->authUser->whyCanWithdraw();
        $taxes = TransactionTax::getByMethod('withdraw');
        return view('portal.bank.withdrawal', compact('canWithdraw', 'whyWithdraw', 'taxes'));
    }
    /**
     * Handle withdrawal POST
     *
     * @return JsonResponse
     */
    public function withdrawalPost() 
    {
        $inputs = $this->request->only(['bank_account', 'withdrawal_value']);
        $inputs['withdrawal_value'] = str_replace(' ', '', $inputs['withdrawal_value']);

        if ($this->authUser->balance->balance_available <= 0 || ($this->authUser->balance->balance_available - $inputs['withdrawal_value']) < 0)
            return $this->respType('error', 'Não possuí saldo suficiente para o levantamento pedido.');

        if (! $this->authUser->checkCanWithdraw())
            return $this->respType('error', 'A sua conta não permite levantamentos.');

        if (! $this->authUser->isBankAccountConfirmed($inputs['bank_account']))
            return $this->respType('error', 'Escolha uma conta bancária válida.');

        if (!$this->authUser->newWithdrawal($inputs['withdrawal_value'], 'bank_transfer', $inputs['bank_account']))
            return $this->respType('error', 'Ocorreu um erro ao processar o pedido de levantamento, por favor tente mais tarde');

        return $this->respType('success', 'Pedido de levantamento efetuado com sucesso!', 'reload');
    }

    /**
     * Display banco conta pagamentos page
     *
     * @return \View
     */
    public function accounts() {
        $user_bank_accounts = UserBankAccount::query()
            ->where('user_id', $this->authUser->id)
            ->where('active', '=', 1)
            ->get();
        return view('portal.bank.accounts', compact('user_bank_accounts'));
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function createAccount(Request $request) {
        $inputs = $request->only(['bank', 'iban']);
        if (isset($inputs['iban'])) {
            $inputs['iban'] = mb_strtoupper(str_replace(' ', '', $inputs['iban']));
        }
        UserBankAccount::$rulesForCreateAccount['iban'] .= $this->authUser->id;
        $validator = Validator::make($inputs, UserBankAccount::$rulesForCreateAccount, UserBankAccount::$messagesForCreateAccount);
        if (!$validator->fails()) {
            /* Save file */
            $file = $this->request->file('upload');

            if ($file == null || ! $file->isValid()) {
                return $this->respType('error', ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']);
            }

            if (!ValidFileTypes::isValid($file->getMimeType()))
                return $this->respType('error', ['upload' => 'Apenas são aceites imagens ou documentos no formato PDF ou WORD.']);

            if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5 * 1024 * 1024) {
                return $this->respType('error', ['upload' => 'O tamanho máximo aceite é de 5mb.']);
            }

            if (! $doc = $this->authUser->addDocument($file, DocumentTypes::$Bank)) {
                return $this->respType('error', ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']);
            }

            if (! $this->authUser->createBankAndIban($inputs, $doc)) {
                return $this->respType('error', ['upload' => 'Ocorreu um erro ao gravar, por favor tente novamente.']);
            }
        } else {
            return $this->respType('error', $validator->errors());
        }

        return $this->respType('success', 'Conta Adicionada com sucesso!', 'reload');
    }

    public function selectAccount() {
        $inputs = $this->request->only(['selected_account']);

        $accountsInUse = $this->authUser->bankAccountsInUse;
        $accountsInUse->each(function ($accountInUse) {
            $accountInUse->update(['status_id' => 'confirmed']);
        });

        $account = $this->authUser->confirmedBankAccounts->find($inputs['selected_account']);
        if ($account) {
            $account->status_id = 'in_use';
            $account->update();
        }
        return redirect('/banco/conta-pagamentos');
    }

    public function removeAccount($id) {
        /** @var UserBankAccount $bankAccount */
        $bankAccount = UserBankAccount::query()
            ->where('user_id', '=', $this->authUser->id)
            ->where('active', '=', 1)
            ->where('id', '=', $id)
            ->first();
        if ($bankAccount === null)
            return $this->resp('error', 'Conta não encontrada!');

        if (!$bankAccount->canDelete())
            return $this->resp('error', 'Esta conta não pode ser apagada!');

        try {
            DB::beginTransaction();

            if (!$userSession = $this->authUser->logUserSession('delete.iban', 'Apagar conta IBAN ' . $bankAccount->iban))
                throw new Exception('errors.creating_session');

            $bankAccount->active = 0;
            $bankAccount->status_id = 'canceled';

            if (!$bankAccount->save())
                throw new Exception('errors.deleting_iban');

            // TODO validate if we need to delete the attachment from DB.

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->resp('error', 'Ocorreu um erro ao apagar a conta!');
        }
        return $this->respType('success', 'Esta conta foi apagada com suceso!', 'reload');
    }

    /**
     * Display Success Message
     *
     * @return \View
     */
    public function success()
    {
        return view('portal.bank.success');
    }

    /**
     * Display Error Message
     *
     * @return \View
     */
    public function error()
    {
        return view('portal.bank.error');
    }
}
