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

use App\User;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;

    Route::any('bem-vindo', ['as' => 'bem-vindo', 'uses' => 'Portal\HomeController@wellcome']);

    Route::get('/', ['as' => '/', 'uses' => 'Portal\BetsController@sports']);
    /*********************************************************************
     *                        BEGIN Auth / Api Routes
     *********************************************************************/
    Route::post('api/token', ['as' => 'api/token', 'uses' => 'AuthController@getToken']);
    /* Utils */
    Route::get('api/utils/sign-up', ['as' => 'api/utils/sign-up', 'uses' => 'Api\UtilsController@getMixSignLists']);
    Route::get('api/info/doc', ['as' => 'api/info/doc', 'uses' => 'Api\InfoController@getDocInfo']);
    Route::get('api/info/childes', ['as' => 'api/info/childes', 'uses' => 'Api\InfoController@getChildesDocs']);
    Route::get('api/competitions', ['as' => 'api/competitions', 'uses' => 'Portal\BetsController@highlights']);
    Route::post('api/sign-up', ['as' => 'api/sign-up', 'uses' => 'Api\SignUpController@postStep1']);
    Route::get('/api/banners', ['as' => 'api/banners', 'uses' => 'Api\BannersController@getBanners']);
    Route::get('/ads/{link}', ['uses' => 'Portal\InfoController@adService']);
    Route::post('api/academiadeapostasapi', ['uses' => 'Api\ApiController@academiaDeApostas']);

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
    /* Autoexclusão*/
    Route::get('api/user/self-exclusion', ['as' => 'api/user/self-exclusion', 'uses' => 'Api\RespGameController@selfExclusionGet']);
    Route::post('api/user/self-exclusion', ['as' => 'api/user/self-exclusion', 'uses' => 'Api\RespGameController@selfExclusionPost']);
    Route::post('api/user/self-exclusion/cancel', ['as' => 'api/user/self-exclusion/cancel', 'uses' => 'Api\RespGameController@cancelSelfExclusionPost']);
    Route::post('api/user/self-exclusion/revoke', ['as' => 'api/user/self-exclusion/revoke', 'uses' => 'Api\RespGameController@revokeSelfExclusionPost']);
});
/*********************************************************************
 * 						BEGIN Auth / Sign Up Routes
 *********************************************************************/
Route::group(['middleware' => 'affiliates'], function () {
    Route::post('api/login', ['as' => 'api/login', 'uses' => 'ApiController@handleRequests']);
    Route::post('api/check-users', ['as' => 'api/checkUsers', 'uses' => 'AuthController@postApiCheck']);
    Route::post('api/check-identity', ['as' => 'api/checkIdentity', 'uses' => 'AuthController@postApiCheckIdentity']);
    Route::post('/', ['as' => '/', 'uses' => 'ApiController@handleRequests']);


    Route::get('captcha', 'AuthController@captcha');
    Route::post('recuperar_password', ['as' => 'recuperar_password', 'uses' => 'AuthController@recuperarPasswordPost']);
    Route::get('/nova_password/{token}', 'AuthController@novaPassword');
    Route::post('/nova_password', 'AuthController@novaPasswordPost');
    Route::post('login/', ['as' => 'login', 'uses' => 'AuthController@postLogin']);
    Route::get('logout', 'AuthController@getLogout');
    Route::get('confirmar_email', 'AuthController@confirmEmail');
});

/*********************************************************************
 * 						END Auth / Sign Up Routes
 *********************************************************************/
