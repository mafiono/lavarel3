var RegionsMenu = new (function (_options)
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
        $.get(ODDS_SERVER + "regions?sport=" + options.sportId + live())
            .done(render);
    }

    function render(data)
    {
        var container = options.container;

        regionsData(data);

        container.html(Template.apply("regions_menu", data));

        container.find("div[data-type=regionMenu]").click(regionClick);
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
            select.call(this);
    }

    function select()
    {
        $(this).addClass("selected");

        $(this).children(".count").addClass("hidden");

        $(this).children(".fa-caret-down").removeClass("hidden");

        var container = $(this).next();

        container.removeClass("hidden");

        if (container.html() === "")
            expand({
                container: container,
                sportId: $(this).data("sport-id"),
                sportName: $(this).data("sport-name"),
                regionId: $(this).data("region-id"),
                regionName: $(this).data("region-name")
            });
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
            FixturesMenu.make(_options);
        else
            CompetitionMenu.make(_options);
    }

})();



