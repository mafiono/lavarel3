var Statistics = new (function() {
    var options = {};

    this.make = function (_options) {
        update(_options);

        make();
    };

    function update(_options)
    {
        for (var i in _options)
            options[i] = _options[i];
    }

    function make (path)
    {
        var container = $("#statistics-container");

        container.html("");

        fetch();
    }

    function fetch()
    {
        $.get(ODDS_SERVER + "fixtures?ids=" +
            options.fixtureId +
            (options.live ? "&live" : "")
        ).done(render);

    }

    function render(data)
    {
        if (!data.fixtures.length)
            return;

        options.name = data.fixtures[0].name;

        $("#statistics-container").html(Template.apply("statistics", options));

        $("#statistics-close").click(closeClick);

        $("#statistics-open").click(openClick);
    }

    function closeClick()
    {
        page(options.closePath);
    }

    function openClick()
    {
        var width = 1200;
        var height = 800;

        window.open('http://www.score24.com/statistics3/index.jsp?partner=score24&eventId=6117' ,
            'newwindow',
            'width=' + width + ', height=' + height + ', top=' + ((window.outerHeight - height) / 2) + ', left=' + ((window.outerWidth - width) / 2)
        );
    }

})();
