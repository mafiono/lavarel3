var Info = new (function () {
    var options = {};

    init();

    function init()
    {

    }

    this.make = function (_options)
    {
        make(_options);
    };

    function make(_options) {
        update(_options);

        fetch();
    }

    function fetch()
    {


    }

    function render()
    {

    }

    function update(_options)
    {
        for (var i in _options)
            options[i] = _options[i];
    }
});
