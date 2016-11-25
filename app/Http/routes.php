<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/*********************************************************************
 *					   BEGIN Portal Routes
 *********************************************************************/

//Route::get('/', function () {
//    return redirect('/apostas/desportos');
//});

use App\User;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;

Route::get('/', ['as' => '/', 'uses' => 'Portal\BetsController@sports']);
/*********************************************************************
 * 						BEGIN Auth / Api Routes
 *********************************************************************/
Route::post('api/token', ['as' => 'api/token', 'uses' => 'AuthController@getToken']);
/* Utils */
Route::get('api/utils/sign-up', ['as' => 'api/utils/sign-up', 'uses' => 'Api\UtilsController@getMixSignLists']);
Route::get('api/info/doc', ['as' => 'api/info/doc', 'uses' => 'Api\InfoController@getDocInfo']);
Route::get('api/info/childes', ['as' => 'api/info/childes', 'uses' => 'Api\InfoController@getChildesDocs']);
Route::get('api/competitions', ['as' => 'api/competitions', 'uses' => 'Portal\BetsController@highlights']);
Route::post('api/sign-up', ['as' => 'api/sign-up', 'uses' => 'Api\SignUpController@postStep1']);
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('api/user', ['as' => 'api/user', 'uses' => 'Api\UserController@getAuthenticatedUser']);
    Route::get('api/user/status', ['as' => 'api/user/status', 'uses' => 'Api\UserController@getUserStatus']);
    Route::post('api/user/profile', ['as' => 'api/user/profile', 'uses' => 'Api\UserController@postProfile']);
    Route::post('api/user/reset_password', ['as' => 'api/user/reset_password', 'uses' => 'Api\UserController@postResetPassword']);
    Route::post('api/user/reset_pin', ['as' => 'api/user/reset_pin', 'uses' => 'Api\UserController@postResetPin']);
    Route::post('api/user/upload_identity', ['as' => 'api/user/upload_identity', 'uses' => 'Api\UserController@postUploadIdentity']);
    Route::post('api/user/upload_address', ['as' => 'api/user/upload_address', 'uses' => 'Api\UserController@postUploadAddress']);
    Route::post('api/user/upload_iban', ['as' => 'api/user/upload_iban', 'uses' => 'Api\UserController@postUploadIban']);
    Route::get('api/user/uploaded_docs', ['as' => 'api/user/uploaded_docs', 'uses' => 'Api\UserController@getUploadedDocs']);
    Route::get('api/user/balance', ['as' => 'api/user/balance', 'uses' => 'Api\UserController@getUserBalance']);
    Route::get('api/user/settings', ['as' => 'api/user/settings', 'uses' => 'Api\UserController@getUserSettings']);
    Route::post('api/user/settings', ['as' => 'api/user/settings', 'uses' => 'Api\UserController@postUserSettings']);
    Route::get('api/user/network', ['as' => 'api/user/network', 'uses' => 'Api\UserController@getUserNetwork']);
    /* Historico */
    Route::post('api/user/history', ['as' => 'api/user/history', 'uses' => 'Api\UserController@postHistory']);
    /* Promoções */
    Route::get('api/user/bonus', ['as' => 'api/user/bonus', 'uses' => 'Api\UserController@getBonus']);
    Route::get('api/user/bonus/active', ['as' => 'api/user/bonus/active', 'uses' => 'Api\UserController@getActiveBonuses']);
    Route::get('api/user/bonus/consumed', ['as' => 'api/user/bonus/consumed', 'uses' => 'Api\UserController@getConsumedBonuses']);
    Route::post('api/user/bonus/redeem', ['as' => 'api/user/bonus/redeem', 'uses' => 'Api\UserController@postRedeemBonus']);
    Route::post('api/user/bonus/cancel', ['as' => 'api/user/bonus/cancel', 'uses' => 'Api\UserController@postCancelBonus']);

    /* Jogo Responsavel */
    Route::get('api/user/limit/bets', ['as' => 'api/user/limit/bets', 'uses' => 'Api\RespGameController@getLimitsBets']);
    Route::post('api/user/limit/bets', ['as' => 'api/user/limit/bets', 'uses' => 'Api\RespGameController@postLimitsBets']);
    Route::get('api/user/limit/deposits', ['as' => 'api/user/limit/deposits', 'uses' => 'Api\RespGameController@getLimitsDeposit']);
    Route::post('api/user/limit/deposits', ['as' => 'api/user/limit/deposits', 'uses' => 'Api\RespGameController@postLimitsDeposits']);
    /* Auto-Exclusão*/
    Route::get('api/user/self-exclusion', ['as' => 'api/user/self-exclusion', 'uses' => 'Api\RespGameController@selfExclusionGet']);
    Route::post('api/user/self-exclusion', ['as' => 'api/user/self-exclusion', 'uses' => 'Api\RespGameController@selfExclusionPost']);
    Route::post('api/user/self-exclusion/cancel', ['as' => 'api/user/self-exclusion/cancel', 'uses' => 'Api\RespGameController@cancelSelfExclusionPost']);
    Route::post('api/user/self-exclusion/revoke', ['as' => 'api/user/self-exclusion/revoke', 'uses' => 'Api\RespGameController@revokeSelfExclusionPost']);
});
/*********************************************************************
 * 						BEGIN Auth / Sign Up Routes
 *********************************************************************/
