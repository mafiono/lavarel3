MiddleAlert = new (function () {
    var options = {
        msg: "",
        liveEmpty: false,
        prematchEmpty: false
    };

    var self = this;

    this.make = function(_options)
    {
        Helpers.updateOptions(_options, options);

        render();
    };

    this.render = function ()
    {
        render();
    };

    this.liveEmpty = function()
    {
        return liveEmpty()
    };

    this.prematchEmpty = function ()
    {
        return prematchEmpty();
    };

    this.hide = function() {
        hide();
    };

    function hide() {
        $("#middleAlert-container").addClass("hidden");
    }

    function render()
    {
        if (options.liveEmpty && options.prematchEmpty) {
            $("#middleAlert-container")
                .removeClass("hidden")
                .html(Template.apply("middleAlert", options));
        }
    }

    function liveEmpty()
    {
        options.liveEmpty = true;

        return self;
    }

    function prematchEmpty()
    {
        options.prematchEmpty = true;

        return self;
    }

    this.options = function() {
        return options;
    }
});