Route::group(['prefix' => 'ajax-perfil'], function () {
    Route::get('perfil/info', 'Portal\ProfileController@profile');
    Route::get('perfil/autenticacao', 'Portal\ProfileController@authentication');
    Route::get('perfil/codigos', 'Portal\ProfileController@codesGet');

    Route::get('banco/saldo', 'Portal\BanksController@balance');
    Route::get('banco/depositar', 'Portal\BanksController@deposit');
    Route::get('banco/taxes', 'Portal\BanksController@getTaxes');

    Route::get('banco/levantar', 'Portal\BanksController@withdrawal');
    Route::get('banco/conta-pagamentos', 'Portal\BanksController@accounts');

    Route::get('bonus/porusar', 'Portal\PromotionsController@index');
    Route::get('bonus/activos', 'Portal\PromotionsController@activeBonuses');
    Route::get('bonus/utilizados', 'Portal\PromotionsController@consumedBonuses');
    Route::get('bonus/sport/redeem/{bonus_id}', 'Portal\PromotionsController@redeemSportBonus');
    Route::get('bonus/sport/cancel/{bonus_id}', 'Portal\PromotionsController@cancelSportBonus');
    Route::get('bonus/casino/redeem/{bonus_id}', 'Portal\PromotionsController@redeemCasinoBonus');
    Route::get('bonus/casino/cancel/{bonus_id}', 'Portal\PromotionsController@cancelCasinoBonus');
    Route::get('bonus/amigos', 'Portal\FriendsNetworkController@invitesGet');
    Route::get('bonus/amigos/rede', 'Portal\FriendsNetworkController@network');

    Route::get('comunicacao/definicoes', 'Portal\CommunicationsController@settingsGet');
    Route::get('comunicacao/reclamacoes', 'Portal\CommunicationsController@complaintsGet');
    Route::get('perfil/mensagens/chat', ['uses' => 'Portal\MessageController@getChat']);
    Route::get('perfil/mensagens/unreads', ['uses' => 'Portal\MessageController@getUnread']);
    Route::get('comunicacao/mensagens', 'Portal\MessageController@getMessages');

    Route::get('jogo-responsavel/limites', 'Portal\ResponsibleGamingController@limitsGet');
    Route::get('jogo-responsavel/last_logins', 'Portal\ResponsibleGamingController@getLastLogins');
    Route::get('jogo-responsavel/autoexclusao', 'Portal\ResponsibleGamingController@selfExclusionGet');

    Route::get('historico', 'Portal\HistoryController@operations');
    Route::get('historico/details/{id}', ['middleware' => 'auth', 'uses' => 'Portal\HistoryController@betDetails']);
    Route::get('/historico/session-details/{id}', ['middleware' => 'auth', 'uses' => 'Portal\HistoryController@sessionDetails']);
});

Route::group(['prefix' => 'ajax-register'], function () {
    Route::get('step1', 'AuthController@registarStep1');
    Route::get('step2', 'AuthController@registarStep2');
    Route::get('step3', 'AuthController@registarStep3');

    Route::post('step1', ['as' => 'registar/step1', 'uses' => 'AuthController@registarStep1Post']);
    Route::post('step2', ['as' => 'registar/step2', 'uses' => 'AuthController@registarStep2Post']);
    Route::post('step3', ['as' => 'registar/step3', 'uses' => 'AuthController@registarStep3Post']);
});


Route::post('perfil', ['as' => 'perfil', 'uses' => 'Portal\ProfileController@profilePost']);
Route::post('perfil/autenticacao/morada', ['as' => 'perfil/autenticacao/morada', 'uses' => 'Portal\ProfileController@addressAuthenticationPost']);
Route::post('perfil/autenticacao/identity', ['as' => 'perfil/autenticacao/identity', 'uses' => 'Portal\ProfileController@identityAuthenticationPost']);
Route::get('perfil/autenticacao/download', 'Portal\ProfileController@getDownloadAttachment');
Route::get('perfil/autenticacao/delete', 'Portal\ProfileController@getDeleteAttachment');
Route::post('perfil/codigos/password', ['as' => 'perfil/codigos/password', 'uses' => 'Portal\ProfileController@passwordPost']);
Route::post('perfil/codigos/codigo-pin', ['as' => 'perfil/codigos/codigo-pin', 'uses' => 'Portal\ProfileController@securityPinPost']);