Route::post('api/login', ['as' => 'api/login', 'uses' => 'ApiController@handleRequests']);
Route::post('api/check-users', ['as' => 'api/checkUsers', 'uses' => 'AuthController@postApiCheck']);
Route::post('/', ['as' => '/', 'uses' => 'ApiController@handleRequests']);



Route::get('captcha', 'AuthController@captcha');
Route::get('registar/step1', 'AuthController@registarStep1');
Route::post('registar/step1', ['as' => 'registar/step1', 'uses' => 'AuthController@registarStep1Post']);
Route::get('registar/step2', 'AuthController@registarStep2');
Route::post('registar/step2', ['as' => '/registar/step2', 'uses' => 'AuthController@registarStep2Post']);
Route::get('registar/step3', 'AuthController@registarStep3');
Route::post('registar/step3', ['as' => '/registar/step3', 'uses' => 'AuthController@registarStep3Post']);
Route::get('registar/step4', 'AuthController@registarStep4');
Route::get('recuperar_password', 'AuthController@recuperarPassword');
Route::post('recuperar_password', ['as' => 'recuperar_password', 'uses' => 'AuthController@recuperarPasswordPost']);
Route::get('/novapassword/{token}', 'AuthController@novaPassword');
Route::post('/novapasswordpost', 'AuthController@novaPasswordPost');
Route::post('login/', ['as' => 'login', 'uses' => 'AuthController@postLogin']);
Route::get('logout', 'AuthController@getLogout');
Route::get('confirmar_email', 'AuthController@confirmEmail');
Route::get('email_confirmado', 'AuthController@confirmedEmail');

/*********************************************************************
 * 						END Auth / Sign Up Routes
 *********************************************************************/
