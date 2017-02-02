FixturesMenu = function(_options)
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
        $.get(ODDS_SERVER + "fixtures?sport=" +
            options.sportId + "&region=" +
            options.regionId + "&live&ignoreOpenMarkets" +
            "&orderBy=elapsed,desc"
        ).done(render);
    }

    function render(data)
    {
        var container = options.container;

        container.html(Template.apply('fixtures_menu', data));

        Favorites.apply();

        var fixtures = container.find("div[data-type=fixtureMenu]");

        fixtures.click(fixtureClick);

        container.find("button[data-type=favorite]").click(favoriteClick);

        if (Globals.selectedFixtureId > -1)
            applySelected();

        // if (options.auto)
        //     fixtures.first().click();
    }

    function favoriteClick()
    {
        Favorites.toggle.call(this);
    }

    function fixtureClick()
    {
        select.call(this);

        page('/direto/mercados/' + $(this).data("game-id"));
    }

    function select()
    {
        $(this).parents(".sportsMenu").find("div[data-type=fixtureMenu]")
            .removeClass("selected")
            .children(".game")
            .removeClass("selected");

        $(this).addClass("selected")
            .children(".game")
            .addClass("selected");
    }

    function applySelected()
    {
        var selected = options.container.find("div[data-type=fixtureMenu][data-game-id=" + Globals.selectedFixtureId + "]");

        if (selected)
            select.call(selected);
    }

};
