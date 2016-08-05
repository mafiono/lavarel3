var SelectionsUpdater = new (function() {

    init();

    function init()
    {
        setInterval(fetch, 10000);
    }

    this.updateSelections = function()
    {
        updateSelections();
    };

    function fetch()
    {
        var selections = $("button[data-type=odds]:visible");

        var ids = [];

        for (var i=0; i<selections.length; i++)
            ids.push($(selections[i]).data("event-id"));

        if (ids.length)
            $.get(ODDS_SERVER + 'selections?ids=' + ids.join(',') + '&since=' + 15)
                .done(render);
    }

    function render(data)
    {
        var selections = data.selections;

        for (var i in selections)
            apply(selections[i]);
    }

    function apply(selection)
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

})();
