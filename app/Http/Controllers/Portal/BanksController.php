<?php

namespace App\Http\Controllers\Portal;

use App\Enums\DocumentTypes;
use App\Http\Traits\GenericResponseTrait;
use App\ListSelfExclusion;
use App\User;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
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
        * Validar auto-exclusão
        */
        $data['document_number'] = $this->authUser->profile->document_number;
        $selfExclusion = ListSelfExclusion::validateSelfExclusion($data)
            || $this->authUser->getSelfExclusion();

        return view('portal.bank.deposit', compact('selfExclusion', 'canDeposit'));
    }

    /**
     * Handle deposit POST
     *
     * @return RedirectResponse | Response
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
        * Validar auto-exclusão
        */
        $data['document_number'] = $this->authUser->profile->document_number;
        $selfExclusion = ListSelfExclusion::validateSelfExclusion($data);
        if ($selfExclusion) {
            $messages = [
                'deposit_value' => 'Existe uma auto-exclusão em vigor, que não o permite fazer depósitos.'
            ];
            return $this->respType('error', $messages);
        }
        $selfExclusion = $this->authUser->getSelfExclusion();
        if ($selfExclusion !== null && $selfExclusion->exists){
            $messages = [
                'deposit_value' => 'Existe uma auto-exclusão em vigor, que não o permite fazer depósitos.'
            ];
            return $this->respType('error', $messages);
        }

        $inputs = $this->request->only('payment_method','deposit_value');

        $validator = Validator::make($inputs, UserTransaction::$rulesForDeposit, UserTransaction::$messages);
        if ($validator->fails()) {
            $messages = UserTransaction::buildValidationMessageArray($validator);
            return $this->respType('error', $messages);
        }
        $messages = $this->authUser->checkInDepositLimit($inputs['deposit_value']);
        if (!empty($messages))
            return $this->respType('error', $messages);

        if ($inputs['payment_method'] === 'paypal') {
            $request = Request::create('/banco/depositar/paypal', 'POST');
            return Route::dispatch($request);
        } else if (in_array($inputs['payment_method'], ['cc', 'mc', 'mb', 'meo_wallet'])) {
            $request = Request::create('/banco/depositar/meowallet', 'POST');
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
        return view('portal.bank.withdrawal', compact('canWithdraw'));
    }
    /**
     * Handle withdrawal POST
     *
     * @return \Redirect
     */
    public function withdrawalPost() 
    {
        $inputs = $this->request->only('bank_account', 'withdrawal_value');

        if ($this->authUser->balance->balance_available <= 0 || ($this->authUser->balance->balance_available - $inputs['withdrawal_value']) < 0)
            return $this->respType('error', 'Não possuí saldo suficiente para o levantamento pedido.');

        if (! $this->authUser->checkCanWithdraw())
            return $this->respType('error', 'A sua conta não permite levantamentos.');

        if (! $this->authUser->isBankAccountConfirmed($inputs['bank_account']))
            return $this->respType('error', 'Escolha uma conta bancária válida.');

        if (!$this->authUser->newWithdrawal($inputs['withdrawal_value'], 'bank_transfer', $inputs['bank_account'], $this->userSessionId))
            return $this->respType('error', 'Ocorreu um erro ao processar o pedido de levantamento, por favor tente mais tarde');

        return $this->respType('success', 'Pedido de levantamento efetuado com sucesso!');
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

    public function createAccount(Request $request) {
        $inputs = $request->only('bank', 'iban');
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

            if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000) {
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
            return $this->resp('error', 'Ocurreu um erro ao apagar a conta!');
        }
        return $this->resp('success', 'Esta conta foi apagada com suceso!');
    }
    /**
     * Display banco consultar bonus page
     *
     * @return \View
     */
    public function checkBonus()
    {
        return view('portal.bank.check_bonus');
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