Route::get('perfil', 'Portal\ProfileController@profile');
Route::post('perfil', ['as' => 'perfil', 'uses' => 'Portal\ProfileController@profilePost']);
Route::get('perfil/autenticacao', 'Portal\ProfileController@authentication');
Route::post('perfil/autenticacao/morada', ['as' => 'perfil/autenticacao/morada', 'uses' => 'Portal\ProfileController@addressAuthenticationPost']);
Route::post('perfil/autenticacao/identity', ['as' => 'perfil/autenticacao/identity', 'uses' => 'Portal\ProfileController@identityAuthenticationPost']);
Route::get('perfil/download', 'Portal\ProfileController@downloadAttachment');
Route::get('perfil/codigos', 'Portal\ProfileController@codesGet');
Route::post('perfil/codigos/password', ['as' => 'perfil/codigos/password', 'uses' => 'Portal\ProfileController@passwordPost']);
Route::post('perfil/codigos/codigo-pin', ['as' => 'perfil/codigos/codigo-pin', 'uses' => 'Portal\ProfileController@securityPinPost']);
Route::get('/banco', function () {
    return redirect('/portal/banco/saldo');
});
Route::get('/banco/sucesso', array('as' => 'banco/sucesso', 'uses' => 'Portal\BanksController@success'));
Route::get('/banco/erro', array('as' => 'banco/erro', 'uses' => 'Portal\BanksController@error'));
Route::get('/banco/saldo', 'Portal\BanksController@balance');
Route::get('/banco/depositar', 'Portal\BanksController@deposit');
Route::post('/banco/depositar', array('as' => 'banco/depositar', 'uses' => 'Portal\BanksController@depositPost'));
Route::post('/banco/depositar/paypal', array('as' => 'banco/depositar/paypal', 'uses' => 'Portal\PaypalController@paymentPost'));
Route::get('/banco/depositar/paypal/status', array('as' => 'banco/depositar/paypal/status', 'uses' => 'Portal\PaypalController@paymentStatus'));
Route::post('/banco/depositar/meowallet', array('as' => 'banco/depositar/meowallet', 'uses' => 'PaymentMethods\MeowalletPaymentController@redirectAction'));
Route::get('/banco/depositar/meowallet/success', array('as' => 'banco/depositar/meowallet/success', 'uses' => 'PaymentMethods\MeowalletPaymentController@successAction'));
Route::get('/banco/depositar/meowallet/failure', array('as' => 'banco/depositar/meowallet/failure', 'uses' => 'PaymentMethods\MeowalletPaymentController@failureAction'));
Route::post('/banco/depositar/meowallet/redirect', array('as' => 'banco/depositar/meowallet/redirect', 'uses' => 'PaymentMethods\MeowalletPaymentController@callbackAction'));
Route::get('/banco/levantar', 'Portal\BanksController@withdrawal');
Route::post('/banco/levantar', array('as' => 'banco/levantar', 'uses' => 'Portal\BanksController@withdrawalPost'));
Route::get('/banco/conta-pagamentos', 'Portal\BanksController@accounts');
Route::post('/banco/conta-pagamentos', 'Portal\BanksController@selectAccount');
Route::put('/banco/conta-pagamentos', 'Portal\BanksController@createAccount');
Route::delete('/banco/conta-pagamentos/{id}/remover', 'Portal\BanksController@removeAccount');
Route::get('/banco/consultar-bonus', 'Portal\BanksController@checkBonus');
Route::get('/promocoes', 'Portal\PromotionsController@index');
Route::get('/promocoes/porusar/{tipo?}', 'Portal\PromotionsController@index');
Route::get('/promocoes/activos', 'Portal\PromotionsController@activeBonuses');
Route::get('/promocoes/utilizados', 'Portal\PromotionsController@consumedBonuses');
Route::get('/promocoes/redeem/{bonus_id}', 'Portal\PromotionsController@redeemBonus');
Route::get('/promocoes/cancel/{bonus_id}', 'Portal\PromotionsController@cancelBonus');
Route::get('/comunicacao', function () {
    return redirect('/comunicacao/definicoes');
});
Route::get('comunicacao/definicoes', 'Portal\CommunicationsController@settingsGet');
Route::post('comunicacao/definicoes', ['as' => 'comunicacao/definicoes', 'uses' => 'Portal\CommunicationsController@settingsPost']);

Route::post('comunicacao/reclamacoes', ['as' => 'comunicacao/reclamacoes', 'uses' => 'Portal\CommunicationsController@complaintsPost']);
Route::get('comunicacao/reclamacoes', 'Portal\CommunicationsController@complaintsGet');

