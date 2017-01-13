Handlebars.registerPartial('perfil', require('./perfil.html'));
Handlebars.registerPartial('perfil.menu.generic', require('./menus/generic.html'));

Perfil = new function () {
    var options = {
        page: 'perfil',
        sub: 'info',
        userId: $('#user-id').text()
    };
    var menus = {
        perfil: [
            {key: 'info', name: 'Info. pessoal', link: ''},
            {key: 'autenticacao', name: 'Autenticação', link: '/autenticacao'},
            {key: 'codigos', name: 'Códigos Acesso', link: '/codigos'}
        ],
        banco: [
            {'key': 'saldo', 'name': 'Saldo', 'link': '/banco/saldo'},
            {'key': 'depositar', 'name': 'Depositar', 'link': '/banco/depositar'},
            {'key': 'pagamentos', 'name': 'Conta Pagamentos', 'link': '/banco/conta-pagamentos'},
            {'key': 'levantar', 'name': 'Levantar', 'link': '/banco/levantar'},
        ],
        bonus: [
            {'key': 'porusar', 'name': 'Por Utilizar', 'link': '/bonus/porusar'},
            {'key': 'activos', 'name': 'Em Utilização', 'link': '/bonus/activos'},
            {'key': 'utilizados', 'name': 'Utilizados', 'link': '/bonus/utilizados'},
            {'key': 'amigos', 'name': 'Convidar Amigos', 'link': '/bonus/amigos'},
        ],
        comunicacao: [
            {'key': 'mensagens', 'name': 'Mensagens', 'link': '/comunicacao/mensagens'},
            {'key': 'definicoes', 'name': 'Definições', 'link': '/comunicacao/definicoes'},
            {'key': 'reclamacoes', 'name': 'Reclamações', 'link': '/comunicacao/reclamacoes'},
        ],
        'jogo-responsavel': [
            {'key': 'limites', 'name': 'Limites', 'link': '/jogo-responsavel/limites'},
            {'key': 'autoexclusao', 'name': 'Auto-exclusão', 'link': '/jogo-responsavel/autoexclusao'},
            {'key': 'last_logins', 'name': 'Últimos Acessos', 'link': '/jogo-responsavel/last_logins'},
        ],
    };
    var ajaxRequest = null;

    init();

    function init()
    {
        // init stuff
    }

    this.make = function(_options)
    {
        options.sub = 'info';
        Helpers.updateOptions(_options, options);

        console.log(options);

        make();
    };

    function make()
    {
        $("#perfil-container").html(Template.apply("perfil", options));

        var menu = $("#perfil-container").find('.profile-sidebar');

        menu.html(Template.apply("perfil.menu.generic", { menus: menus[options.page], options: options }));

        fetch();
    }

    function fetch()
    {
        if (ajaxRequest !== null) ajaxRequest.abort();
        var url = '/ajax-perfil/' + options.page + '/' + options.sub;
        console.log('Requesting', url);
        ajaxRequest = $.ajax({
            url: url,
            error: redirect,
            success: render
        });
    }

    function render(content)
    {
        ajaxRequest = null;

        var container = $("#perfil-container").find('.profile-content');

        container.html(content);
    }

    function redirect(err) {
        console.log(err);
        page('/');
    }
};
