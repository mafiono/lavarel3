var Markets = new (function ()
{
    var competitionId = null;

    this.graveward = function()
    {
        //MarketsController.showGamesMarket($(this).parent().data('id'));
    };

    this.make = function (competition)
    {
        competitionId = competition;

        fetchHeaderMarkets();
    };

    function fetchHeaderMarkets()
    {
        $.get("/odds/markets?competition=" + competitionId)
            .done(headerTemplate);
    }

    function headerTemplate(data)
    {
        Template.get(
            "/assets/Portal/templates/markets/markets_header.html",
            function (template) {renderHeader(template(data))}
        );
    }

    function renderHeader(template)
    {
        $("#markets-header-container").html(template);
    }
})();
