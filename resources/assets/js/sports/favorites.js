var Favorites = new (function () {

    var favorites = Cookies.getJSON("favorites") || [];

    init();

    function init()
    {
        restore();

        $("#btnFavorites").click(showFavorites);

        $(window).unload(persist);

        activeIcon();
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

    function restore()
    {
        if (!favorites)
            return;

        for (var i=0; i<favorites.length; i++)
            if (moment(favorites[i].date).add(1, "days")<moment()) {
                favorites.splice(i, 1);
                i--;
            }

        apply();
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

})();
