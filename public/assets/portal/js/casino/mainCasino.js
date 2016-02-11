$(function() {
    var menuSpinner = new Spinner().spin(document.getElementById("casino-menu-container"));
    var contentSpinner = new Spinner().spin(document.getElementById("casino-content-container"));
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
        startContentSpinner.call(this);
        Template.get("assets/portal/templates/casino_games.html", function (template) {
            $.get(url, function(data) {
                $("#casino-content-container").html(template(data));
            });
        });
    }

    function populateAllGames() {
        startContentSpinner();
        Template.get("assets/portal/templates/casino_allGames.html", function (template) {
            $.get("/casino/games", function(data) {
                $("#casino-content-container").html(template(data));
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

    function startContentSpinner() {
        var contentContainer = $("#casino-content-container").get(0);
        $(contentContainer).html("");
        contentSpinner.spin(contentContainer);
    }

});