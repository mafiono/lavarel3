ScoreCenter = new (function ()
{
    var options = {};

    var cache = {};

    var urls = {
        10: "https://casinoportugal.betstream.betgenius.com/betstream-view/footballscorecentre/casinoportugalfootballscorecentre/html?culture=pt-PT&eventId=",
        4: "https://casinoportugal.betstream.betgenius.com/betstream-view/basketballscorecentre/casinoportugalbasketballscorecentre/html?culture=pt-PT&eventId=",
        // 24: "https://casinoportugal.betstream.betgenius.com/betstream-view/tennisscorecentre/casinoportugaltennisscorecentre/html?culture=pt-PT&eventId=",
    };

    this.make = function(_options)
    {
        Helpers.updateOptions(_options, options);

        make();
    };

    function make()
    {
        options.container.addClass("hidden");

        if (cache[options.fixtureId])
            render();
        else
            fetch()
    }

    function fetch()
    {
        $.get(ODDS_SERVER + "fixtures?live&ids=" + options.fixtureId)
            .done(render);
    }

    function render(data)
    {
        if (data && data.fixtures.length)
            cache[options.fixtureId] = data.fixtures[0].sport_id;

        if (! urls[cache[options.fixtureId]])
            return;

        options.container.attr("src", urls[cache[options.fixtureId]] + options.fixtureId);
        options.container.removeClass("hidden");
    }

})();
