$(function() {

    var mode = "";

    page('*', hide);

    page('/', home);

    page('/desportos/destaque/:competitionId', highlight);
    page('/desportos/competicao/:competitionId', competition);
    page('/desportos/mercados/:fixtureId', markets);
    page('/desportos', sports);

    page('/direto/mercados/:fixtureId', liveMarkets);
    page('/direto', live);

    page('/favoritos', favorites);

    page('/pesquisa/:query', search);

    page('/info/:', info);

    page('*', pageMode);

    page();


    function hide(ctx, next)
    {
        $("#homepage-container").addClass("hidden");
        $("#breadcrumb-container").addClass("hidden");
        $("#fixtures-container").addClass("hidden");
        $("#match-container").addClass("hidden");
        $("#markets-container").addClass("hidden");

        next();
    }

    function pageMode()
    {
        switch (mode) {
            case "live":
                $("#header-live").addClass("active");
                $("#header-prematch").removeClass("active");
                $("#sportsMenu-button-live").addClass("selected");
                $("#sportsMenu-button-prematch").removeClass("selected");
                $("#sportsMenu-live-container").removeClass("hidden");
                $("#sportsMenu-prematch-container").addClass("hidden");
                break;
            case "sports":
                $("#header-prematch").addClass("active");
                $("#header-live").removeClass("active");
                $("#sportsMenu-button-live").removeClass("selected");
                $("#sportsMenu-button-prematch").addClass("selected");
                $("#sportsMenu-live-container").addClass("hidden");
                $("#sportsMenu-prematch-container").removeClass("hidden");
                break;
            default:
                $("#header-prematch").removeClass("active");
                $("#header-live").removeClass("active");
                $("#sportsMenu-button-live").removeClass("selected");
                $("#sportsMenu-button-prematch").removeClass("selected");
                $("#sportsMenu-live-container").addClass("hidden");
                $("#sportsMenu-prematch-container").removeClass("hidden");
                break;
        }
    }

    function home(ctx, next)
    {
        mode = "";

        LiveFixtures.make({
            container : $("#liveFixtures-container"),
            mode : "sport",
            sportName : "Futebol",
            sportId : "10",
            live : true,
            take: 5
        });

        TennisFixtures.make({
            container : $("#tennisFixtures-container"),
            mode : "sport",
            sportName : "Futebol",
            sportId : "10",
            take: 5
        });


        $("#homepage-container").removeClass("hidden");

        next();
    }

    function sports(ctx, next)
    {
        mode = "sports";

        var options = {
            sport: "Futebol",
            region: "Europa",
            competition: "UEFA Champions League",
            competitionId: 19,
            until: encodeURIComponent(moment.utc().add(1, "years").format()),
            operation: "Competition"
        };

        Breadcrumb.make(options);

        Fixtures.make(options);

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }

    function highlight(ctx, next)
    {
        mode = "sports";

        var competitionId = ctx.params.competitionId;
        var competition = $("#sportsMenu-highlights").find("div[data-competition-id=" + competitionId + "]")
            .data("competition-name");

        var options = {
            competitionId: competitionId,
            competition: competition,
            operation: "Destaques"
        };

        Breadcrumb.make(options);

        Fixtures.make(options);

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }

    function competition(ctx, next)
    {
        mode = "sports";

        var competitionId = ctx.params.competitionId;

        var options = SportsMenu.competitionInfo(competitionId);
        options["operation"] = "Competition";
        options["mode"] = "Competition";
        options["competitionId"] = competitionId;

        Breadcrumb.make(options);
        Fixtures.make(options);

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }


    function markets(ctx, next)
    {
        mode = "sports";

        var fixtureId = ctx.params.fixtureId;

        var options = {
            fixtureId: fixtureId,
            live: false
        };

        Markets.make(options);

        $("#breadcrumb-container").removeClass("hidden");
        $("#markets-container").removeClass("hidden");

        next();
    }

    function live(ctx, next)
    {
        mode = "live";

        Breadcrumb.make({operation: "AO-VIVO"});

        if ($("#sportsMenu-live-container").html() === "")
            LiveMenu.make();

        next();
    }


    function liveMarkets (ctx, next) {
        mode = "live";

        var fixtureId = ctx.params.fixtureId;

        var options = {
            fixtureId: fixtureId,
            live: true
        };

        Markets.make(options);

        var matchContainer = $("#match-container");

        matchContainer.prop("src","https://coolbet.betstream.betgenius.com/betstream-view/footballscorecentre/coolbetfootballscorecentre/html?eventId=" + fixtureId);

        matchContainer.removeClass("hidden");
        $("#markets-container").removeClass("hidden");

        next();
    }

    function favorites(ctx, next)
    {
        Breadcrumb.make({operation: "Favoritos"});

        Fixtures.make({mode: "favorites"});

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

        Fixtures.make({
            mode: "search",
            query: query
        });

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }

    function info(ctx, next)
    {


        $("#info-container").removeClass("hidden");

        next();
    }

});
