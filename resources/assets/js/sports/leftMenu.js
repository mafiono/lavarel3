LeftMenu = new (function()
{
    this.init = function() {
        init();
    };

    function init() {
        $("#sportsMenu-button-live").click(liveClick);

        $("#sportsMenu-button-prematch").click(prematchClick);

       // make();
    }

    this.makeHighlights = function(highlights)
    {
        fetchHighlights(highlights);
    };

    function fetchHighlights(highligths)
    {
        if (highligths.length === 0) {
            $("#highlights-container").html("");

            return;
        }

        $.get(ODDS_SERVER + "competitions?ids=" + highligths.join(','))
            .done(renderHighlights)
    }

    function renderHighlights(data)
    {
        data.competitions = data.competitions.slice(0, 4);

        // Sort by name
        data.competitions.sort(function(a,b) {return (a.name > b.name) ? 1 : ((b.name > a.name) ? -1 : 0);} );

        var container = $("#sportsMenu-highlights");

        container.html(Template.apply('highlights_submenu', data));

        container.find("div[data-type=highlight]").click(highlightClick);
    }

    function highlightClick()
    {
        page('/desportos/destaque/' + $(this).data("competition-id"));
    }

    function liveClick()
    {
        page('/direto');
    }

    function prematchClick()
    {
        page('/desportos');
    }

})();
