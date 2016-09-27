Register = new function () {
    var options = {};

    init();

    function init()
    {
        // init stuff
    }

    this.make = function(_options)
    {
        Helpers.updateOptions(_options, options)

        options.container.removeClass("hidden");

        make();
    };

    function make()
    {
        options.container.attr("src", "/registar/step1");
    }
};
