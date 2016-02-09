$(function() {
    populateCasinoMenu();
    $("#featured-carousel, #cards-carousel").owlCarousel({
        items : 4,
        itemsScaleUp : false,
        pagination: false,
        navigation: false,
        responsive: false
    });
    var featuredCarousel = $("#featured-carousel").data('owlCarousel');

    $("#featured-prev").click(function(){
        featuredCarousel.prev();
    });
    $("#featured-next").click(function(){
        featuredCarousel.next();
    });

    var cardsCarousel = $("#cards-carousel").data('owlCarousel');

    $("#cards-prev").click(function(){
        cardsCarousel.prev();
    });

    $("#cards-next").click(function(){
        cardsCarousel.next();
    });

    function populateCasinoMenu() {
        Template.get("assets/portal/templates/casino_menu.html", function (template) {
            $.get("/game_types", function(data) {
                $("#casinoMenuContainer").html(template(data));
                $("#allMenuItem").click(allMenuItemClick);
                var game_types = data.game_types;
                for (var i in game_types)
                    $("#"+game_types[i].id+"MenuItem").click(menuItemClick);
            });
        });
    }

    function selectMenuItem() {
        $("#casinoMenuContainer").find("[data-selected-css]").removeClass($(this).data("selected-css"));
        $(this).addClass($(this).data("selected-css"));
    }

    function allMenuItemClick() {
        selectMenuItem.call(this);
    }

    function menuItemClick() {
        selectMenuItem.call(this);
    }
});
