$(function() {
    populateCasinoMenu();
    populateAllGames();

    function populateCasinoMenu() {
        Template.get("assets/portal/templates/casino_menu.html", function (template) {
            $.get("/casino/game_types", function(data) {
                $("#casino-menu-container").html(template(data));
                $("#casino-all-menuItem").click(allMenuItemClick);
                var game_types = data["game_types"];
                for (var i in game_types)
                    $("#casino-"+game_types[i].id+"-menuItem").click(menuItemClick);
            });
        });
    }

    function selectMenuItem() {
        $("#casino-menu-container").find("[data-selected-css]").removeClass($(this).data("selected-css"));
        $(this).addClass($(this).data("selected-css"));
    }

    function allMenuItemClick() {
        selectMenuItem.call(this);
    }

    function menuItemClick() {
        selectMenuItem.call(this);
        if ($(this).data("id") === "featured")
            populateGames("/casino/featured_games");
        else if ($(this).data("id") === "all")
            populateAllGames();
        else
            populateGames("/casino/games/"+$(this).data("id"));
    }

    function populateGames(url) {
        Template.get("assets/portal/templates/casino_games.html", function (template) {
            $.get(url, function(data) {
                var casinoGamesContainer = $("#casino-games-container");
                selectContainer(casinoGamesContainer);
                casinoGamesContainer.html(template(data));
            });
        });
    }

    function populateAllGames() {
        Template.get("assets/portal/templates/casino_allGames.html", function (template) {
            $.get("/casino/games", function(data) {
                var casinoAllContainer = $("#casino-all-container");
                casinoAllContainer.html(template(data));
                selectContainer(casinoAllContainer);
                var gameTypes = data["game_types"];
                for (var i in gameTypes) {
                    $("#casino-"+gameTypes[i]["id"]+"-carousel").owlCarousel({
                        items : 4,
                        itemsScaleUp : false,
                        pagination: false,
                        navigation: false,
                        responsive: false
                    });
                    var carousel = $("#casino-"+gameTypes[i]["id"]+"-carousel").data('owlCarousel');
                    $("#casino-"+gameTypes[i]["id"]+"-prev").click(
                        (function(carousel) {
                            return function () {carousel.prev();}
                        })(carousel)
                    );
                    $("#casino-"+gameTypes[i]["id"]+"-next").click(
                        (function(carousel) {
                            return function () {carousel.next();}
                        })(carousel)
                    );
                }
            });
        });
    }

    function selectContainer(container) {
        $("#casino-all-container").addClass("hidden");
        $("#casino-featuredGames-container").addClass("hidden");
        $("#casino-games-container").addClass("hidden");
        $(container).removeClass("hidden");
    }

});
