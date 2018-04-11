Handlebars.registerPartial('perfil.menu.history', require('./templates/history.html'));
Handlebars.registerPartial('history_bet_details', require('./templates/history_bet_details.html'));
Handlebars.registerPartial('history_transaction_details', require('./templates/history_transaction_details.html'));
Handlebars.registerPartial('messages_details', require('./templates/messages_details.html'));

PerfilHistory = new function () {
    var self = this;
    var options = {
        page: 'historico',
        userId: $('#user-id').text(),
        events: require('./sub/operations')
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
        this.unload();

        make();
    };

    function make()
    {
        $("#perfil-container").html(Template.apply("perfil", { menus: menus, options: options }));

        var menu = $("#perfil-container").find('.profile-sidebar');

        menu.addClass('mobile-sidebar');

        menu.html(Template.apply("perfil.menu.history", options));

        if (typeof ProfileRouter !== "undefined")
            ProfileRouter.routeLinks();

        fetch();
    }

    function fetch()
    {
        if (ajaxRequest !== null) {
            ajaxRequest.abort();
        }
        var url = '/ajax-perfil/historico';
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
        var testing = $('<div>').html(content);
        var container = $("#perfil-container");
        container.find('.profile-content').empty().append(testing.find('#content'));
        container.find('.profile-sidebar').empty().append(testing.find('#sidebar'));

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
