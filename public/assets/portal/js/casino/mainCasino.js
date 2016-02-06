$(function() {
    $("#featured-carousel, #cards-carousel").owlCarousel({
        items : 4,
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

});
