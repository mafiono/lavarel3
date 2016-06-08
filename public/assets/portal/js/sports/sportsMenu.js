var SportsMenu = new (function()
{
    var markets = null;

    this.init = function(marketsObj)
    {
        markets = marketsObj;
    };

    this.make = function()
    {
        fetchSports();
    };

    function fetchSports ()
    {
        $.get("/odds/sports?ids=10,24")
            .done(renderSports);
    }

    function renderSports (data)
    {
        $("#sports-menu-container").html(Template.apply("sports_menu", data));

        sportsClick();
    }

    function sportsClick ()
    {
        $(".menu1-option").click(sportClick);
    }

    function sportClick ()
    {
        var containerEmpty = ($(this).next().html() === "");

        if (containerEmpty && $(this).hasClass("selected"))
            return;

        var sportId = $(this).data("sport-id");

        if (containerEmpty)
            fetchRegions(sportId);

        toggleSport.call(this);
    }

    function toggleSport ()
    {
        $(this).toggleClass("selected");
        $(this).parent().find(".level2").toggleClass("hidden");
        $(this).find(".i1").toggleClass("hidden");
        $(this).find(".n1").toggleClass("menu-option-selected");
    }

    function fetchRegions (sportId)
    {
        $.get("/odds/regions?sport=" + sportId)
            .done(function (data) {renderRegions(data, sportId)})
    }

    function renderRegions (data, sportId) {
        var container = $("#sports-menu-container").find("div[data-sport-id=" + sportId + "]").next();

        container.html(Template.apply("regions_submenu", data));

        regionsClick(container, sportId);
    }

    function regionsClick(container, sportId)
    {
        container.find(".menu2-option")
            .click(function () {regionClick.call(this, sportId)});
    }

    function regionClick(sportId)
    {
        var containerEmpty = $(this).next().html() === "";

        if (containerEmpty && $(this).hasClass("selected"))
            return;

        var regionId = $(this).data("region-id");

        if (containerEmpty)
            fetchCompetitions(sportId, regionId);

        toggleRegion.call(this);
    }

    function toggleRegion() {
        $(this).toggleClass("selected");
        $(this).parent().find(".level3").toggleClass("hidden");
        $(this).find(".i2").toggleClass("hidden");
        $(this).find(".n2").toggleClass("menu-option-selected");
    }

    function fetchCompetitions(sportId, regionId)
    {
        $.get("/odds/competitions?sport=" + sportId + "&region=" + regionId)
            .done(function (data) {renderCompetitions(data, sportId, regionId)});
    }

    function renderCompetitions(data, sportId, regionId)
    {
        var container = $("#sports-menu-container").find("div[data-sport-id=" + sportId + "]").next()
            .find("div[data-region-id=" + regionId + "]").next();

        container.html(Template.apply('competitions_submenu', data));

        competitionsClick(container);
    }

    function competitionsClick(container)
    {
        $(container).find(".menu3-option").click(competitionClick);
    }

    function competitionClick()
    {
        $(".n3").removeClass("menu-option-selected");
        $(".i3").addClass("hidden");

        $(this).find(".i3").removeClass("hidden");
        $(this).find(".n3").addClass("menu-option-selected");


        competitionId = $(this).parent().data('competition-id');

        markets.make(competitionId);
    }

})();
