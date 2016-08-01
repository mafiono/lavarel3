function SportsMenu (_options)
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
        $.getJSON(ODDS_SERVER + "sports" + live())
            .done(render);
    }

    function render(data)
    {
        var container = options.container;

        container.html(Template.apply("sports_menu", data));

        container.find("div[data-type=sportMenu]").click(sportClick);
    }

    function live()
    {
        return options.live ? "?live" : "";
    }

    function sportClick()
    {
        if ($(this).hasClass("selected"))
            unselect.call(this);
        else
            select.call(this);
    }

    function select()
    {
        $(this).addClass("selected");

        $(this).children(".fa-plus")
            .removeClass("fa-plus")
            .addClass("fa-caret-down");

        var container = $(this).next();

        container.removeClass("hidden");

        if (container.html() === "")
            RegionsMenu.make({
                container: container,
                live: options.live,
                sportId: $(this).data("sport-id"),
                sportName: $(this).data("sport-name")
            });
    }

    function unselect()
    {
        $(this).removeClass("selected");

        $(this).children(".fa-caret-down")
            .removeClass("fa-caret-down")
            .addClass("fa-plus");

        $(this).next().addClass("hidden");
    }

    this.snap = function() {
        takeSnapshot();
    };

    function takeSnapshot()
    {
        var sports = options.container.find("div.selected[data-type=sportMenu]");

        options.selections = {};

        sports.each(function(index, elem) {
            var sportId = $(elem).data("sport-id");

            options.selections[sportId] = [];

            takeSelectedRegions($(elem).next(), options.selections[sportId]);
        });

        console.log(options);

    }

    function takeSelectedRegions(container, sport)
    {
        var regions = container.find("div.selected[data-type=regionMenu]");

        regions.each(function(index, elem) {
            var regionId = $(elem).data("region-id");

            sport.push(regionId);
        });

    }

    function refresh()
    {
        make();
    }
}

var LiveSportsMenu = new SportsMenu();
