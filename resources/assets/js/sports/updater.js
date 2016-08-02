var Updater = new (function() {

    init();

    function init()
    {
        setInterval(updateSelections, 10000);

        // setInterval(updateFixtures, 10000);
    }

    this.updateSelections = function()
    {
        updateSelections();
    };

    function updateSelections()
    {
        var selections = $("button[data-type=odds]:visible");

        var ids = [];

        for (var i=0; i<selections.length; i++)
            ids.push($(selections[i]).data("event-id"));

        if (ids.length)
            $.get('http://genius.ibetup.eu/selections?ids=' + ids.join(',') + '&since=' + 15)
                .done(fetchSelections);
    }

    function fetchSelections(data)
    {
        var selections = data.selections;

        for (var i in selections)
            updateSelection(selections[i]);
    }

    function updateSelection(selection)
    {
        var btn = $("button[data-event-id=" + selection.id + "]");

        if (btn.data("event-price") == selection.decimal)
            return;

        var className = (btn.data("event-price") > selection.decimal ? "updater-down" : "updater-up");

        btn.addClass(className);
        btn.data('event-price', selection.decimal);
        btn.html(selection.decimal);

        setTimeout(function() {
            btn.removeClass(className);
        }, 5000);
    }

    function updateFixtures()
    {
        var fixtures = $(".sportsMenu div[data-type=fixtureMenu]:visible");

        var ids = [];

        for (var i=0; i<fixtures.length; i++)
            ids.push($(fixtures[i]).data("game-id"));

        if (ids.length)
            $.get('http://genius.ibetup.eu/fixtures?ids=' + ids.join(',') + '&since=' + 30)
                .done(renderFixtures);
    }


    function renderFixtures(data)
    {
        var fixtures = data.fixtures;

        for (var i in fixtures) {
            var matchState = $(".sportsMenu div[data-game-id=" + fixture.id + "]");


        }

    }

})();
