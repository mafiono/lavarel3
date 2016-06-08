var Markets = new (function ()
{
    var competitionId = null;

    var templates = {
        "2": "match_result",
        "122": "draw_no_bet",
        "7202": "double_chance"
    };

    this.make = function (competition)
    {
        competitionId = competition;

        fetchHeaderMarkets();
    };

    function fetchHeaderMarkets()
    {
        $.get("/odds/markets?competition=" + competitionId)
            .done(renderHeader);
    }

    function renderHeader(data)
    {
        headerData(data);

        $("#markets-header-container").html(Template.apply('markets_header', data));

        var select = $("#markets-select");

        select.change(marketSelect);

        var marketTypeId = select.find(":selected").val();

        fetchFixtures(competitionId, marketTypeId);
    }

    function headerData(data)
    {
        data.now = moment().format("DD MMM HH:mm");
    }

    function marketSelect()
    {
        var marketTypeId = $(this).find(":selected").val();

        fetchFixtures(competitionId, marketTypeId);
    }

    function fetchFixtures(competitionId, marketTypeId)
    {
        $.get("/odds/fixtures?competition=" + competitionId + "&marketType=" + marketTypeId)
            .done(function (data) {renderFixtures(data, marketTypeId)});
    }

    function renderFixtures(data, marketTypeId)
    {
        fixturesData(data);

        var marketsContent = $("#markets-content");

        marketsContent.html(Template.apply(templates[marketTypeId], data));

        marketsContent.find("[data-type='odds']").click(selectionClick);
    }

    function fixturesData(data)
    {
        var dates = {};

        data['fixtures'].forEach(function (fixture) {
            fixtureDate(dates, fixture);

            fixture['time'] = moment(fixture['start_time_utc']).format("HH:mm");
        });
    }

    function fixtureDate(dates, fixture)
    {
        var date = moment(fixture['start_time_utc']).format("ddd DD MMM");
        if (!dates[date]) {
            dates[date] = true;
            fixture['date'] = date;
        }
    }

    function selectionClick() {
        Betslip.toggle.call(this, {
            id: $(this).data("event-id"),
            name: $(this).data("event-name"),
            odds: $(this).data("event-price"),
            marketId: $(this).data("market-id"),
            marketName: $(this).data("market-name"),
            gameId: $(this).data("game-id"),
            gameName: $(this).data("game-name"),
            gameDate: $(this).data("game-date"),
            amount: 0
        });
    }

})();
