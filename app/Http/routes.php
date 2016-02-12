<?php/*|--------------------------------------------------------------------------| Application Routes|--------------------------------------------------------------------------|| Here is where you can register all of the routes for an application.| It's a breeze. Simply tell Laravel the URIs it should respond to| and give it the controller to call when that URI is requested.|*//********************************************************************* *					   BEGIN Portal Routes *********************************************************************///Route::get('/', function () {//    return redirect()->intended('/apostas/desportos');//});Route::get('/', 'Portal\HomeController@index');/********************************************************************* * 						BEGIN Auth / Sign Up Routes *********************************************************************/Route::post('api/login', ['as' => 'api/login', 'uses' => 'ApiController@handleRequests']);Route::post('/', ['uses' => 'ApiController@handleRequests']);Route::get('/registar', function () {    return redirect()->intended('/registar/step1');});Route::get('registar/step1', 'AuthController@registarStep1');Route::post('registar/step1', ['as' => 'registar/step1', 'uses' => 'AuthController@registarStep1Post']);Route::get('registar/step2', 'AuthController@registarStep2');Route::post('registar/step2', ['as' => '/registar/step2', 'uses' => 'AuthController@registarStep2Post']);Route::get('registar/step3', 'AuthController@registarStep3');Route::post('registar/step3', ['as' => '/registar/step3', 'uses' => 'AuthController@registarStep3Post']);Route::get('registar/step4', 'AuthController@registarStep4');Route::get('recuperar_password', 'AuthController@recuperarPassword');Route::post('recuperar_password', ['as' => 'recuperar_password', 'uses' => 'AuthController@recuperarPasswordPost']);Route::post('login/', ['as' => 'login', 'uses' => 'AuthController@postLogin']);Route::get('logout', 'AuthController@getLogout');Route::get('confirmar_email', 'AuthController@confirmEmail');Route::get('email_confirmado', 'AuthController@confirmedEmail');/********************************************************************* * 						END Auth / Sign Up Routes *********************************************************************/Route::get('perfil', 'Portal\ProfileController@profile');Route::post('perfil', ['as' => 'perfil', 'uses' => 'Portal\ProfileController@profilePost']);Route::get('perfil/autenticacao', 'Portal\ProfileController@authentication');Route::get('perfil/autenticacao/morada', 'Portal\ProfileController@addressAuthentication');Route::post('perfil/autenticacao/morada', ['as' => 'perfil/autenticacao/morada', 'uses' => 'Portal\ProfileController@addressAuthenticationPost']);Route::post('perfil/autenticacao', ['as' => 'perfil/autenticacao', 'uses' => 'Portal\ProfileController@authenticationPost']);Route::get('perfil/password', 'Portal\ProfileController@passwordGet');Route::post('perfil/password', ['as' => 'perfil/password', 'uses' => 'Portal\ProfileController@passwordPost']);Route::get('perfil/codigo-pin', 'Portal\ProfileController@securityPinGet');Route::post('perfil/codigo-pin', ['as' => 'perfil/codigo-pin', 'uses' => 'Portal\ProfileController@securityPinPost']);Route::get('/banco', function () {    return redirect()->intended('/portal/banco/saldo');});Route::get('/banco/sucesso', array('as' => 'banco/sucesso', 'uses' => 'Portal\BanksController@success'));Route::get('/banco/erro', array('as' => 'banco/erro', 'uses' => 'Portal\BanksController@error'));Route::get('/banco/saldo', 'Portal\BanksController@balance');Route::get('/banco/depositar', 'Portal\BanksController@deposit');Route::post('/banco/depositar', array('as' => 'banco/depositar', 'uses' => 'Portal\BanksController@depositPost'));Route::post('/banco/depositar/paypal', array('as' => 'banco/depositar/paypal', 'uses' => 'Portal\PaypalController@paymentPost'));Route::get('/banco/depositar/paypal/status', array('as' => 'banco/depositar/paypal/status', 'uses' => 'Portal\PaypalController@paymentStatus'));Route::get('/banco/levantar', 'Portal\BanksController@withdrawal');Route::post('/banco/levantar', array('as' => 'banco/levantar', 'uses' => 'Portal\BanksController@withdrawalPost'));Route::get('/banco/conta-pagamentos', 'Portal\BanksController@accounts');Route::post('/banco/conta-pagamentos', 'Portal\BanksController@selectAccount');Route::put('/banco/conta-pagamentos', 'Portal\BanksController@createAccount');Route::delete('/banco/conta-pagamentos/{id}/remover', 'Portal\BanksController@removeAccount');Route::get('/banco/consultar-bonus', 'Portal\BanksController@checkBonus');Route::get('/promocoes', 'Portal\PromotionsController@index');Route::get('/promocoes/pendentes', 'Portal\PromotionsController@pendents');Route::get('/promocoes/utilizados', 'Portal\PromotionsController@used');Route::get('/comunicacao', function () {    return redirect()->intended('/comunicacao/definicoes');});Route::get('comunicacao/definicoes', 'Portal\CommunicationsController@settingsGet');Route::post('comunicacao/definicoes', ['as' => 'comunicacao/definicoes', 'uses' => 'Portal\CommunicationsController@settingsPost']);Route::get('comunicacao/mensagens', 'Portal\CommunicationsController@messagesGet');Route::get('/amigos', function () {    return redirect()->intended('/amigos/convites');});Route::get('amigos/convites', 'Portal\FriendsNetworkController@invitesGet');Route::post('amigos/convites', ['as' => 'amigos/convites', 'uses' => 'Portal\FriendsNetworkController@invitesPost']);Route::get('amigos/rede', 'Portal\FriendsNetworkController@network');// HistóricoRoute::get('/historico', 'Portal\HistoryController@history');Route::get('/historico/recente', 'Portal\HistoryController@recentGet');Route::get('/historico/depositos', 'Portal\HistoryController@depositsGet');Route::get('/historico/levantamentos', 'Portal\HistoryController@withdrawalsGet');Route::get('/historico/operacoes', 'Portal\HistoryController@operations');Route::post('/historico/operacoes', 'Portal\HistoryController@operationsPost');Route::get('/jogo-responsavel', function () {    return redirect()->intended('/jogo-responsavel/limites');});Route::get('jogo-responsavel/limites', 'Portal\ResponsibleGamingController@limitsGet');Route::post('jogo-responsavel/limites', ['as' => 'jogo-responsavel/limites', 'uses' => 'Portal\ResponsibleGamingController@limitsPost']);Route::get('jogo-responsavel/autoexclusao', 'Portal\ResponsibleGamingController@selfExclusionGet');Route::post('jogo-responsavel/autoexclusao', ['as' => 'jogo-responsavel/autoexclusao', 'uses' => 'Portal\ResponsibleGamingController@selfExclusionPost']);Route::get('definicoes', 'Portal\ProfileController@settings');Route::get('/apostas', function () {    return redirect()->intended('/desportos');});Route::get('/desportos', 'Portal\BetsController@sports');Route::get('/aovivo', 'Portal\BetsController@sports');Route::post('/bets/load/{value}', ['as' => 'bets/load/leftbar', 'uses' => 'Portal\BetsController@loadPost']);Route::get('/get-balance', ['uses' => 'Portal\ProfileController@getBalance']);// InfoRoute::get('/sobre_nos','Portal\InfoController@aboutUs');Route::get('/afiliados','Portal\InfoController@affiliates');Route::get('/termos_e_condicoes','Portal\InfoController@terms');Route::get('/contactos','Portal\InfoController@contacts');Route::get('/ajuda','Portal\InfoController@help');Route::get('/promocoes','Portal\InfoController@promotions');Route::get('/faq','Portal\InfoController@terms');Route::get('/territorios_restritos','Portal\InfoController@restricted');// Casino//Route::get('/casino', 'Portal\CasinoController@casino');Route::get('/casino', 'Portal\BetsController@sports');Route::get('/casino/game_types', 'Portal\CasinoController@gameTypes');Route::get('/casino/games', 'Portal\CasinoController@allGames');Route::get('/casino/games/{type}', 'Portal\CasinoController@games');Route::get('/casino/featured_games', 'Portal\CasinoController@featuredGames');// NYXRoute::get('/nyx_wallet','NyxController@nyxWallet');/********************************************************************* *					   END Portal Routes *********************************************************************/Route::get('/admin', function () {    return view('ibetup');});Route::get('/share', function() {    return Share::load('http://www.test.com', 'Isto é um teste')->facebook();});//Route::get('/apostas', 'Portal\BetsController@index');Route::get('/bc', 'ProxyController@proxy');//swarm-partner.betcontruct.com/***************************** * BEGIN Dashboard (Backoffice) Routes *****************************/Route::group(array('prefix' => 'dashboard'), function() {    // Dashboard    Route::get('/', 'DashboardController@index');    Route::get('/jogadores', 'JogadoresController@index');    Route::get('/jogadores/comprovativos/{jogador_id}', 'JogadoresController@comprovativoMorada');    Route::post('jogadores/comprovativos/{jogador_id}', ['as' => 'dashboard/jogadores/comprovativos', 'uses' => 'JogadoresController@comprovativoMoradaPost']);    Route::get('/jogadores/comprovativos/download/{jogador_id}', ['as' => '/dashboard/jogadores/comprovativos/download', 'uses' => 'JogadoresController@downloadComprovativoMorada']);    Route::get('/depositos', 'DepositosController@index');    Route::get('/levantamentos', 'LevantamentosController@index');    Route::get('/apostas', 'ApostasController@index');});/***************************** * END Dashboard (Backoffice) Routes *****************************/