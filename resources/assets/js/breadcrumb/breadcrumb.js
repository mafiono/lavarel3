var Breadcrumb = new (function ()
{
    var options = {};

    var container = $("#breadcrumb-container");

    this.make = function (_options)
    {
        make(_options);

        return this;
    };

    function make(_options)
    {
        update(_options);

        if ((options.operation == "markets")
            || ((options.operation == "competition" || options.operation == "highlights") && !options.competition)) {
            fetch();

            return;
        }

        render();
    }

    function fetch()
    {
        container.html("<div class='breadcrumb'>&nbsp;</div>");
        $.get(ODDS_SERVER +
            "fixtures?" + mode() +
            "&with=sport,competition.region",
            "&take=1"
        ).done(render);
    }

    function update(_options)
    {
        for (var i in _options)
            options[i] = _options[i];
    }

    function render(data)
    {
        if (data && data.fixtures && data.fixtures.length) {
            var fixture = data.fixtures[0];

            options.competition = fixture.competition.name;
            options.region = fixture.competition.region.name;
            options.sport = fixture.sport.name;
        }

        container.html(Template.apply('breadcrumb', options));
    }

    function mode()
    {
        return (options.operation == "markets") ? "ids=" + options.fixtureId : "competition=" + options.competitionId;
    }
});
