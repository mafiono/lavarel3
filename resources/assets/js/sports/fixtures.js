function Fixtures(_options)
{
    var options = {mode: "competition"};

    var market_types = "2,306,322,259,105,122,7202,25,60,62,104,169,6832,7591";

    init(_options);

    function init(_options)
    {
        Helpers.updateOptions(_options, options);

        setInterval(refresh, 30000);
    }

    function make(_options)
    {
        Helpers.updateOptions(_options, options);

        if (!options.container)
            return;

        options.container.removeClass("hidden");

        fetch();
    }

    function fetch()
    {
        $.get(ODDS_SERVER + "fixtures?" +
            mode() +
            "&marketType=2,306,322&orderBy=start_time_utc,asc" +
            live() +
            until() +
            "&marketsCount=" + market_types +
            take()
        ).done(render);
    }

    function render(data)
    {

        var container = options.container;

        if (!data.fixtures.length) {
            container.html("");

            return;
        }

        fixturesData(data);

        container.html(Template.apply("fixtures", data));

        container.find("[data-type='fixture']").click(fixtureClick);

        container.find("[data-type='odds']").click(selectionClick);

        container.find("[data-type='favorite']").click(favoriteClick);

        container.find("[data-type='statistics']").click(statisticsClick);


        if (options.take && (data.fixtures.length < options.take))
            options.container.find(".fixtures-expand").remove();

        options.container.find(".fixtures-expand").click(expandClick);

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
            case "competition":
                return "competition=" + options.competitionId;
            case "favorites":
                return favorites();
            case "search":
                return "query=" + options.query;
        }
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

    this.make = function (_options)
    {
        make(_options);
    };

    function expandClick()
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

LiveFavoritesFixtures = new Fixtures();
FavoritesFixtures = new Fixtures();

LiveSearchFixtures = new Fixtures();
SearchFixtures = new Fixtures();
