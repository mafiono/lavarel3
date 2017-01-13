Handlebars.registerPartial('perfil', require('./perfil.html'));

Perfil = new function () {
    var options = {};

    init();

    function init()
    {
        // init stuff
    }

    this.make = function(_options)
    {
        Helpers.updateOptions(_options, options);

        make();
    };

    function make()
    {
        var content = Template.apply("perfil");

        $("#perfil-container").html(content);
    }
};