Route::get('perfil/mensagens/chat', ['uses' => 'Portal\MessageController@getChat']);
Route::post('perfil/mensagens/new', ['uses' => 'Portal\MessageController@postNewMessage']);
Route::get('perfil/mensagens/unreads', ['uses' => 'Portal\MessageController@getUnread']);
Route::post('perfil/mensagens/read', 'Portal\MessageController@readMessages');
Route::get('comunicacao/mensagens', 'Portal\MessageController@getMessages');
Route::get('/amigos', function () {
    return redirect('/amigos/convites');
});
Route::get('amigos/convites', 'Portal\FriendsNetworkController@invitesGet');
Route::get('amigos/rede', 'Portal\FriendsNetworkController@network');
Route::post('amigos/convites', ['as' => 'amigos/convites', 'uses' => 'Portal\FriendsNetworkController@invitesPost']);
Route::post('amigos/bulk-invites', 'Portal\FriendsNetworkController@inviteBulkPost');

// Histórico
Route::get('/historico', 'Portal\HistoryController@operations');
Route::get('/historico/details/{id}', ['middleware' => 'auth', 'uses' => 'Portal\HistoryController@betDetails']);
Route::post('/historico/operacoes', 'Portal\HistoryController@operationsPost');
// Jogo Responsável
Route::get('/jogo-responsavel', function () {
    return redirect('/jogo-responsavel/limites');
});
Route::get('jogo-responsavel/limites', 'Portal\ResponsibleGamingController@limitsGet');
Route::get('jogo-responsavel/last_logins', 'Portal\ResponsibleGamingController@getLastLogins');
Route::post('jogo-responsavel/limites', ['as' => 'jogo-responsavel/limites', 'uses' => 'Portal\ResponsibleGamingController@limitsPost']);
Route::get('jogo-responsavel/limites/apostas', 'Portal\ResponsibleGamingController@limitsBetsGet');
Route::post('jogo-responsavel/limites/apostas', ['as' => 'jogo-responsavel/limites/apostas', 'uses' => 'Portal\ResponsibleGamingController@limitsBetsPost']);
Route::get('jogo-responsavel/autoexclusao', 'Portal\ResponsibleGamingController@selfExclusionGet');
Route::post('jogo-responsavel/autoexclusao', ['as' => 'jogo-responsavel/autoexclusao', 'uses' => 'Portal\ResponsibleGamingController@selfExclusionPost']);
Route::post('jogo-responphpsavel/cancelar-autoexclusao', ['as' => 'jogo-responsavel/cancelar-autoexclusao', 'uses' => 'Portal\ResponsibleGamingController@cancelSelfExclusionPost']);
Route::post('jogo-responsavel/revogar-autoexclusao', ['as' => 'jogo-responsavel/revogar-autoexclusao', 'uses' => 'Portal\ResponsibleGamingController@revokeSelfExclusionPost']);
Route::get('definicoes', 'Portal\ProfileController@settings');
Route::get('/apostas', function () {
    return redirect('/desportos');
});
Route::get('/desportos', 'Portal\BetsController@sports');
Route::get('/desportos/destaque/{id}', 'Portal\BetsController@sports');
Route::get('/desportos/competicao/{id}', 'Portal\BetsController@sports');
Route::get('/desportos/mercados/{id}', 'Portal\BetsController@sports');
Route::get('/direto', 'Portal\BetsController@sports');
Route::get('/direto/mercados/{id}', 'Portal\BetsController@sports');
Route::get('/favoritos', 'Portal\BetsController@sports');
Route::get('/pesquisa/{query}', 'Portal\BetsController@sports');
Route::get('/info', 'Portal\BetsController@sports');
Route::get('/info/{term}', 'Portal\BetsController@sports');
Route::get('/registar', 'Portal\BetsController@sports');
Route::get('/direto/estatistica/{id}', 'Portal\BetsController@sports');
Route::get('/desportos/estatistica/{id}', 'Portal\BetsController@sports');


Route::get('/get-balance', ['middleware' => 'auth', 'uses' => 'Portal\ProfileController@getBalance']);
Route::get('/open-bets', ['middleware' => 'auth', 'as' => 'open-bets', 'uses' =>  'Portal\BetsController@openBets']);


