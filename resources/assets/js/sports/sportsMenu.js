var SportsMenu = new (function()
{
    var until;

    init();

    function init()
    {
        until = encodeURIComponent(moment.utc().add(1, "years").format());

        new Spinner().spin(document.getElementById("sportsSpinner"));

        new Spinner().spin(document.getElementById("highlightsSpinner"));

        $("#sportsMenu-interval").click(intervalClick);

        $(".sportsMenu-container div[data-interval]").click(intervalOptionClick);

        make();
    };

    function intervalClick()
    {
        var expand = $(this).find("span i");

        expand.toggleClass("fa-plus");
        expand.toggleClass("fa-caret-down");
        expand.toggleClass("collapse");

        $(this).toggleClass("selected");

        $("#sportsMenu-interval-content").toggleClass("hidden");
    }

    function intervalOptionClick()
    {
        $("#sportsMenu-interval-text").html($(this).find("span").html());

        var interval = $(this).data("interval");

        var until = (interval == "today") ?
            moment.utc().add(1, 'days').hours(0).minutes(0).seconds(0).format() :
            moment.utc().add(interval, "hours").format();

        until = encodeURIComponent(until);

        Markets.makeUntil(until);

        $("#sportsMenu-interval-content").toggleClass("hidden");
    }

    function make()
    {
        fetchSports();
    }

    this.make = function()
    {
        make();
    };

    function fetchSports ()
    {
        $.get("/odds/sports?ids=10,24,4&until" + until)
            .done(renderSports);
    }

    function renderSports (data)
    {
        $("#sportsMenu-popular").html(Template.apply("sports_menu", data));

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

        var expand = $(this).find("span i");

        expand.toggleClass("fa-plus");
        expand.toggleClass("fa-caret-down");
        expand.toggleClass("collapse");
    }

    function fetchRegions (sportId)
    {
        $.get("/odds/regions?sport=" + sportId + "&until=" + until)
            .done(function (data) {renderRegions(data, sportId)})
    }

    function renderRegions (data, sportId) {
        var container = $("#sportsMenu-popular").find("div[data-sport-id=" + sportId + "]").next();

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
        $(this).parent().find(".level3").toggleClass("hidden");
        $(this).find(".i2").toggleClass("hidden");
        $(this).find(".sportsMenu-text-region.count").toggleClass("hidden");
        $(this).find(".n2").toggleClass("selected");
    }

    function fetchCompetitions(sportId, regionId)
    {
        $.get("/odds/competitions?sport=" + sportId + "&region=" + regionId)
            .done(function (data) {renderCompetitions(data, sportId, regionId)});
    }

    function renderCompetitions(data, sportId, regionId)
    {
        var container = $("#sportsMenu-popular").find("div[data-sport-id=" + sportId + "]").next()
            .find("div[data-region-id=" + regionId + "]").next();

        container.html(Template.apply('competitions_submenu', data));

        $(container).find(".menu3-option").click(competitionClick);
    }

    function competitionClick()
    {
        $(".n3").removeClass("selected");
        $(".i3").addClass("hidden");
        $(this).find(".i3").removeClass("hidden");
        $(this).find(".n3").addClass("selected");

        competitionId = $(this).parent().data('competition-id');

        Markets.make(marketsOptions.call(this));
    }

    function marketsOptions()
    {
        var competition = $(this).parent();
        var region = competition.parent().prev();
        var sport = region.parent().parent().prev();

        return {
            competition : competition.data("competition-name"),
            competitionId : competition.data("competition-id"),
            region : region.data("region-name"),
            sport : sport.data("sport-name")
        };
    }

    this.makeHighlights = function(highlights)
    {
        fetchHighlights(highlights);
    };

    function fetchHighlights(highligths)
    {
        if (highligths.length == 0) {
            $("#highlights-container").html("");

            return;
        }

        $.get("/odds/competitions?ids=" + highligths.join(','))
            .done(renderHighlights)
    }

    function renderHighlights(data)
    {
        var container = $("#highlights-container");

        container.html(Template.apply('highlights_submenu', data));

        container.find("div[data-type=highlight]").click(highlightClick);
    }

    function highlightClick()
    {
        Markets.makeHighlight({
            competition : $(this).data("competition-name"),
            competitionId : $(this).data("competition-id")
        });
    }

})();
