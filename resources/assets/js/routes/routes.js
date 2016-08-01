$(function() {

    var mode = "";

    var sportsPage = false;

    var prev = "/";


    page('*', allowed);

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

    page('/info', info);
    page('/info/:term', info);

    page('/desportos/estatistica/:fixtureId', statistics);
    page('/direto/estatistica/:fixtureId', statistics);

    page('*', pageMode);

    page();


    function allowed (ctx, next)
    {
        if (/((\/$)|(\/info.*))|(\/pesquisa.*)|(\/direto.*)|(\/desporto.*)|(\/favoritos)/.test(ctx.path)) {
            next();

            return;
        }

        page.stop();

        window.location = ctx.path;

        next();
    }

    function hide(ctx, next)
    {
        $("#homepage-container").addClass("hidden");
        $("#breadcrumb-container").addClass("hidden");
        $("#fixtures-container").addClass("hidden");
        $("#match-container").addClass("hidden");
        $("#markets-container").addClass("hidden");
        $("#search-container").addClass("hidden");
        $("#favorites-container").addClass("hidden");
        $("#liveMarkets-container").addClass("hidden");
        $("#info-container").addClass("hidden");
        $("#statistics-container").addClass("hidden");

        next();
    }

    function pageMode(ctx)
    {
        prev = ctx.path;

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
            expand : true,
            take: 5
        });

        HighFixtures.make({
            container : $("#highFixtures-container"),
            mode : "sport",
            sportName : "Em Alta",
            sportId : "10",
            expand : true,
            take: 5
        });

        TennisFixtures.make({
            container : $("#tennisFixtures-container"),
            mode : "sport",
            sportName : "Ténis",
            sportId : "24",
            expand : true,
            take: 5
        });

        $("#homepage-container").removeClass("hidden");

        next();
    }

    function sports(ctx, next)
    {
        if (!sportsPage)
            sportsPage = '/desportos/destaque/19';

        page(sportsPage);

        next();
    }

    function highlight(ctx, next)
    {
        mode = "sports";

        sportsPage = ctx.path;

        var competitionId = ctx.params.competitionId;
        var competition = $("#sportsMenu-highlights").find("div[data-competition-id=" + competitionId + "]")
            .data("competition-name");

        var options = {
            competitionId: competitionId,
            competition: competition,
            container: $("#fixtures-container"),
            mode: "highlights"
        };

        Breadcrumb.make(options);

        SportsFixtures.make(options);

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }

    function competition(ctx, next)
    {
        mode = "sports";

        sportsPage = ctx.path;

        var competitionId = ctx.params.competitionId;

        Breadcrumb.make({
            mode: "competition",
            competitionId: competitionId,
        });

        SportsFixtures.make({
            mode: "competition",
            competitionId: competitionId,
            container: $("#fixtures-container")
        });

        next();
    }


    function markets(ctx, next)
    {
        mode = "sports";

        var fixtureId = ctx.params.fixtureId;

        Breadcrumb.make({
            fixtureId: fixtureId,
            mode: "markets"
        });

        Markets.make({
            fixtureId: fixtureId,
            live: false,
            container: $("#markets-container")
        });

        $("#breadcrumb-container").removeClass("hidden");
        $("#markets-container").removeClass("hidden");

        next();
    }

    function live(ctx, next)
    {
        mode = "live";

        var container = $("#sportsMenu-live-container");

        if (container.html() === "")
            LiveSportsMenu.make({
                container: container,
                live: true,
                markets: true
            });
        // else
        //     page('/direto/mercados/' + LiveSportsMenu.selected());


        $("#liveMarkets-container").removeClass("hidden");
        $("#match-container").removeClass("hidden");

        next();
    }


    function liveMarkets (ctx, next) {
        mode = "live";

        var fixtureId = ctx.params.fixtureId;

        // if (LiveMenu.loaded())
        //     LiveMenu.selected(fixtureId);
        // else
        //     LiveMenu.make();

        var container = $("#sportsMenu-live-container");

        if (container.html() === "")
            LiveSportsMenu.make({
                container: container,
                live: true,
                markets: true
            });


        Markets.make({
            fixtureId: fixtureId,
            live: true,
            container: $("#liveMarkets-container")
        });

        var matchContainer = $("#match-container");

        matchContainer.attr("src","https://betportugal-uat.betstream.betgenius.com/betstream-view/footballscorecentre/betportugalfootballscorecentre/html?eventId=" + fixtureId);

        matchContainer.removeClass("hidden");

        next();
    }

    function favorites(ctx, next)
    {
        mode = "";

        Breadcrumb.make({mode: "favorites"});

        LiveFavoritesFixtures.make({
            mode: "favorites",
            live: true,
            container: $("#favorites-live-container")
        });

        FavoritesFixtures.make({
            mode: "favorites",
            container: $("#favorites-prematch-container")
        });

        $("#breadcrumb-container").removeClass("hidden");
        $("#favorites-container").removeClass("hidden");

        next();
    }

    function search(ctx, next)
    {
        mode = "";

        var query = ctx.params.query;

        if (query.length <3) {
            page('/');

            return;
        }

        Breadcrumb.make({
            "mode": "search",
            "query": query
        });

        LiveSearchFixtures.make({
            mode: "search",
            container: $("#search-live-container"),
            live: true,
            query: query
        });

        SearchFixtures.make({
            mode: "search",
            container: $("#search-prematch-container"),
            query: query
        });

        $("#breadcrumb-container").removeClass("hidden");
        $("#search-container").removeClass("hidden");

        next();
    }

    function info(ctx, next)
    {

        Info.make(ctx.params.term);

        $("#info-container").removeClass("hidden");

        next();
    }


    function statistics(ctx, next)
    {

        var fixtureId = ctx.params.fixtureId;

        var live = /(\/direto.*)/.test(ctx.path);

        Breadcrumb.make({
            fixtureId: fixtureId,
            mode: "markets",
            live: live
        });

        Statistics.make({
            fixtureId: fixtureId,
            live: live,
            closePath: prev,
            title: ""
        });

        $("#statistics-container").removeClass("hidden");

        next();
    }


});
