SportsMenu = function (_options)
{
    var options = {};

    var sportIds = [
        10, //Football
        4,  //Basketball
        24, //Tennis
        491393, //Futsal
        73743, //Rugby League
        73744, //Rugby Union
        99614 //Handball
    ];

    init(_options);

    function init (_options)
    {
        Helpers.updateOptions(_options, options);

        if (options.refreshInterval)
            setInterval(refresh, options.refreshInterval*1000);
    }

    this.make = function(_options)
    {
        Helpers.updateOptions(_options, options);

        make();
    };

    function make()
    {
        if (options.container)
            fetch();
    }

    function fetch()
    {
        $.getJSON(ODDS_SERVER + "sports?" + ids() + live())
            .done(render);
    }

    function render(data)
    {
        var container = options.container;

        container.html(Template.apply("sports_menu", data));

        var sports = container.find("div[data-type=sportMenu]");

        sports.click(sportClick);

        applySelection();

        if (options.auto)
            sports.first().click();
    }

    function live()
    {
        return options.live ? "&live" : "";
    }

    function ids()
    {
        return "ids=" + sportIds.join(",");
    }

    function sportClick()
    {
        if ($(this).hasClass("selected"))
            unselect.call(this);
        else
            select.call(this, true);
    }

    function select(cache, selections)
    {
        $(this).addClass("selected");

        $(this).children(".fa-plus")
            .removeClass("fa-plus")
            .addClass("fa-caret-down");

        var container = $(this).next();

        container.removeClass("hidden");

        if (cache && $.trim(container.html()) != "")
            return;

        new RegionsMenu({
            container: container,
            live: options.live,
            sportId: $(this).data("sport-id"),
            sportName: $(this).data("sport-name"),
            selections: selections,
            auto: options.auto
        }).make();

        options.auto = false;
    }

    function unselect()
    {
        $(this).removeClass("selected");

        $(this).children(".fa-caret-down")
            .removeClass("fa-caret-down")
            .addClass("fa-plus");

        $(this).next().addClass("hidden");
    }

    function takeSnapshot()
    {
        var sports = options.container.find("div.selected[data-type=sportMenu]");

        options.selections = {};

        sports.each(function(index, elem) {
            var sportId = $(elem).data("sport-id");

            options.selections[sportId] = [];

            selectedRegions($(elem).next(), options.selections[sportId]);
        });
    }

    function selectedRegions(container, sport)
    {
        var regions = container.find("div.selected[data-type=regionMenu]");

        regions.each(function(index, elem) {
            var regionId = $(elem).data("region-id");

            sport.push(regionId);
        });

    }

    this.refresh = function(_options)
    {
        Helpers.updateOptions(_options);

        refresh();
    };

    function refresh()
    {
        if (options.container && options.container.is(":visible")) {
            takeSnapshot();

            make();
        }
    }

    function applySelection()
    {
        for (var i in options.selections)
            select.call(options.container.find("div[data-type=sportMenu][data-sport-id=" + i + "]"), false, options.selections[i]);
    }

    function selectFixture(fixtureId)
    {
        Globals.selectedFixtureId = fixtureId;

        options.container.find("div[data-type=fixtureMenu]")
            .removeClass("selected")
            .children(".game")
            .removeClass("selected");

        options.container
            .find("div[data-type=fixtureMenu][data-game-id=" + fixtureId + "]")
            .addClass("selected")
            .children(".game")
            .addClass("selected");
    }

    this.selectFixture = function(fixtureId)
    {
        selectFixture(fixtureId);
    };

    function selectCompetition (competitionId)
    {
        Globals.selectedCompetitionId = competitionId;

        options.container.find(".competition").removeClass("selected")
            .children('i').addClass("hidden");

        $(".sportsMenu .competition[data-competition-id=" + competitionId + "]").addClass("selected")
            .children("i").removeClass("hidden");

    }

    this.selectCompetition = function (competitionId)
    {
        selectCompetition(competitionId);
    };

};

LiveSportsMenu = new SportsMenu({
    refreshInterval: 60
});
