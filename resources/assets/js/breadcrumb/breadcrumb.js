Breadcrumb = new (function ()
{
    var options = {};

    var cache = {};

    var container = null;

    function init() {
        container = $("#breadcrumb-container");
    }

    this.init = function () {
        init();
    };

    this.make = function (_options)
    {
        Helpers.updateOptions(_options, options);

        container.removeClass("hidden");

        make();
    };

    function make()
    {
        if (options.mode === "title") {
            render();

            return;
        }


        if ((options.mode == "markets" || options.mode == "competition") || (options.mode == "highlights" && !options.competition)) {
            if (isCached()) {
                fetchFromCache();

                render();
            } else
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
            live() +
            "&with=sport,competition.region",
            "&take=1"
        ).done(render);
    }

    function render(data)
    {

        if (data && data.fixtures && data.fixtures.length) {
            var fixture = data.fixtures[0];

            options.competition = fixture.competition.name;
            options.competitionId = fixture.competition_id;
            options.region = fixture.competition.region.name;
            options.sport = fixture.sport.name;

            addToCache();
        }

        container.html(Template.apply('breadcrumb', options));

        if (options.mode == "markets" || options.mode == "highlights")
            PopularSportsMenu.selectCompetition(options.competitionId);
    }

    function mode()
    {
        return (options.mode == "markets") ? "ids=" + options.fixtureId : "competition=" + options.competitionId;
    }

    function live()
    {
        return options.live ? "&live" : "";
    }

    function isCached()
    {
        return !!cache[getCacheKey()];
    }

    function fetchFromCache()
    {
        var key = getCacheKey();
        options.competition = cache[key].competition;
        options.competitionId = cache[key].competitionId;
        options.region = cache[key].region;
        options.sport = cache[key].sport;
    }

    function addToCache()
    {
        cache[getCacheKey()] = {
            competition: options.competition,
            competitionId: options.competitionId,
            region: options.region,
            sport: options.sport
        };
    }

    function getCacheKey()
    {
        return (options.mode == "markets") ? "fixture:" + options.fixtureId : "competition:" + options.competitionId;
    }

});
