ProfileRouter = new (function () {
    var routes = [
        '/perfil',
        '/perfil/info',
        '/perfil/autenticacao',
        '/perfil/codigos',
        '/perfil/banco/saldo',
        '/perfil/banco/depositar',
        '/perfil/banco/conta-pagamentos',
        '/perfil/banco/levantar',
        '/perfil/bonus/porusar',
        '/perfil/bonus/activos',
        '/perfil/bonus/utilizados',
        '/perfil/bonus/amigos',
        '/perfil/historico',
        '/perfil/comunicacao/mensagens',
        '/perfil/comunicacao/definicoes',
        '/perfil/comunicacao/reclamacoes',
        '/perfil/jogo-responsavel/limites',
        '/perfil/jogo-responsavel/autoexclusao',
        '/perfil/jogo-responsavel/last_logins'
    ];


    this.routeLinks = function () {
        $("#perfil-container").find('a').each(function (index, link) {
            var route = $(link).attr('href');

            if ($.inArray(route, routes) !== -1) {
                $(link).click(function (event) {
                    event.preventDefault();
                    router.push($(this).attr('href'));
                });
            }
        });
    }
});
