$(function() {
    $("#featured-carousel, #cards-carousel").owlCarousel({
        items : 4,
        itemsScaleUp : false,
        pagination: false,
        navigation: false,
        responsive: false
    });

    var featuredCarousel = $(".owl-carousel").data('owlCarousel');
    $("#featured-prev").click(function(){
        featuredCarousel.next();
    });
    $("#featured-next").click(function(){
        featuredCarousel.next();
    });

});
