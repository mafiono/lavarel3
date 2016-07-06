var Favorites = new (function () {

    var favorites = Cookies.getJSON("favorites") || [];

    init();

    function init()
    {
        restore();

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
            if (favorites[i].id == id)
                favorites.splice(i, 1);
    }

    function restore()
    {
        var oldFavorites = Cookies.getJSON("favorites");
        
        if (!oldFavorites)
            return;
        
        apply();
    }

    function persist()
    {
        Cookies.set("favorites", favorites, {expires: 30});
    }

    function apply()
    {
        for (var i in favorites)
            $("#favorite-button-"+favorites[i].id).addClass("selected");
    }

    function showFavorites(e)
    {
        if (favorites.length)
            Markets.makeFavorites();

        e.preventDefault();

        return false;
    }

})();
