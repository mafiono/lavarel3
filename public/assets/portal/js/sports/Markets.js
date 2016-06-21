var Markets = new (function ()
{
    var options;

    var outcomes = {};

    var fixtureId;

    var templates = {
        "2": "match_result",
        "122": "draw_no_bet",
        "7202": "double_chance",
        "25": "highest_scoring_half",
        "60": "win_both_halves",
        "62": "clean_sheet",
        "104": "win_either_half",
        "105": "handicap_with_tie",
        "169": "to_win_to_nil",
        "6832": "first_half_result",
        "7591": "second_half_result"
    };

    init();

    function init()
    {
        new Spinner().spin(document.getElementById("marketsSpinner"));

        make({sport: "Futebol", region: "Europa", competition: "UEFA European Football Championship", competitionId: 51});
    }

    function make(_options)
    {
        options = _options ? _options : options;

        makeUntil(options.until);
    }

    this.make = function (_options)
    {
        make(_options);
    };

    function makeUntil(until)
    {
        options.until = until ? until : encodeURIComponent(moment.utc().add(1, "years").format());

        renderHeader();

        fetchFixtures();
    }

    this.makeUntil = function (until)
    {
        makeUntil(until);
    };


    function renderHeader()
    {
        $("#markets-header-container").html(Template.apply('markets_header', options));
    }

    function fetchFixtures()
    {
        $.get("/odds/fixtures?competition=" + options.competitionId +
            "&marketType=2&orderBy=start_time_utc,asc" +
            "&until=" + options.until
        ).done(renderFixtures);
    }

    function renderFixtures(data)
    {
        fixturesData(data);

        var marketsContent = $("#markets-content");

        marketsContent.html(Template.apply(templates["2"], data));

        applySelected(marketsContent);

        marketsContent.find("[data-type='game']").click(fixtureClick);

        marketsContent.find("[data-type='odds']").click(selectionClick);

        showFixturesMarket();
    }

    function fixturesData(data)
    {
        for (var i in data['fixtures']) {
            var fixture = data['fixtures'][i];

            fixture.date = moment.utc(fixture['start_time_utc']).local().format("DD MMM");
            fixture.time = moment.utc(fixture['start_time_utc']).local().format("HH:mm");
            fixture.parity = i%2?"odd":"even";

            outcomesFomFixture(fixture);
        }

        data.outcomes = outcomes;
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
            hideFixturesMarket();

            return;
        }

        fixtureId = id;

        $.get("/odds/fixtures?ids=" + fixtureId +
            "&withMarketTypes=2,122,7202,25,60,62,104,169,6832,7591"
        ).done(renderFixture);
    }

    function renderFixture(data)
    {
        headerData(data);

        fixturesData(data);

        var gameContainer = $("#game-container");

        gameContainer.html(Template.apply('fixture_markets', data));

        gameContainer.find("[data-type='odds']").click(selectionClick);

        applySelected(gameContainer);

        $("#markets-game-hide").click(showFixturesMarket);

        hideFixturesMarket();
    }

    function showFixturesMarket()
    {
        $("#markets-container").removeClass("hidden");
        $("#game-container").addClass("hidden");
    }

    function hideFixturesMarket()
    {
        $("#markets-container").addClass("hidden");
        $("#game-container").removeClass("hidden");
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

})();
