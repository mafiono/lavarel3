Handlebars.registerHelper("for", function(data, i, n, options) {
    var collection = [];
    var index = 0;
    for (var key in data) {
        if (index >= i && index < n)
            collection.push(data[key]);
        index++;
    }
    var res = "";
    for (var key in collection) {
        res += options.fn(collection[key]);
    }
    return res;
});

$(function(){
    Favorites.onChange(function() {
        $(".favoritos-contend").html(Favorites.count());
        var favoritesContainer = $("#favorites-container");
        if (Favorites.count() && !favoritesContainer.hasClass("hidden"))
            populateFavorites();
        else
            favoritesContainer.addClass("hidden");
    });

    Favorites.onPageChange(populateFavorites);

    $("#favorites-open").click(function (){
        if (!Favorites.count())
            return;
        var favoritesContainer = $("#favorites-container");
        favoritesContainer.toggleClass("hidden");
        if (!favoritesContainer.hasClass("hidden")) {
            populateFavorites();
        }
    });

    function populateFavorites() {
        Template.get("/assets/portal/templates/favorites.html", function (template) {
            var favoritesContainer = $("#favorites-container");
            var page = Favorites.page();
            var pageStep = Favorites.pageStep();
            favoritesContainer.html(template({
                "games": Favorites.games(),
                "hasPrev": Favorites.hasPrevPage(),
                "hasNext": Favorites.hasNextPage(),
                "startIndex": page*pageStep,
                "endIndex": (page+1)*pageStep
            }));
            favoritesContainer.find("[data-type='game']").click(function () {
                MarketsController.showGameMarkets($(this).data("game-id"));
                $("#favorites-close").click();
            });
            $("#favorites-close").click(function() {
                favoritesContainer.toggleClass("hidden");
            });
            favoritesContainer.find("button[data-remove]").click(function (e) {
                var gameId = $(this).data("game-id");
                Favorites.remove(gameId);
                var favoriteButton = $("#favorite-button-"+gameId);
                favoriteButton.removeClass(favoriteButton.data("selected-css"));
                e.stopImmediatePropagation();
            });
            $("#favorites-prev").click(function() {
                Favorites.page(Favorites.page()-1);
            });
            $("#favorites-next").click(function() {
                Favorites.page(Favorites.page()+1);
            });
        });
    }
});
