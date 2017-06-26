FixturesMenuUpdater = new (function() {

    init();

    function init()
    {
        setInterval(fetch, 10000);
    }

    function fetch()
    {
        var fixtures = $(".sportsMenu div[data-type=fixtureMenu]:visible");

        var ids = [];

        for (var i=0; i<fixtures.length; i++)
            ids.push($(fixtures[i]).data("game-id"));

        if (ids.length)
            $.get(ODDS_SERVER + 'fixtures?ids=' + ids.join(',') + '&live&since=' + 10)
                .done(render);
    }

    function render(data)
    {
        var fixtures = data.fixtures;

        for (var i in fixtures) {
            var fixture = fixtures[i];

            var matchState = $(".sportsMenu div[data-game-id=" + fixture.id + "] .matchState");

            matchState.html(fixture.elapsed + "<br>" + fixture.score);
        }
    }

});
