<?php

namespace App\Http\Controllers\Portal;

use App\ListSelfExclusion;
use App\User;
use View, Session, Validator, Auth, Route, Hash, Redirect;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\UserTransaction;
use App\UserBankAccount;

class BanksController extends Controller {

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
        * Validar auto-exclusão
        */
        $data['document_number'] = $this->authUser->profile->document_number;
        $selfExclusion = ListSelfExclusion::validateSelfExclusion($data)
            || $this->authUser->getSelfExclusion();

        return view('portal.bank.deposit', compact('selfExclusion'));
    }

    /**
     * Handle deposit POST
     *
     * @return array Json array
     */
    public function depositPost()
    {
        /*
        * Validar auto-exclusão
        */
        $data['document_number'] = $this->authUser->profile->document_number;
        $selfExclusion = ListSelfExclusion::validateSelfExclusion($data);
        if ($selfExclusion) {
            $messages = [
                'deposit_value' => 'Existe uma auto-exclusão em vigor, que não o permite fazer depósitos.'
            ];
            return redirect()->back()->withErrors($messages);
        }
        $selfExclusion = $this->authUser->getSelfExclusion();
        if ($selfExclusion !== null && $selfExclusion->exists){
            $messages = [
                'deposit_value' => 'Existe uma auto-exclusão em vigor, que não o permite fazer depósitos.'
            ];
            return redirect()->back()->withErrors($messages);
        }

        $inputs = $this->request->only('payment_method','deposit_value');

        $validator = Validator::make($inputs, UserTransaction::$rulesForDeposit, UserTransaction::$messages);
        if ($validator->fails()) {
            $messages = UserTransaction::buildValidationMessageArray($validator);
            return redirect()->back()->withErrors($messages);
        }

        if ($inputs['payment_method'] == 'paypal') {
            $request = Request::create('/banco/depositar/paypal', 'POST');
            return Route::dispatch($request);
        }

        return redirect()->back();
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
        $inputs = $this->request->only('bank_account', 'withdrawal_value', 'password');

        if (! Hash::check($inputs['password'], $this->authUser->password))
            return Redirect::to('/banco/levantar')->with('error', 'A password introduzida não está correcta');

        if ($this->authUser->balance->balance_available <= 0 || ($this->authUser->balance->balance_available - $inputs['withdrawal_value']) < 0)
            return Redirect::to('/banco/levantar')->with('error', 'Não possuí saldo suficiente para o levantamento pedido.');

        if (! $this->authUser->checkCanWithdraw())
            return Redirect::to('/banco/levantar')->with('error', 'A sua conta não permite levantamentos.');

        if (! $this->authUser->isBankAccountConfirmed($inputs['bank_account']))
            return Redirect::to('/banco/levantar')->with('error', 'Escolha uma conta bancária válida.');

        if (!$this->authUser->newWithdrawal($inputs['withdrawal_value'], 'bank_transfer', $inputs['bank_account'], $this->userSessionId))
            return Redirect::to('/banco/levantar')->with('error', 'Ocorreu um erro ao processar o pedido de levantamento, por favor tente mais tarde');

        return Redirect::to('/banco/sucesso')->with('success', 'Pedido de levantamento efetuado com sucesso!');
    }

    /**
     * Display banco conta pagamentos page
     *
     * @return \View
     */
    public function accounts() {
        $user_bank_accounts = UserBankAccount::where('user_id', $this->authUser->id)->get();
        return view('portal.bank.accounts', compact('user_bank_accounts'));
    }

    private function validateUpload(\Illuminate\Validation\Validator $validator){
        /* Save file */
        $file = $this->request->file('upload');

        if ($file == null || ! $file->isValid())
            return $validator->errors()->add('upload', 'Ocorreu um erro a enviar o documento, por favor tente novamente.');

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
            return $validator->errors()->add('upload', 'O tamanho máximo aceite é de 5mb.');

        if (! $fullPath = $this->authUser->addDocument($file, 'comprovativo_iban', $this->userSessionId))
            return $validator->errors()->add('upload', 'Ocorreu um erro a enviar o documento, por favor tente novamente.');

    }
    public function createAccount(Request $request) {
        $inputs = $request->only('bank', 'iban');
        $validator = Validator::make($inputs, UserBankAccount::$rulesForCreateAccount);
        if (!$validator->fails()) {
            $this->authUser->createBankAndIban($inputs, $this->userSessionId);

            $this->validateUpload($validator);
        }

        return redirect('/banco/conta-pagamentos');
    }

    public function selectAccount(Request $request) {
        $accountsInUse = $this->authUser->bankAccountsInUse;
        $accountsInUse->each(function ($accountInUse) {
            $accountInUse->update(['status_id' => 'confirmed']);
        });

        $account = $this->authUser->confirmedBankAccounts->find($request['selected_account']);
        if ($account) {
            $account->status_id = 'in_use';
            $account->update();
        }
        return redirect('/banco/conta-pagamentos');
    }
    public function removeAccount($id) {
        UserBankAccount::destroy($id);
        return redirect('/banco/conta-pagamentos');
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