Route::get('/perfil/banco/depositar/paypal/status', array('as' => 'perfil/banco/depositar/paypal/status', 'uses' => 'PaymentMethods\PaypalController@paymentStatus'));
Route::get('/perfil/banco/depositar/meowallet/success', array('as' => 'perfil/banco/depositar/meowallet/success', 'uses' => 'PaymentMethods\MeowalletPaymentController@successAction'));
Route::get('/perfil/banco/depositar/meowallet/failure', array('as' => 'perfil/banco/depositar/meowallet/failure', 'uses' => 'PaymentMethods\MeowalletPaymentController@failureAction'));
Route::post('/banco/depositar', array('as' => 'banco/depositar', 'uses' => 'Portal\BanksController@depositPost'));
Route::post('/perfil/banco/depositar/paypal', array('as' => 'perfil/banco/depositar/paypal', 'uses' => 'PaymentMethods\PaypalController@paymentPost'));
Route::post('/perfil/banco/depositar/swift-pay', array('as' => 'perfil/banco/depositar/swift-pay', 'uses' => 'PaymentMethods\SwiftPaymentsController@paymentPost'));
Route::post('/perfil/banco/depositar/swift-pay/redirect', array('as' => 'perfil/banco/depositar/swift-pay/redirect', 'uses' => 'PaymentMethods\SwiftPaymentsController@callbackAction'));
Route::post('/perfil/banco/depositar/meowallet', array('as' => 'perfil/banco/depositar/meowallet', 'uses' => 'PaymentMethods\MeowalletPaymentController@redirectAction'));
Route::post('/perfil/banco/depositar/meowallet/redirect', array('as' => 'perfil/banco/depositar/meowallet/redirect', 'uses' => 'PaymentMethods\MeowalletPaymentController@callbackAction'));
Route::post('/banco/levantar', array('as' => 'banco/levantar', 'uses' => 'Portal\BanksController@withdrawalPost'));
Route::post('/banco/conta-pagamentos', 'Portal\BanksController@selectAccount');
Route::put('/banco/conta-pagamentos', 'Portal\BanksController@createAccount');
Route::delete('/banco/conta-pagamentos/{id}/remover', 'Portal\BanksController@removeAccount');

Route::post('/promocoes/amigos/convites', ['as' => 'amigos/convites', 'uses' => 'Portal\FriendsNetworkController@invitesPost']);
Route::post('/promocoes/amigos/bulk-invites', 'Portal\FriendsNetworkController@inviteBulkPost');

Route::post('comunicacao/definicoes', ['as' => 'comunicacao/definicoes', 'uses' => 'Portal\CommunicationsController@settingsPost']);
Route::post('comunicacao/reclamacoes', ['as' => 'comunicacao/reclamacoes', 'uses' => 'Portal\CommunicationsController@complaintsPost']);

Route::post('perfil/mensagens/new', ['uses' => 'Portal\MessageController@postNewMessage']);
Route::post('perfil/mensagens/upload', ['uses' => 'Portal\MessageController@postNewUpload']);
Route::post('perfil/mensagens/read', 'Portal\MessageController@readMessages');

// Histórico
Route::post('/historico/operacoes', 'Portal\HistoryController@operationsPost');

// Jogo Responsável
Route::post('jogo-responsavel/limites/depositos', ['as' => 'jogo-responsavel/limites/depositos', 'uses' => 'Portal\ResponsibleGamingController@limitsDepositsPost']);
Route::post('jogo-responsavel/limites/apostas', ['as' => 'jogo-responsavel/limites/apostas', 'uses' => 'Portal\ResponsibleGamingController@limitsBetsPost']);
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
Route::get('/info/{term?}', 'Portal\BetsController@sports');
Route::get('/perfil/{page?}/{sub?}', 'Portal\BetsController@sports');
Route::get('/registar/{step?}', 'Portal\BetsController@sports');
Route::get('/direto/estatistica/{id}', 'Portal\BetsController@sports');
Route::get('/desportos/estatistica/{id}', 'Portal\BetsController@sports');
Route::get('/favoritos', 'Portal\BetsController@sports');
Route::get('/afiliados/export', 'DashboardController@exportCsv');
Route::get('/daily-bet', 'SuggestionsController@dailyBet');
Route::get('/betslip/add', 'BetslipController@addbets');

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

// Promotions
Route::get('/promocoes', 'Portal\BetsController@sports');
Route::get('/promotions', 'PromotionsController@index');
Route::get('/promotions/get-image', 'PromotionsController@getImage');
Route::get('/promotions/bigodd', 'PromotionsController@bigodd');

// Mobile
Route::get('/mobile/menu-desportos', 'Portal\BetsController@sports');
Route::get('/mobile/betslip', 'Portal\BetsController@sports');
Route::get('/mobile/login', 'Portal\BetsController@sports');
Route::get('/mobile/menu', 'Portal\BetsController@sports');

// Casino
if (config('app.casino_available')) {
    Route::get('/casino', 'Casino\CasinoController@index');
} else {
    Route::get('/casino', 'Portal\BetsController@sports');
}

