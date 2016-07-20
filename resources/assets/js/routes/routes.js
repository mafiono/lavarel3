$(function() {

    var liveMode = false;

    page('*', hide);

    page('/', home);
    page('/desportos', sports);
    page('/direto', live);
    page('/favoritos', favorites);
    page('/pesquisa/:query', search);

    page('*', gameMode);

    page();


    function hide(ctx, next)
    {
        $("#intro-container").addClass("hidden");
        $("#breadcrumb-container").addClass("hidden");
        $("#fixtures-container").addClass("hidden");
        $("#markets-container").addClass("hidden");

        next();
    }

    function gameMode()
    {
        if (liveMode) {
            $("#header-live").addClass("active");
            $("#header-prematch").removeClass("active");
            $("#sportsMenu-button-live").addClass("selected");
            $("#sportsMenu-button-prematch").removeClass("selected");
            $("#sportsMenu-live-container").removeClass("hidden");
            $("#sportsMenu-prematch-container").addClass("hidden");
        } else {
            $("#header-prematch").addClass("active");
            $("#header-live").removeClass("active");
            $("#sportsMenu-button-live").removeClass("selected");
            $("#sportsMenu-button-prematch").addClass("selected");
            $("#sportsMenu-live-container").addClass("hidden");
            $("#sportsMenu-prematch-container").removeClass("hidden");
        }
    }

    function home(ctx, next)
    {
        $("#intro-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }

    function sports(ctx, next) {
        liveMode = false;

        Markets.makeDefault();

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }

    function live(ctx, next)
    {
        liveMode = true;

        Markets.makeLive();

        if ($("#sportsMenu-live-container").html() === "")
            LiveMenu.make();

        $("#sportsMenu-live-container").removeClass("hidden");
        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }

    function favorites(ctx, next)
    {
        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        Markets.makeFavorites();

        next();
    }

    function search(ctx, next)
    {
        console.log(ctx);

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        if (ctx.params.query.length <3) {
            page('/');
        }

        Markets.makeQuery(ctx.params.query);

        next();
    }

});