//Route::get('/info','Portal\InfoController@index');
Route::get('/textos/sobre_nos','Portal\InfoController@aboutUs');
//Route::get('/info/afiliados','Portal\InfoController@affiliates');
Route::get('/textos/termos_e_condicoes','Portal\InfoController@terms');
Route::get('/textos/contactos','Portal\InfoController@contacts');
//Route::get('/info/proteccao_utilizador','Portal\InfoController@protect_user');
//Route::get('/info/ajuda','Portal\InfoController@help');
Route::get('/textos/bonus_e_promocoes','Portal\InfoController@promotions');
Route::get('/textos/faq','Portal\InfoController@faq');
Route::get('/textos/pagamentos','Portal\InfoController@pays');
Route::get('/textos/politica_priv', 'Portal\InfoController@politica_priv');
//Route::get('/info/politica_cookies', 'Portal\InfoController@politica_cookies');
//Route::get('/info/regras/{tipo?}/{game?}', 'Portal\InfoController@regras');
//Route::get('/info/dificuldades_tecnicas', 'Portal\InfoController@dificuldades_tecnicas');
Route::get('/textos/jogo_responsavel', 'Portal\InfoController@jogo_responsavel');

// Sportsbook
Route::post('/desporto/betslip', ['as' => 'betslip', 'uses' => 'BetslipController@placeBets']);

// Casino
//Route::get('/casino', 'Portal\CasinoController@casino');
Route::get('/casino', 'Portal\BetsController@sports');
Route::get('/casino/game_types', 'Portal\CasinoController@gameTypes');
Route::get('/casino/games', 'Portal\CasinoController@allGames');
Route::get('/casino/games/{type}', 'Portal\CasinoController@games');
Route::get('/casino/featured_games', 'Portal\CasinoController@featuredGames');

// NYX
Route::get('/nyx_wallet','NyxController@nyxWallet');

// Balance
Route::get('/balance', ['as' => 'balance', 'uses' => 'Portal\BalanceController@balance']);

// Odds

Route::match(['get', 'post'], '/odds/fixtures', ['as' => 'odds.fixtures', 'uses' => 'Portal\OddsController@fixtures']);
Route::match(['get', 'post'], '/odds/sports', ['as' => 'odds.sports', 'uses' => 'Portal\OddsController@sports']);
Route::match(['get', 'post'], '/odds/regions', ['as' => 'odds.regions', 'uses' => 'Portal\OddsController@regions']);
Route::match(['get', 'post'], '/odds/competitions', ['as' => 'odds.competitions', 'uses' => 'Portal\OddsController@competitions']);
Route::match(['get', 'post'], '/odds/markets', ['as' => 'odds.markets', 'uses' => 'Portal\OddsController@markets']);
Route::match(['get', 'post'], '/odds/selections', ['as' => 'odds.selections', 'uses' => 'Portal\OddsController@selections']);


/*********************************************************************
 *					   END Portal Routes
 *********************************************************************/
Route::get('/admin', function () {
    return view('ibetup');
});
Route::get('/share', function() {
    return Share::load('http://www.test.com', 'Isto é um teste')->facebook();
});
//Route::get('/apostas', 'Portal\BetsController@index');

/*****************************
 * BEGIN Dashboard (Backoffice) Routes
 *****************************/
Route::group(array('prefix' => 'dashboard'), function() {
    // Dashboard
    Route::get('/', 'DashboardController@index');
    Route::get('/jogadores', 'JogadoresController@index');
    Route::get('/jogadores/comprovativos/{jogador_id}', 'JogadoresController@comprovativoMorada');
    Route::post('jogadores/comprovativos/{jogador_id}', ['as' => 'dashboard/jogadores/comprovativos', 'uses' => 'JogadoresController@comprovativoMoradaPost']);
    Route::get('/jogadores/comprovativos/download/{jogador_id}', ['as' => '/dashboard/jogadores/comprovativos/download', 'uses' => 'JogadoresController@downloadComprovativoMorada']);
    Route::get('/depositos', 'DepositosController@index');
    Route::get('/levantamentos', 'LevantamentosController@index');
    Route::get('/apostas', 'ApostasController@index');
});
/*****************************
 * END Dashboard (Backoffice) Routes
 *****************************/

Route::any('server', ['uses' => 'SoapController@server']);
