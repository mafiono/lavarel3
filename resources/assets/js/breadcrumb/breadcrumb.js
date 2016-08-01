var Breadcrumb = new (function ()
{
    var options = {};

    var container = $("#breadcrumb-container");

    this.make = function (_options)
    {
        Helpers.updateOptions(_options, options);

        container.removeClass("hidden");

        make();
    };

    function make(_options)
    {
        if ((options.mode == "markets" || options.mode == "competition") || (options.mode == "highlights" && !options.competition)) {
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
        return (options.mode == "markets") ? "ids=" + options.fixtureId : "competition=" + options.competitionId;
    }
});
