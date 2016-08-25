CompetitionMenu = function(_options)
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
        $.get(ODDS_SERVER + "competitions?sport=" + options.sportId + "&region=" + options.regionId)
            .done(render);
    }

    function render(data)
    {
        var container = options.container;

        competitionsData(data);

        container.html(Template.apply('competitions_menu', data));

        container.find("div[data-type=competitionMenu]").click(competitionClick);
    }

    function competitionsData(data)
    {
        data["sportId"] = options.sportId;
        data["sportName"] = options.sportName;
        data["regionId"] = options.regionId;
        data["regionName"] = options.regionName;

        if (options.live)
            data["live"] = true;
    }

    function competitionClick()
    {
        select.call(this);

        page('/desportos/competicao/' + $(this).data('competition-id'));
    }

    function select()
    {
        $(this).parents(".sportsMenu").find("div[data-type=competitionMenu]")
            .removeClass("selected")
            .children(".fa-caret-right")
            .addClass("hidden");

        $(this).addClass("selected");

        $(this).children(".fa-caret-right").removeClass("hidden");
    }
    
}
