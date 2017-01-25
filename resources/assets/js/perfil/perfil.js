Handlebars.registerPartial('perfil', require('./perfil.html'));
Handlebars.registerPartial('perfil.menu.generic', require('./templates/generic.html'));

Perfil = new function () {
    var options = {
        page: 'perfil',
        sub: 'info',
        userId: $('#user-id').text(),
        events: {
            load: function () {},
            unload: function () {}
        }
    };
    var menus = require('./perfil-routes');
    var ajaxRequest = null;

    init();

    function init()
    {
        // init stuff
    }

    this.make = function(_options)
    {
        options.events.unload();
        options.sub = 'info';
        Helpers.updateOptions(_options, options);

        console.log(options);

        make();
    };

    function make()
    {
        console.log('Loading page', options, menus);
        options.events = menus[options.page].sub[options.sub].events || {
            load: function () {},
            unload: function () {}
        };

        $("#perfil-container").html(Template.apply("perfil", { menus: menus, options: options }));

        var menu = $("#perfil-container").find('.profile-sidebar');

        menu.html(Template.apply("perfil.menu.generic", { menus: menus[options.page].sub, options: options }));

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

        options.events.load();
    }

    function redirect(err) {
        console.log(err);
        options.events.unload();
        page('/');
    }
};
