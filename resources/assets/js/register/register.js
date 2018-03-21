Handlebars.registerPartial('register', require('./register.html'));

Register = new function () {
    var self = this;
    var options = {
        step: 'step2',
        events: {
            load: function () {},
            unload: function () {}
        }
    };
    var menus = {
        step2: { events: require('./steps/step2') },
        step3: { events: require('./steps/step3') }
    };
    var ajaxRequest = null;

    init();

    function init()
    {
        $('#stats').hide();
    }

    this.make = function(ctx, next)
    {
        this.unload();

        if (ctx.params.step) {
            options.step = ctx.params.step;

            make();
        }
    };

    function make()
    {
        options.events = menus[options.step].events || {
            load: function () {},
            unload: function () {}
        };

        // show loading
        $("#register-container").html(Template.apply("register", { menus: menus, options: options }));

        fetch();
    }

    function fetch()
    {
        if (ajaxRequest !== null) ajaxRequest.abort();
        var url = '/ajax-register/' + options.step;
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

        if ("object" === typeof content) {
            if (content.type === 'redirect') {
                return page(content.redirect);
            }
        }

        var container = $("#register-container");

        container.html(content);

        options.events.load();
    }

    function redirect(err) {
        ajaxRequest = null;
        if ("object" === typeof err.responseJSON) {
            if (err.responseJSON.type === 'redirect') {
                return page(err.responseJSON.redirect);
            }
        }

        console.log(err);
        self.unload();
        if (err.statusText === 'abort') {

        } else {
            page('/');
        }
    }

    this.unload = function() {
        if (ajaxRequest !== null) {
            ajaxRequest.abort();
            ajaxRequest = null;
        }
        if (options && options.events && typeof options.events.unload === 'function')
            options.events.unload();
    }
};
