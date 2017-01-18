Handlebars.registerPartial('perfil.menu.history', require('./menus/history.html'));

PerfilHistory = new function () {
    var options = {
        page: 'historico',
        userId: $('#user-id').text()
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
        make();
    };

    function make()
    {
        $("#perfil-container").html(Template.apply("perfil", { menus: menus, options: options }));

        var menu = $("#perfil-container").find('.profile-sidebar');

        menu.html(Template.apply("perfil.menu.history", options));

        fetch();
    }

    function fetch()
    {
        if (ajaxRequest !== null) ajaxRequest.abort();
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
    }

    function redirect(err) {
        console.log(err);
        page('/');
    }
};
