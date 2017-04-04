Markets = new (function ()
{
    var options = {
        visited: {}
    };

    var outcomes = {};

    init();

    function init()
    {
        window.setInterval(refresh, 9000);
    }

    this.make = function(_options)
    {
        Helpers.updateOptions(_options, options);

        options.container.html("");

        options.container.removeClass("hidden");

        make(_options);
    };

    function make()
    {
        fetch();
    }

    function fetch()
    {
        $.get(ODDS_SERVER + "fixtures?ids=" + options.fixtureId
            + "&withOpenMarkets"
            + "&with=competition"
            + live()
        ).done(render);
    }

    function live()
    {
        return options.live ? "&live" : "";
    }

    function render(data)
    {
        if (data.fixtures.length == 0) {
            window.setTimeout(fetch, 2000);

            if (!$(".markets_overlay").length) {
                options.container.prepend(Template.apply("unavailable_markets"));

                $("<div class='markets_overlay'></div>")
                .appendTo(options.container.css("position", "relative"));
            }

            return;
        } else {
            options.container.parent().find(".markets-unavailable").remove();
        }

        if (options.live) {
            let fixture = data.fixtures[0];
            LiveSportsMenu.selectRegion(fixture.sport_id, fixture.competition.region_id);
        }

        headerData(data);

        fixturesData(data);

        data.collapsed = options.collapsed;

        var container = options.container;

        container.html(Template.apply('markets', data));

        container.find("[data-type='odds']").click(selectionClick);

        Betslip.applySelected(container);

        $("#markets-close").click(closeClick);

        $("#markets-statistics").click(function () {
            page(options.live?'/direto':'/desportos' + '/estatistica/' + data.fixtures[0].id);
        });

        container.find("div.title i, span").click(collapseClick);

        if (!options.visited[options.fixtureId]) {
            options.visited[options.fixtureId] = true;

            collapseAfter(5);
        }
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
        fixture.marketsSet = {};
        var marketsSet = fixture.marketsSet;

        for (var i in markets) {
            var market = markets[i];

            MarketsVerifier.checkMarket(market.market_type_id);

            if (!marketsSet[market.market_type_id])
                marketsSet[market.market_type_id] = [];

            marketsSet[market.market_type_id].push(market);
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
        var bets = Betslip.bets;

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
            sportId: $(this).data("sport-id"),
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

    function collapseClick()
    {
        let header = $(this).parent();

        let container = header.next();

        let marketId = header.data("market-id");

        let icon = header.find("i");

        if (!options.collapsed)
            options.collapsed = {};

        if (options.collapsed[marketId]){
            container.removeClass("hidden");
            icon.removeClass("cp-plus");
            icon.addClass("cp-caret-down");

            delete options.collapsed[marketId];
        } else {
            container.addClass("hidden");
            icon.removeClass("cp-caret-down");
            icon.addClass("cp-plus");

            options.collapsed[marketId] = true;
        }
    }

    function collapseAfter(start)
    {
        options.container
            .find(".markets div.title i")
            .slice(start)
            .click();
    }

    function refresh()
    {
        if (options.container && options.container.is(":visible"))
            make();
    }

})();
