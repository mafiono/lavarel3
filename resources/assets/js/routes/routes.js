$(function() {

    var liveMode = false;

    page('*', hide);

    page('/', home);
    page('/desportos', sports);
    page('/desportos/destaque/:competitionId', highlight);
    page('/desportos/competicao/:competitionId', competition);
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

    function sports(ctx, next)
    {
        liveMode = false;

        Fixtures.make({
            mode: "favorites"
        });

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }

    function highlight(ctx, next)
    {
        var competitionId = ctx.params.competitionId;
        var competition = $("#sportsMenu-highlights").find("div[data-competition-id=" + competitionId + "]")
            .data("competition-name");

        var options = {
            competitionId: competitionId,
            competition: competition,
            operation: "Destaques"
        };

        Breadcrumb.make(options);

        Markets.makeHighlight(options);

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }

    function competition(ctx, next)
    {
        var competitionId = ctx.params.competitionId;

        var options = SportsMenu.competitionInfo(competitionId);
        options["operation"] = "Competition";
        options["competitionId"] = competitionId;

        Breadcrumb.make(options);
        Markets.make(options);

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }

    function live(ctx, next)
    {
        liveMode = true;

        Breadcrumb.make({operation: "Favoritos"});
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
        Breadcrumb.make({operation: "Favoritos"});

        Markets.makeFavorites();

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }

    function search(ctx, next)
    {
        var query = ctx.params.query;

        if (query.length <3) {
            page('/');

            return;
        }

        Breadcrumb.make({
            "operation": "Pesquisa",
            "query": query
        });

        Markets.makeQuery(query);

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }

});
