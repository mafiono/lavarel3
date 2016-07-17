(function() {

    page('*', hide);

    page('/', home);
    page('/desportos', sports);
    page('/live', live);
    page('/favoritos', favorites);
    page('/pesquisa/:query', search);

    page();


    function hide(ctx, next)
    {
        $("#intro-container").addClass("hidden");
        $("#breadcrumb-container").addClass("hidden");
        $("#fixtures-container").addClass("hidden");
        $("#markets-container").addClass("hidden");

        next();
    }

    function home()
    {
        $("#intro-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");
    }

    function sports()
    {
        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");
    }

    function game(ctx)
    {
        console.log(ctx);
        $("#breadcrumb-container").removeClass("hidden");
        $("#markets-container").removeClass("hidden");
    }

    function live()
    {
        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");
    }

    function favorites()
    {
        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        Markets.makeFavorites();
    }

    function search(ctx)
    {
        console.log(ctx);

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        Markets.makeQuery(ctx.params.query);
    }
})();
