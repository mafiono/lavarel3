var Markets = new (function ()
{
    var options = {};

    var outcomes = {};

    var market_types = "2,306,322,259,105,122,7202,25,60,62,104,169,6832,7591";


    init();

    function init()
    {
        setInterval(refresh, 30000);
    }

    this.make = function(_options)
    {
        make(_options);
    };

    function make(_options)
    {
        Helpers.updateOptions(_options, options);

        options.container.removeClass("hidden");

        fetch();
    }

    function fetch()
    {
        $.get(ODDS_SERVER + "fixtures?ids=" + options.fixtureId +
            "&withMarketTypes=" + market_types
            + live()
        ).done(render);
    }


    function live()
    {
        return options.live ? "&live" : "";
    }

    function render(data)
    {
        headerData(data);

        fixturesData(data, true);

        var container = options.container;

        container.html(Template.apply('markets', data));

        container.find("[data-type='odds']").click(selectionClick);

        Betslip.applySelected(container);

        $("#markets-close").click(closeClick);

        $("#markets-statistics").click(function () {page('/estatistica/' + data.fixtures[0].id);});

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
            outcomesFromMarket(markets[i]);

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

    function headerData(data)
    {
        data.live = options.live;
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

    function closeClick ()
    {
        if (history.length) {
            history.back();

            return;
        }

        page('/');
    }

    function refresh()
    {
        if (options.container && options.container.is(":visible"))
            make();
    }


})();