Route::get('/casino/game/{id}', ['middleware' => 'auth', 'uses' => 'Casino\CasinoGameController@index']);
Route::get('/casino/netent/{id}', ['uses' => 'Casino\CasinoGameController@openNetentGame']);
Route::get('/casino/game-demo/{id}', 'Casino\CasinoGameController@demo');
Route::get('/casino/pesquisa', 'Casino\CasinoController@index');
Route::get('/casino/pesquisa/{term}', 'Casino\CasinoController@index');
Route::get('/casino/favorites', 'Casino\CasinoController@index');
Route::get('/casino/registar/{step?}', 'Casino\CasinoController@index');
Route::get('/casino/games/favorites', ['middleware' => 'auth', 'uses' => 'Casino\CasinoFavoritesController@index']);
Route::post('/casino/games/favorites', ['middleware' => 'auth', 'uses' => 'Casino\CasinoFavoritesController@store']);
Route::delete('/casino/games/favorites/{id}', ['middleware' => 'auth', 'uses' => 'Casino\CasinoFavoritesController@destroy']);
Route::get('/casino/info', 'Casino\CasinoController@index');
Route::get('/casino/info/sobre_nos', 'Casino\CasinoController@index');
Route::get('/casino/info/termos_e_condicoes', 'Casino\CasinoController@index');
Route::get('/casino/info/politica_privacidade', 'Casino\CasinoController@index');
Route::get('/casino/info/faq', 'Casino\CasinoController@index');
Route::get('/casino/info/bonus_e_promocoes', 'Casino\CasinoController@index');
Route::get('/casino/info/pagamentos', 'Casino\CasinoController@index');
Route::get('/casino/info/jogo_responsavel', 'Casino\CasinoController@index');
Route::get('/casino/info/contactos', 'Casino\CasinoController@index');
Route::get('/casino/perfil', 'Casino\CasinoController@index');
Route::get('/casino/perfil/banco/{sub?}', 'Casino\CasinoController@index');
Route::get('/casino/perfil/bonus/{sub?}', 'Casino\CasinoController@index');
Route::get('/casino/perfil/historico', 'Casino\CasinoController@index');
Route::get('/casino/perfil/comunicacao/{sub?}', 'Casino\CasinoController@index');
Route::get('/casino/perfil/jogo-responsavel/{sub?}', 'Casino\CasinoController@index');
Route::get('/casino/perfil/banco/{sub?}', 'Casino\CasinoController@index');
Route::get('/casino/game-details/{token}', 'Casino\CasinoGameController@report');
Route::get('/casino/mobile/login', 'Casino\CasinoController@index');
Route::get('/casino/mobile/menu-casino', 'Casino\CasinoController@index');
Route::get('/casino/promocoes', 'Casino\CasinoController@index');
Route::get('/casino/mobile/menu', 'Casino\CasinoController@index');
Route::get('/casino/mobile/launch/{gameid}', 'Casino\CasinoController@index');

// Balance
Route::get('/balance', ['as' => 'balance', 'uses' => 'Portal\BalanceController@balance']);

// Odds
Route::match(['get', 'post'], '/odds/fixtures', ['as' => 'odds.fixtures', 'uses' => 'Portal\OddsController@fixtures']);
Route::match(['get', 'post'], '/odds/sports', ['as' => 'odds.sports', 'uses' => 'Portal\OddsController@sports']);
Route::match(['get', 'post'], '/odds/regions', ['as' => 'odds.regions', 'uses' => 'Portal\OddsController@regions']);
Route::match(['get', 'post'], '/odds/competitions', ['as' => 'odds.competitions', 'uses' => 'Portal\OddsController@competitions']);
Route::match(['get', 'post'], '/odds/markets', ['as' => 'odds.markets', 'uses' => 'Portal\OddsController@markets']);
Route::match(['get', 'post'], '/odds/selections', ['as' => 'odds.selections', 'uses' => 'Portal\OddsController@selections']);

// Testing Platform
if (env('APP_ENV', 'production') === 'local' && env('APP_DEBUG', false)) {
    Route::get('/tester/{id?}', ['uses' => 'Portal\TesterController@listViews']);
    Route::get('/tester/{id?}/{type?}', ['uses' => 'Portal\TesterController@index']);
}


/*********************************************************************
 *					   END Portal Routes
 *********************************************************************/
