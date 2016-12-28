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
            selectedFixtureId: selectedFixtureId(),
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

        if (options.live)
            selectedFixtureId(options.container.find(".fixture.selected").data("game-id"));
        else
            selectedCompetitionId(options.container.find(".competition.selected").data("competition-id"));
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

    function refresh() {
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

    this.selectedFixtureId = function(fixtureId)
    {
        return selectedFixtureId(fixtureId);
    };

    function selectedFixtureId(fixtureId)
    {
        if (fixtureId)
            options.selectedFixtureId = fixtureId;

        return options.selectedFixtureId;
    }

    this.selectFixture = function(fixtureId)
    {
        var fixture = options.container.find("div[data-type=fixtureMenu][data-game-id=" + fixtureId + "]");

        selectedFixtureId(fixtureId);

        if (!fixture)
            return;

        fixture.parents(".sportsMenu").find("div[data-type=fixtureMenu]")
            .removeClass("selected")
            .children(".game")
            .removeClass("selected");

        fixture.addClass("selected")
            .children(".game")
            .addClass("selected");
    };


    this.selectCompetition = function(competitionId)
    {
        competitionId(competitionId);
    };

    this.unselectCompetitions = function()
    {
        unselectCompetitions();
    };

    function selectedCompetitionId(competitionId)
    {
        if (competitionId)
            options.selectedCompetitionId = competitionId;

        return options.selectedCompetitionId;
    }

    this.selectCompetition = function(competitionId)
    {
        selectedCompetitionId(competitionId);

        unselectCompetitions();

        selectCompetition(competitionId);
    };

    function selectCompetition(competitionId)
    {
        $(".sportsMenu .competition[data-competition-id=" + competitionId + "]").addClass("selected")
            .children("i").removeClass("hidden");
    }

    function unselectCompetitions()
    {
        options.container.find(".competition").removeClass("selected")
            .children('i').addClass("hidden");
    }

};

LiveSportsMenu = new SportsMenu({
    refreshInterval: 60
});