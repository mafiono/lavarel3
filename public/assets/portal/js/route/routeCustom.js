Router.config({ mode: 'history'});

var beginRoute = window.location.pathname;

Router.navigate();

// adding routes
Router
    .add(/aovivo/, function() { 
        selectHeaderMenuItem($("#header_aovivo"));
        $("#_casino").addClass("hidden");
        $("#_apostas").removeClass("hidden");
        SportsBarController.gameType(1);
    })
    .add(/desportos/, function() {
        selectHeaderMenuItem($("#header_desportos"));
        $("#_casino").addClass("hidden");
        $("#_apostas").removeClass("hidden");
        SportsBarController.gameType(0);
    })
    .add(/casino/, function() {
        selectHeaderMenuItem($("#header_casino"));
        $("#_casino").removeClass("hidden");
        $("#_apostas").addClass("hidden");
    })
    .add(function() {
        console.log('default');
    })
    .listen();

//TODO: function shoudn't know CSS classes
function selectHeaderMenuItem(menuItem) {
    $(".menu-black-active").removeClass("menu-black-active");
    menuItem.find("li").addClass("menu-black-active");
};

$(function () {
    Router.navigate(beginRoute);
});

