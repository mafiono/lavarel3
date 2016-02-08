$(function() {
    $("#featured-carousel, #cards-carousel").owlCarousel({
        items : 3,
        itemsScaleUp : false,
        pagination: false,
        navigation: false,
        responsive: false
    });

    var featuredCarousel = $("#featured-carousel").data('owlCarousel');
    $("#featured-prev").click(function(){
        featuredCarousel.next();
    });
    $("#featured-next").click(function(){
        featuredCarousel.next();
    });

    var cardsCarousel = $("#cards-carousel").data('owlCarousel');
    $("#cards-prev").click(function(){
        cardsCarousel.next();
    });
    $("#cards-next").click(function(){
        cardsCarousel.next();
    });

    $("#allMenuItem").click(function() {
        $("#allMenuItem").addClass("casino-box-menuItemSelected");
        $("#featuredMenuItem").removeClass("casino-box-menuItemSelected");
        $("#allContainer").removeClass("hidden");
        $("#featuredGamesContainer").addClass("hidden");
    });

    $("#featuredMenuItem").click(function() {
        $("#allMenuItem").removeClass("casino-box-menuItemSelected");
        $("#featuredMenuItem").addClass("casino-box-menuItemSelected");
        $("#allContainer").addClass("hidden");
        $("#featuredGamesContainer").removeClass("hidden");
    });
});
