ScoreCenter = new (function ()
{
    var options = {};

    var cache = {};

    var urls = {
        10: "https://betportugal.betstream.betgenius.com/betstream-view/footballscorecentre/betportugalfootballscorecentre/html?eventId=",
        4: "http://betportugal.betstream.betgenius.com/betstream-view/basketballscorecentre/betportugalbasketballscorecentre/html?eventId=",
        24: "https://betportugal.betstream.betgenius.com/betstream-view/tennisscorecentre/betportugaltennisscorecentre/html?eventId=",
    };

    this.make = function(_options)
    {
        Helpers.updateOptions(_options, options);

        make();
    };

    function make()
    {
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
        if (data)
            cache[options.fixtureId] = data.fixtures[0].sport_id;

        options.container.attr("src", urls[cache[options.fixtureId]] + options.fixtureId);
        options.container.removeClass("hidden");
    }

})();
