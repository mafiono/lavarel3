var LiveMenu = new (function () {

    init();

    function init()
    {
        // make();
    }

    function make()
    {
        fetchSports();
    }

    this.make = function ()
    {
        make();
    };

    function fetchSports ()
    {
        $.get("http://genius.ibetup.eu/sports?live")
            .done(renderSports);
    }


    function renderSports (data)
    {
        if (!data.sports.length)
            return;

        $("#sportsMenu-live-container").html(Template.apply("liveMenu_sports", data));

        $("#sportsMenu-live-container").find(".level1 > .sport").click(sportClick);
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
        container.find(".level2 > .region")
            .click(function () {regionClick.call(this, sportId)});
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

        $(container).find(".fixture").click(fixtureClick);
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

        // competitionId = $(this).parent().data('competition-id');

        // Markets.makeLive(marketsOptions.call(this));
    }
    //
    // function marketsOptions()
    // {
    //     var competition = $(this).parent();
    //     var region = competition.parent().prev();
    //     var sport = region.parent().parent().prev();
    //
    //     return {
    //         competition : competition.data("competition-name"),
    //         competitionId : competition.data("competition-id"),
    //         region : region.data("region-name"),
    //         sport : sport.data("sport-name")
    //     };
    // }

});
