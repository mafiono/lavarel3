Fixtures = function (_options) {
    var options = {mode: "competition"};

    var matchResultIds = [
        2, //Football
        322, //Tennis
        306, //Basketball
        7469, //Futsal
        8133, //Rugby League
        15, //Rugby Union
        6662, //Handball
        6734, //Volleyball
        35, // Golf
        84, //Motor Sports
        202 //Ice Hockey
    ];

    var sportsPriority = {
        10: 1, //Footbal
        4: 2,  //Basketball
        24: 3, //Tennis
        491393: 4, //Futsal
        73743: 8, //Rugby League
        73744: 7, //Rugby Union
        99614: 6, //Handball
        91189: 5, //Volleyball
        15: 10, //Ice Hockey
        16:11, //Motor Sports
        22: 9 //Golf
    };

    var collapsed = [];

    init(_options);

    function init(_options)
    {
        Helpers.updateOptions(_options, options);

        window.setInterval(refresh, 30000);
    }

    this.make = function (_options, refresh)
    {
        Helpers.updateOptions(_options, options);

        if (!options.container)
            return;

        options.container.html("");

        if (!refresh) {
            options.container.removeClass("hidden");
        }

        make();
    };

    function make()
    {
        MiddleAlert.hide();

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
            "&ignoreTradingSelections" +
            take()
        ).done(render);
    }

    function render(data)
    {
        var container = options.container;

        if (!data.fixtures.length) {
            container.html("");

            if (options.until
                && (options.mode === "competition" || options.mode === "highlights" || options.mode === "highgames")
                && page.current !== "/")
            {
                MiddleAlert.make({
                    msg: "<p>De momento não existem eventos disponíveis no intervalo selecionado.</p>" +
                        "<p>Por favor selecione um intervalo diferente.</p>",
                    prematchEmpty: true,
                    liveEmpty: true
                })
            }

            if (options.mode === "favorites" || options.mode === "search") {

                if (options.live)
                    MiddleAlert.liveEmpty().render();
                else
                    MiddleAlert.prematchEmpty().render();
            }

            return;
        }

        fixturesData(data);

        groupFixturesBySport(data);

        container.html(Template.apply("fixtures", data));

        container.find("[data-type='fixture']").click(fixtureClick);

        container.find("[data-type='odds']").click(selectionClick);

        container.find("[data-type='favorite']").click(favoriteClick);

        container.find("[data-type='statistics']").click(statisticsClick);

        if (options.take && (data.fixtures.length < options.take))
            options.container.find(".fixtures-more").remove();

        container.find("th.marketCount").click(collapseClick);

        container.find(".fixtures-more").click(moreClick);

        collapsed.forEach((sportId) => collapse(sportId));

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

    function groupFixturesBySport(data) {
        let sports = [];

        data.fixtures.forEach((fixture) => {
            if (!sports.includes(fixture.sport_id))
                sports.push(fixture.sport_id);
        });

        data.sports = [];

        sports.forEach(
            (sport) => {
                data.sports.push({
                    collapse: false,
                    sportId: sport,
                    sportOrder: sport in sportsPriority ? sportsPriority[sport] : 999,
                    fixtures: data.fixtures.filter((fixture) => fixture.sport_id === sport)
                });
            }
        );

        data.sports.sort((a,b) => a.sportOrder - b.sportOrder);
    }

    function mode()
    {
        switch (options.mode) {
            case "sport":
                return "sport=" + options.sportId;
            case "highgames":
                return "sport=" + options.sportId +
                    (options.highGameIds.length > 0 && "&ids=" + options.highGameIds.join(","));
            case "highlights":
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
        if (options.mode === "competition" || options.mode === "highlights" || options.mode === "sport" || options.mode === "highgames")
            return "&until=" + (options.until ? options.until : encodeURIComponent(moment.utc().add(1, "years").format()));

        return "";
    }

    function take()
    {
        return options.take ? "&take=" + options.take : "&take=60";
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
            sportId: $(this).data("sport-id"),
            amount: 0
        });
    }

    function favoriteClick(e)
    {
        e.stopPropagation();

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
        let sportId = $(this).parents("table").data("sport");

        if (collapsed.includes(sportId)) {
            collapsed.splice(collapsed.indexOf(sportId), 1);

            make();
        } else {
            collapsed.push(sportId);

            collapse(sportId);
        }
    }

    function collapse(sportId)
    {
        let table = options.container.find("table[data-sport='" + sportId + "']");

        table.find("tr:not(:first-child)").hide();
        table.find("th.marketCount .cp-caret-down").removeClass("cp-caret-down").addClass("cp-plus");

        options.container.find(".fixtures-more").remove();
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
        if (options.container && options.container.is(":visible"))
            make();
    }
};

SportsFixtures = new Fixtures();

LiveFixtures = new Fixtures();
HighFixtures = new Fixtures();
TennisFixtures = new Fixtures();

LiveBasketballFixtures = new Fixtures();
LiveTenisFixtures = new Fixtures();

LiveFavoritesFixtures = new Fixtures();
FavoritesFixtures = new Fixtures();

LiveSearchFixtures = new Fixtures();
SearchFixtures = new Fixtures();
