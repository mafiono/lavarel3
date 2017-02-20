RegionsMenu = function(_options)
{
    var options = {};

    var characters = {
        'á': 'a', 'à': 'a',
        'é': 'e', 'è': 'e',
        'í': 'i', 'ì': 'i',
        'ó': 'o', 'ò': 'o',
        'ú': 'u', 'ù': 'u'
    };

    init(_options);

    function init (_options)
    {
        Helpers.updateOptions(_options, options);
    }

    this.make = function(_options)
    {
        Helpers.updateOptions(_options, options);

        make();
    };

    function make()
    {
        fetch();
    }

    function fetch()
    {
        $.getJSON(ODDS_SERVER + "regions?sport=" + options.sportId
            + live())
            .done(render);
    }

    function render(data)
    {
        var container = options.container;

        regionsData(data);

        container.html(Template.apply("regions_menu", data));

        var regions = container.find("div[data-type=regionMenu]");

        regions.click(regionClick);

        applySelection();

        if (options.auto)
            regions.first().click();
    }

    function regionsData(data)
    {
        data["sportId"] = options.sportId;
        data["sportName"] = options.sportName;

        if (options.live)
            data["live"] = true;

        if (data.regions)
            data.regions.sort(function (a, b) {
                return latinize(a.name.toLowerCase()) < latinize(b.name.toLowerCase()) ? -1 : 1 ;
            });
    }

    function live()
    {
        return options.live ? "&live&ignoreOpenMarkets&fixturesCount" : "";
    }

    function regionClick()
    {
        if ($(this).hasClass("selected"))
            unselect.call(this);
        else
            select.call(this, true);
    }

    function select(cache)
    {
        $(this).addClass("selected");

        $(this).children(".count").addClass("hidden");

        $(this).children(".fa-caret-down").removeClass("hidden");

        var container = $(this).next();

        container.removeClass("hidden");

        if (cache && $.trim(container.html()) != "")
            return ;

        expand({
            container: container,
            sportId: $(this).data("sport-id"),
            sportName: $(this).data("sport-name"),
            regionId: $(this).data("region-id"),
            regionName: $(this).data("region-name"),
            selectedFixtureId: options.selectedFixtureId,
            selectedCompetitionId: options.selectedCompetitionId,
            auto: options.auto
        });

        options.auto = false;
    }

    function unselect()
    {
        $(this).removeClass("selected");

        $(this).children(".count").removeClass("hidden");

        $(this).children(".fa-caret-down").addClass("hidden");

        $(this).next().addClass("hidden");
    }

    function expand(_options)
    {
        if (options.live)
            (new FixturesMenu()).make(_options);
        else
            (new CompetitionMenu()).make(_options);
    }

    function applySelection()
    {
        for (var i in options.selections)
            select.call(options.container.find("div[data-type=regionMenu][data-region-id=" + options.selections[i] + "]"), false);
    }

    function latinize(str) {
        if (typeof str === 'string') {
            return str.replace(/[^A-Za-z0-9]/g, function(x) {
                return characters[x] || x;
            });
        } else {
            return str;
        }
    }
};



