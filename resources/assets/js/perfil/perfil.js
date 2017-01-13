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
            {key: 'info', name: 'Info. pessoal', link: '/perfil'},
            {key: 'autenticacao', name: 'Autenticação', link: '/perfil/autenticacao'},
            {key: 'codigos', name: 'Códigos Acesso', link: '/perfil/codigos'}
        ]
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
