var Updater = new (function() {

    init();

    function init()
    {
        setInterval(updateSelections, 10000);
    }

    function updateSelections()
    {
        var selections = $("button[data-type=odds]");

        var ids = [];

        for (var i=0; i<selections.length; i++)
            ids.push($(selections[i]).data("event-id"));

        if (ids.length)
            $.get('/odds/selections?ids=' + ids.join(',') + '&since=' + 15)
                .done(fetchSelections);
    }

    function fetchSelections(data)
    {
        var selections = data.selections;

        for (var i in selections)
            update(selections[i]);
    }

    function update(selection)
    {
        var btn = $("button[data-event-id=" + selection.id + "]");

        if (btn.data("event-price") == selection.decimal)
            return;

        var className = (btn.data("event-price") > selection.decimal ? "updated-down" : "updated-up");

        btn.addClass(className);
        btn.data('event-price', selection.decimal);
        btn.html(selection.decimal);

        setTimeout(function() {
            btn.removeClass(className);
        }, 500);
    }

})();
