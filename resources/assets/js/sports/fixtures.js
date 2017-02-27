Fixtures = function (_options)
{
    var options = {mode: "competition"};

    var matchResultIds = [
        2, //Football
        322, //Tennis
        306, //Basketball
        7469, //Futsal
        8133, //Rugby League
        15, //Rugby Union
        6662, //Handball
        6734 //Volleyball
    ];

    init(_options);

    function init(_options)
    {
        Helpers.updateOptions(_options, options);

        window.setInterval(refresh, 30000);
    }

    this.make = function (_options)
    {
        Helpers.updateOptions(_options, options);

        if (!options.container)
            return;

        options.container.html("");

        options.container.removeClass("hidden");

        make();
    };

    function make()
    {
        fetch();
    }

    function fetch()
    {
        if (options.mode === "favorites" && Favorites.games().length === 0) {
            options.container.html("");
            return;
        }

        $.get(ODDS_SERVER + "fixtures?" +
            mode() +
            marketType() +
            "&orderBy=start_time_utc,asc" +
            live() +
            until() +
            "&countMarkets" +
            take()
        ).done(render);
    }

    function render(data)
    {
        var container = options.container;

        if (!data.fixtures.length) {
            container.html("");

            if (options.mode === "favorites" || options.mode === "search") {

                if (options.live)
                    MiddleAlert.liveEmpty().render();
                else
                    MiddleAlert.prematchEmpty().render();
            }

            return;
        }

        fixturesData(data);

        container.html(Template.apply("fixtures", data));

        container.find("[data-type='fixture']").click(fixtureClick);

        container.find("[data-type='odds']").click(selectionClick);

        container.find("[data-type='favorite']").click(favoriteClick);

        container.find("[data-type='statistics']").click(statisticsClick);


        if (options.take && (data.fixtures.length < options.take))
            options.container.find(".fixtures-more").remove();

        container.find("th.marketCount").click(collapseClick);

        container.find(".fixtures-more").click(moreClick);

        Betslip.applySelected(container);

        Favorites.apply();
    }

    function fixturesData(data)
    {
        data.options = options;

        var fixtures = data.fixtures;

        for (var i in fixtures) {
            var fixture = fixtures[i];

            fixture.date = moment.utc(fixture['start_time_utc']).local().format("DD MMM");
            fixture.time = moment.utc(fixture['start_time_utc']).local().format("HH:mm");
        }
    }

    function mode()
    {
        switch (options.mode) {
            case "sport":
                return "sport=" + options.sportId;
            case "highlights":
                return "ids=" + options.highlightsIds;
            case "competition":
                return "competition=" + options.competitionId;
            case "favorites":
                return favorites();
            case "search":
                return "query=" + options.query;
        }
    }

    function marketType()
    {
        return "&marketType=" + matchResultIds.join(",");
    }

    function live()
    {
        return options.live ? "&live" : "";
    }

    function until()
    {
        if (options.mode == "competition" || options.mode == "highlights")
            return "&until=" + (options.until ? options.until : encodeURIComponent(moment.utc().add(1, "years").format()));

        return "";
    }

    function take()
    {
        return options.take ? "&take=" + options.take : "&take=20";
    }

    function fixtureClick()
    {
        if (options.live) {
            page('/direto/mercados/' + $(this).data("game-id"));

            return;
        }

        page('/desportos/mercados/' + $(this).data("game-id"));
    }

    function selectionClick()
    {
        Betslip.toggle.call(this, {
            id: $(this).data("event-id"),
            name: $(this).data("event-name"),
            odds: $(this).data("event-price"),
            marketId: $(this).data("market-id"),
            marketName: $(this).data("market-name"),
            gameId: $(this).data("game-id"),
            gameName: $(this).data("game-name"),
            gameDate: $(this).data("game-date"),
            amount: 0
        });
    }

    function favoriteClick()
    {
        Favorites.toggle.call(this);
    }

    function favorites()
    {
        var favorites = [];

        var games = Favorites.games();

        for (var i in games)
            favorites.push(games[i].id);

        return "ids=" + favorites.join(',');
    }

    function collapseClick()
    {
        if (options.collapsed) {
            make();
            options.collapsed = false;

            return;
        }

        collapse();
        options.collapsed = true;
    }

    function collapse()
    {
        var container = options.container;

        container.find("tr:not(:first-child)").hide();

        container.find("th.marketCount .fa-caret-down").removeClass("fa-caret-down").addClass("fa-plus");
    }

    function moreClick()
    {
        options.container.find(".fixtures-expand").remove();
        delete options.expand;
        delete options.take;
        fetch();
    }

    function statisticsClick()
    {
        page((options.live ? '/direto' : '/desportos') + '/estatistica/' + $(this).data("game-id"));
    }

    function refresh()
    {
        if (options.container && options.container.is(":visible") && !options.collapsed)
            make();
    }
};

SportsFixtures = new Fixtures();

LiveFixtures = new Fixtures();

TennisFixtures = new Fixtures();

LiveBasketballFixtures = new Fixtures();
LiveTenisFixtures = new Fixtures();

LiveFavoritesFixtures = new Fixtures();
FavoritesFixtures = new Fixtures();

LiveSearchFixtures = new Fixtures();
SearchFixtures = new Fixtures();
