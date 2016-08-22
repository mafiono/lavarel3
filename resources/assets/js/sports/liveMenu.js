LiveMenu = new (function () {

    var options = {};

    var loaded = false;

    var selectedFixture;

    init();

    function init()
    {
        // make();
    }

    function make()
    {
        fetchSports();
    }

    this.make = function (_options)
    {
        update(_options);

        make();
    };

    this.loaded = function()
    {
        return loaded;
    };

    this.selected = function(fixtureId)
    {
        if (fixtureId) {
            var container = $("#sportsMenu-live-container");

            container.find("td[data-game-id]").removeClass("selected");
            container.find("td[data-game-id=" + fixtureId + "]").addClass("selected");
        }

        return selectedFixture;
    };

    function fetchSports ()
    {
        $.get(ODDS_SERVER + "sports?live")
            .done(renderSports);
    }


    function renderSports (data)
    {
        if (!data.sports.length)
            return;

        var container = $("#sportsMenu-live-container");

        container.html(Template.apply("liveMenu_sports", data));

        var sports = container.find(".level1 > .sport");

        sports.click(sportClick);

        if (!loaded)
            sports.first().click();

    }

    function sportClick ()
    {
        var containerEmpty = ($(this).next().html() === "");

        var sportId = $(this).data("sport-id");

        if (containerEmpty)
            fetchRegions(sportId);

        toggleSport.call(this);
    }

    function toggleSport ()
    {
        $(this).toggleClass("selected");
        $(this).parent().find(".level2").toggleClass("hidden");

        var expand = $(this).children(".expand");

        expand.toggleClass("fa-plus");
        expand.toggleClass("fa-caret-down");
        expand.toggleClass("collapse");
    }

    function fetchRegions (sportId)
    {
        $.get("http://genius.ibetup.eu/regions?sport=" + sportId + "&live&fixturesCount")
            .done(function (data) {renderRegions(data, sportId)})
    }

    function renderRegions (data, sportId) {
        var container = $("#sportsMenu-live-container").find("div[data-sport-id=" + sportId + "]").next();

        container.html(Template.apply("liveMenu_regions", data));

        regionsClick(container, sportId);
    }

    function regionsClick(container, sportId)
    {
        var regions = container.find(".level2 > .region");

        regions.click(function () {regionClick.call(this, sportId)});

        if (!loaded)
            regions.first().click();
    }

    function regionClick(sportId)
    {
        var containerEmpty = $(this).next().html() === "";

        if (containerEmpty && $(this).hasClass("selected"))
            return;

        var regionId = $(this).data("region-id");

        if (containerEmpty)
            fetchFixtures(sportId, regionId);

        toggleRegion.call(this);
    }

    function toggleRegion() {
        $(this).parent().find(".level3").toggleClass("hidden");
        $(this).find(".collapse").toggleClass("hidden");
        $(this).find(".count").toggleClass("hidden");
    }

    function fetchFixtures(sportId, regionId)
    {
        $.get("http://genius.ibetup.eu/fixtures?sport=" + sportId + "&region=" + regionId + "&live")
            .done(function (data) {renderFixtures(data, sportId, regionId)});
    }

    function renderFixtures(data, sportId, regionId)
    {
        var container = $("#sportsMenu-live-container").find("div[data-sport-id=" + sportId + "]").next()
            .find("div[data-region-id=" + regionId + "]").next();

        container.html(Template.apply('liveMenu_fixtures', data));

        Favorites.apply();

        var fixtures = $(container).find(".fixture");

        fixtures.click(fixtureClick);

        if (!loaded) {
            loaded = true;
            if (options.markets)
                fixtures.first().click();
        }

        $(container).find("button[data-type=favorite]").click(favoriteClick);
    }

    function favoriteClick()
    {
        Favorites.toggle.call(this);
    }

    function fixtureClick()
    {
        $("#sportsMenu-live-container").find("table").removeClass("selected");
        $("#sportsMenu-live-container").find("table .fixture").removeClass("selected");
        $(this).parent().parent().parent().addClass("selected");
        $(this).addClass("selected");
        $(".i3").addClass("hidden");

        selectedFixture = $(this).data("game-id");

        page('/direto/mercados/' + selectedFixture);
    }

    function update(_options)
    {
        for (var i in _options)
            options[i] = _options[i];
    }

});
