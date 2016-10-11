RegionsMenu = function(_options)
{
    var options = {};

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
        $.getJSON(ODDS_SERVER + "regions?sport=" + options.sportId + live())
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
    }

    function live()
    {
        return options.live ? "&live&fixturesCount" : "";
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

}



