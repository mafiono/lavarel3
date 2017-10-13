SportsMenu = function (_options)
{
    var options = {};

    var sportIds = [
        10, //Football
        4,  //Basketball
        15, //Ice Hockey
        24, //Tennis
        491393, //Futsal
        73743, //Rugby League
        73744, //Rugby Union
        99614, //Handball
        91189 //Volleyball
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

        data.live = options.live;

        container.html(Template.apply("sports_menu", data));

        var sports = container.find("div[data-type=sportMenu]");

        sports.click(sportClick);

        applySelection();

        if (options.auto)
            sports.first().click();
    }

    function live()
    {
        return options.live ? "&live&ignoreOpenMarkets" : "";
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

        $(this).children(".cp-plus")
            .removeClass("cp-plus")
            .addClass("cp-caret-down");

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
        console.log($(this));

        $(this).removeClass("selected");

        $(this).children(".cp-caret-down")
            .removeClass("cp-caret-down")
            .addClass("cp-plus");

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

    function selectSport(sportId)
    {
        let handle = setInterval(function () {
            let sport = options.container.find(".sport[data-sport-id=" + sportId + "]");
            if (sport.hasClass("selected")) {
                clearInterval(handle);
                return;
            } else {
                sport.click();
            }
        }, 200);
        setTimeout(function() {clearInterval(handle)}, 8000);
    }

    this.selectSport = function(sportId) {
        selectSport(sportId);
    };

    function selectRegion(sportId, regionId)
    {
        selectSport(sportId);

        let handle = setInterval(function () {
            let region = options.container.find(".sportsMenu .region[data-sport-id=" + sportId + "][data-region-id=" + regionId + "]");
            if (region.hasClass("selected")) {
                clearInterval(handle);
                return;
            } else {
                region.click();
            }
        }, 400);
        setTimeout(function() {clearInterval(handle)}, 8000);
    }

    this.selectRegion = function(regionId, sportId)
    {
        selectRegion(regionId, sportId);
    };

};

LiveSportsMenu = new SportsMenu({
    refreshInterval: 60
});
