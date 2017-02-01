Handlebars.registerPartial('register', require('./register.html'));

Register = new function () {
    var options = {
        step: 'step1',
        events: {
            load: function () {},
            unload: function () {}
        }
    };
    var menus = {
        step1: { events: require('./steps/step1') },
        step2: { events: require('./steps/step2') },
        step3: { events: require('./steps/step3') }
    };
    var ajaxRequest = null;

    init();

    function init()
    {
        // init stuff
    }

    this.make = function(ctx, next)
    {
        this.unload();

        Helpers.updateOptions(ctx.terms, options);

        make();
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

        var container = $("#register-container");

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
