Handlebars.registerPartial('perfil', require('./perfil.html'));
Handlebars.registerPartial('perfil.menu.generic', require('./templates/generic.html'));

Perfil = new function () {
    var self = this;
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
        // console.log (_options);
        this.unload();
        options.sub = 'info';
        Helpers.updateOptions(_options, options);

        // console.log(options);

        make();
    };

    function make()
    {
        console.log('Loading page', options.page, options.sub);
        options.events = menus[options.page].sub[options.sub].events || {
            load: function () {},
            unload: function () {}
        };

        $("#perfil-container").html(Template.apply("perfil", { menus: menus, options: options }));

        var menu = $("#perfil-container").find('.profile-sidebar');

        menu.html(Template.apply("perfil.menu.generic", { menus: menus[options.page].sub, options: options }));

        if (typeof ProfileRouter !== "undefined")
            ProfileRouter.routeLinks();

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
        if (container.length === 0) {
            window.setTimeout(() => {
                    render(content);},
                300);
        }

        container.html(content);

        options.events.load();
    }

    function redirect(err) {
        ajaxRequest = null;
        console.log(err);
        self.unload();
        if (err.statusText === 'abort') {

        } else {
            page('/');
        }
    }

    this.unload = function() {
        if (options && options.events && typeof options.events.unload === 'function')
            options.events.unload();
    }
};
