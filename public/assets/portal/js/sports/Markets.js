var Markets = new (function ()
{
    var options = {
        sport: "Futebol",
        region: "Europa",
        competition: "UEFA European Football Championship",
        competitionId: 51,
        until: encodeURIComponent(moment.utc().add(1, "years").format()),
        operation: "Competition"
    };

    var outcomes = {};

    var fixtureId;

    var market_types = "2,259,105,122,7202,25,60,62,104,169,6832,7591";

    init();

    function init()
    {
        new Spinner().spin(document.getElementById("marketsSpinner"));

        make(fromCompetition(options));
    }

    function make(from)
    {
        renderHeader();

        fetchFixtures(from);
    }

    this.make = function(_options)
    {
        if (_options)
            updateOptions(_options);

        options["operation"] = "Competition";

        make(fromCompetition(options));
    };

    this.makeUntil = function (until)
    {
        options.until = until ? until : encodeURIComponent(moment.utc().add(1, "years").format());

        make(fromCompetition(options));
    };

    this.makeFavorites = function ()
    {
        options["operation"] = "Favoritos";

        make(fromFavorites());
    };

    this.makeQuery = function (query)
    {
        options["operation"] = "Pesquisa";
        options["query"] = query;

        make(fromQuery(query));
    };

    this.makeHighlight = function(_options)
    {
        options["operation"] = "Destaques";

        updateOptions(_options);

        make(fromCompetition(options));
    };

    function renderHeader()
    {
        $("#markets-header-container").html(Template.apply('markets_navigation', options));
    }

    function fromCompetition()
    {
        return "competition=" + options.competitionId;
    }

    function fromFavorites()
    {
        var favorites = [];

        var games = Favorites.games();

        for (var i in games)
            favorites.push(games[i].id);

        return "ids=" + favorites.join(',');
    }

    function fromQuery(query)
    {
        return "query=" + query;
    }

    function fetchFixtures(from)
    {
        $.get("/odds/fixtures?" + from +
            "&marketType=2&orderBy=start_time_utc,asc" +
            "&until=" + options.until +
            "&marketsCount=" + market_types
        ).done(renderFixtures);
    }

    function renderFixtures(data)
    {
        fixturesData(data);

        var marketsContent = $("#markets-content");

        marketsContent.html(Template.apply("fixtures", data));

        applySelected(marketsContent);

        marketsContent.find("[data-type='fixture']").click(fixtureClick);

        marketsContent.find("[data-type='odds']").click(selectionClick);

        marketsContent.find("[data-type='favorite']").click(favoriteClick);

        Favorites.apply();

        showFixtures();
    }

    function fixturesData(data)
    {
        var fixtures = data.fixtures;

        for (var i in fixtures)
            fixtureData(fixtures[i]);

        data.outcomes = outcomes;
    }

    function fixtureData(fixture)
    {
        fixture.date = moment.utc(fixture['start_time_utc']).local().format("DD MMM");
        fixture.time = moment.utc(fixture['start_time_utc']).local().format("HH:mm");

        outcomesFomFixture(fixture);

        fixtureMarkets(fixture);
    }

    function fixtureMarkets(fixture) {
        var markets = fixture.markets;

        for (var i in markets) {
            var market = markets[i];

            if (!fixture[market.market_type_id])
                fixture[market.market_type_id] = [];

            fixture[market.market_type_id].push(market);
        }
    }

    function outcomesFomFixture(fixture)
    {
        var markets = fixture.markets;

        for (var i in markets)
            outcomesFromMarket(markets[i])

        return outcomes;
    }

    function outcomesFromMarket(market)
    {
        var selections = market.selections;

        for (var i in selections) {
            var outcome = selections[i].outcome;

            if (outcome)
                outcomes[outcome.id] = outcome.name;
        }
    }

    function applySelected(container)
    {
        var bets = Betslip.bets();

        for (var i in bets)
            container.find("[data-event-id='" + bets[i].id + "']").addClass("selected");
    }

    function fixtureClick()
    {
        var id = $(this).data("game-id");

        if (fixtureId == id) {
            hideFixtures();

            return;
        }

        fixtureId = id;

        $.get("/odds/fixtures?ids=" + fixtureId +
            "&withMarketTypes=2,259,105,122,7202,25,60,62,104,169,6832,7591"
        ).done(renderFixture);
    }

    function renderFixture(data)
    {
        headerData(data);

        fixturesData(data, true);

        var container = $("#markets-fixtureMarketsContainer");

        container.html(Template.apply('fixture_markets', data));

        container.find("[data-type='odds']").click(selectionClick);

        applySelected(container);

        $("#markets-hide").click(showFixtures);

        container.find("#markets-more").click(moreMarketsClick);

        hideFixtures();
    }

    function showFixtures()
    {
        $("#markets-fixturesContainer").removeClass("hidden");
        $("#markets-fixtureMarketsContainer").addClass("hidden");
    }

    function hideFixtures()
    {
        $("#markets-fixturesContainer").addClass("hidden");
        $("#markets-fixtureMarketsContainer").removeClass("hidden");
    }

    function moreMarketsClick() {
        $(this).addClass("hidden");

        $("#markets-others").removeClass("hidden");
    }

    function headerData(data)
    {
        data.sport = options.sport;
        data.region = options.region;
        data.competition = options.competition;
        data.now = moment().format("DD MMM HH:mm");
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

    function updateOptions(_options) {
        for (var i in _options)
            options[i] = _options[i];
    }

})();
