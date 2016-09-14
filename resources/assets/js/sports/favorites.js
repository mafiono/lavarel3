var Favorites = new (function () {

    var favorites = Cookies.getJSON("favorites") || [];

    var newFavorites = [];

    init();

    function init()
    {
        refresh();

        window.setInterval(refresh, 30000);

        $("#btnFavorites").click(showFavorites);

        $(window).unload(persist);
    }

    this.toggle = function()
    {
        $(this).toggleClass("selected");

        var id = $(this).data('game-id');

        if (has(id))
            remove(id);
        else
            add.call(this);

        activeIcon();
    };

    this.games = function()
    {
        return favorites;
    };

    this.apply = function()
    {
        apply();
    };

    function has(id)
    {
        for (var i in favorites)
            if (favorites[i].id == id)
                return true;

        return false;
    }

    function add()
    {
        favorites.push({
            'id': $(this).data("game-id"),
            'name': $(this).data("game-name"),
            'date': $(this).data("game-date")
        });
    }

    function remove(id)
    {
        for (var i in favorites)
            if (favorites[i].id == id) {
                favorites.splice(i, 1);
                return;
            }

    }

    function persist()
    {
        Cookies.set("favorites", favorites, {expires: 30});
    }

    function apply()
    {
        $("button[data-type=favorite]").removeClass("selected");

        for (var i in favorites)
            $("button[data-type=favorite][data-game-id=" + favorites[i].id + "]").addClass("selected");
    }

    function showFavorites(e)
    {
        if (favorites.length)
            page("/favoritos");

        e.preventDefault();

        return false;
    }

    function activeIcon()
    {
        $("#btnFavorites").find("i").css("color", (favorites.length ? "#ff9900" : "#cccccc"));
    }

    function getIds() {
        var ids = [];

        for (var i in favorites)
            ids.push(favorites[i].id);

        return ids.join(',');
    }

    function refresh()
    {
        var ids = getIds();

        if (!ids.length)
            return;

        newFavorites = [];

        $(function () {fetch(ids, false);});
    }

    function fetch(ids, live)
    {
        $.get(ODDS_SERVER + "fixtures?ids=" + ids + (live ? "&live" : "" ))
            .done(function (data) {

                var fixtures = data.fixtures;

                for (var i in fixtures) {
                    var fixture = fixtures[i];

                    newFavorites.push({
                        id: fixture.id,
                        name: fixture.name,
                        date: fixture.date
                    });
                }

                if (live) {
                    reset();

                    return;
                }

                fetch(ids, true);
            });
    }

    function reset()
    {
        favorites = [];

        for (var i in newFavorites)
            favorites.push(newFavorites[i]);

        activeIcon();
    }

})();
