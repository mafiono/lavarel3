$(function() {
    var mode = "";

    var sportsPage = false;

    var prev = "/";

    page('*', allowed);

    page('*', hideMobile);

    page('*', hide);

    page('/', home);

    page('/mobile/:view', mobile);

    page('/desportos/destaque/:competitionId', highlight);
    page('/desportos/competicao/:competitionId', competition);
    page('/desportos/mercados/:fixtureId', markets);
    page('/desportos', sports);

    page('/direto/mercados/:fixtureId', liveMarkets);
    page('/direto', live);

    page('/favoritos', favorites);
    page('/casino', casino);

    page('/pesquisa/:query', search);

    page('/registar/:step?', register);
    page.exit('/registar/*', exitRegister);

    page('/info', info);
    page('/info/:term', info);

    page('/perfil/historico', perfilHistorico);
    page.exit('/perfil/historico', exitPerfilHistorico);

    page('/perfil', perfil('perfil'));
    page('/perfil/:sub', perfil('perfil'));
    page('/perfil/:page/:sub', perfil());
    page('/perfil/*', exitPerfil);

    page('/desportos/estatistica/:fixtureId', statistics);
    page('/direto/estatistica/:fixtureId', statistics);

    page('/promocoes', promotions);

    page('*', pageMode);

    page();

    function allowed (ctx, next)
    {
        if (/((\/$)|(\/info.*))|(\/promocoes.*)|(\/pesquisa.*)|(\/direto.*)|(\/desporto.*)|(\/casino.*)|(\/favoritos)|(\/registar)|(\/perfil.*)|(\/mobile.*)/.test(ctx.path)) {
            var staticContainer = $('.static-container');
            if (staticContainer.length) {
                staticContainer.hide();
                $('.markets-container').show();
            }
            next();

            return;
        }

        page.stop();

        if (window.location.pathname !== ctx.path)
            window.location.href = ctx.path;

        next();
    }

    function hideMobile(ctx, next)
    {
        if (MobileHelper.isMobile()) {
            MobileHelper.hideContainers();

            $(window).scrollTop(0);

            if (ctx.path.substr(0,7) !== "/mobile")
                MobileHelper.showView();
        }

        next();
    }

    function mobile(ctx, next)
    {
        if (MobileHelper.isMobile()
            && ctx.params.view
        ) {
            if (ctx.params.view === 'login'
                && Store.getters['user/isAuthenticated']
            ) {
                page("/");

                return;
            }

            MobileHelper.showView(ctx.params.view);
        }

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
        $("#perfil-container").addClass("hidden");
        $("#statistics-container").addClass("hidden");
        $("#register-container").addClass("hidden");
        $("#middleAlert-container").addClass("hidden");
        $("#sports-container").addClass("hidden");
        $("#live-container").addClass("hidden");
        Store.commit('promotions/setVisible', false);

        next();
    }

    function pageMode(ctx)
    {
        prev = ctx.path;

        switch (mode) {
            case "live":
                $(".header-live").addClass("active");
                $(".header-prematch").removeClass("active");
                $(".header-casino").removeClass("active");
                $("#sportsMenu-button-live").addClass("selected");
                $("#sportsMenu-button-prematch").removeClass("selected");
                $("#sportsMenu-live-container").removeClass("hidden");
                $("#sportsMenu-prematch-container").addClass("hidden");
                break;
            case "sports":
                $(".header-prematch").addClass("active");
                $(".header-live").removeClass("active");
                $(".header-casino").removeClass("active");
                $("#sportsMenu-button-live").removeClass("selected");
                $("#sportsMenu-button-prematch").addClass("selected");
                $("#sportsMenu-live-container").addClass("hidden");
                $("#sportsMenu-prematch-container").removeClass("hidden");
                break;
            case "casino":
                $(".header-prematch").removeClass("active");
                $(".header-live").removeClass("active");
                $(".header-casino").addClass("active");
                $("#sportsMenu-button-live").removeClass("selected");
                $("#sportsMenu-button-prematch").removeClass("selected");
                $("#sportsMenu-live-container").addClass("hidden");
                $("#sportsMenu-prematch-container").removeClass("hidden");
                break;
            default:
                $(".header-prematch").removeClass("active");
                $(".header-live").removeClass("active");
                $(".header-casino").removeClass("active");
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

        BannersMenu.make({
            container : $("#banners-container"),
            types : [ 'title', 'carousel' ],
        });

        PopularSportsMenu.selectCompetition(-1);

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
            mode : "highgames",
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
        mode = "sports";

        Breadcrumb.make({
            mode: "title",
            title: "Desportos"
        });

        HighFixtures.make({
            container : $("#sports-high-container"),
            mode : "highgames",
            sportName : "Em Alta",
            sportId : "10",
            take: 20,
            expand: true
        });

        $("#sports-container").removeClass("hidden");

        PopularSportsMenu.selectCompetition(-1);

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

        Breadcrumb.make({
            mode: "title",
            title: "Direto"
        });

        LiveFixtures.make({
            container : $("#live-football-container"),
            mode : "sport",
            sportName : "Futebol",
            sportId : "10",
            live : true,
            expand : true,
            take: 5
        });

        LiveBasketballFixtures.make({
            container : $("#live-basketball-container"),
            mode : "sport",
            sportName : "Basquetebol",
            sportId : "4",
            live : true,
            expand : true,
            take: 5
        });

        LiveTenisFixtures.make({
            container : $("#live-tenis-container"),
            mode : "sport",
            sportName : "Tenis",
            sportId : "24",
            live : true,
            expand : true,
            take: 5
        });

        $("#live-container").removeClass("hidden");

        var container = $("#sportsMenu-live-container");

        if ((container.html() === ""))
            LiveSportsMenu.make({
                container: container,
                live: true,
                markets: true
            });

        LiveSportsMenu.selectFixture(-1);

        next();
    }

    function liveMarkets (ctx, next) {
        mode = "live";

        var fixtureId = ctx.params.fixtureId;

        var container = $("#sportsMenu-live-container");

        if (container.html() === "")
            LiveSportsMenu.make({
                container: container,
                live: true,
                markets: true,
            });

        LiveSportsMenu.selectFixture(fixtureId);

        Markets.make({
            fixtureId: fixtureId,
            live: true,
            container: $("#liveMarkets-container")
        });

        ScoreCenter.make({
            container: $("#match-container"),
            fixtureId: fixtureId,
            sportId: 10
        });

        next();
    }

    function favorites(ctx, next)
    {
        mode = "";

        Breadcrumb.make({mode: "favorites"});

        $("#breadcrumb-container").removeClass("hidden");
        $("#favorites-container").removeClass("hidden");

        var hasNoFavorites = Favorites.games().length === 0;

        MiddleAlert.make({
            msg: "<p>Não existem favoritos.</p><p>Por favor selecione alguns.</p>",
            liveEmpty: hasNoFavorites,
            prematchEmpty: hasNoFavorites
        });

        LiveFavoritesFixtures.make({
            mode: "favorites",
            live: true,
            container: $("#favorites-live-container")
        });

        FavoritesFixtures.make({
            mode: "favorites",
            live: false,
            container: $("#favorites-prematch-container")
        });


        next();
    }

    function casino(ctx, next) {

        if (!!window.casinoAvailable) {
            page.stop();

            if (window.location.pathname !== ctx.path)
                window.location.href = ctx.path;

            next();
            return;
        }

        mode = "casino";

        Breadcrumb.make({mode: "title", title: "Casino"});

        MiddleAlert.make({
            msg: "<p>Brevemente disponível.</p>",
            liveEmpty: true,
            prematchEmpty: true
        });

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

        MiddleAlert.make({
            msg: "<p>Não existem resultados.</p><p>Por favor refine a pesquisa.</p>",
            liveEmpty: false,
            prematchEmpty: false
        });

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
            live: false,
            container: $("#search-prematch-container"),
            query: query
        });

        $("#breadcrumb-container").removeClass("hidden");
        $("#search-container").removeClass("hidden");

        next();
    }

    function register(ctx, next)
    {
        Register.make(ctx, next);

        $("#register-container").removeClass("hidden");

        next();
    }

    function exitRegister(ctx, next) {
        next();
    }

    function info(ctx, next)
    {
        Info.make(ctx.params.term, ctx.querystring);

        $("#info-container").removeClass("hidden");

        next();
    }

    function perfil(page) {
        return function (ctx, next)
        {
            if (page) Helpers.updateOptions({page: page}, ctx.params);
            if (ctx.params.sub === 'historico') return next();

            Perfil.make(ctx.params);

            $("#perfil-container").removeClass("hidden");

            next();
        };
    }
    function perfilHistorico(ctx, next) {
        PerfilHistory.make(ctx.params);

        $("#perfil-container").removeClass("hidden");

        next();
    }
    function exitPerfilHistorico(ctx, next) {
        PerfilHistory.unload();
        next();
    }
    function exitPerfil(ctx, next) {
        Perfil.unload();
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

    function promotions(ctx, next) {
        Store.commit('promotions/setVisible', true);
        next();
    }

});
