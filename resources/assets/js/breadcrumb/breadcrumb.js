var Breadcrumb = new (function ()
{
    var options = {};

    this.make = function (_options)
    {
        make(_options);

        return this;
    };

    function make(_options)
    {
        update(_options);

        render();
    }

    function update(_options)
    {
        for (var i in _options)
            options[i] = _options[i];
    }

    function render()
    {
        $("#breadcrumb-container").html(Template.apply('breadcrumb', options));
    }

});
