$(function() {

    var mode = "";

    var sportsPage = false;


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

    page('*', pageMode);

    page();


    function allowed (ctx, next)
    {
        if (/((\/$)|(\/info.*))|(\/pesquisa.*)|(\/direto.*)|(\/desporto.*)|(\/favoritos)/.test(ctx.path)) {
            next();

            return;
        }

        window.location = ctx.path;
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
            operation: "highlights"
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

        var options = SportsMenu.competitionInfo(competitionId);
        options["operation"] = "competition";
        options["mode"] = "competition";
        options["competitionId"] = competitionId;
        options["container"] = $("#fixtures-container");

        Breadcrumb.make(options);
        SportsFixtures.make(options);

        $("#breadcrumb-container").removeClass("hidden");
        $("#fixtures-container").removeClass("hidden");

        next();
    }


    function markets(ctx, next)
    {
        mode = "sports";

        var fixtureId = ctx.params.fixtureId;

        Breadcrumb.make({
            fixtureId: fixtureId,
            operation: "markets"
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

        if (LiveMenu.loaded())
            page('/direto/mercados/' + LiveMenu.selected());
        else
            LiveMenu.make({markets: true});

        $("#match-container").removeClass("hidden");
        $("#liveMarkets-container").removeClass("hidden");

        next();
    }


    function liveMarkets (ctx, next) {
        mode = "live";

        var fixtureId = ctx.params.fixtureId;

        if (LiveMenu.loaded())
            LiveMenu.selected(fixtureId);
        else
            LiveMenu.make();

        var options = {
            fixtureId: fixtureId,
            live: true,
            container: $("#liveMarkets-container")
        };

        Markets.make(options);

        var matchContainer = $("#match-container");

        matchContainer.prop("src","https://coolbet.betstream.betgenius.com/betstream-view/footballscorecentre/coolbetfootballscorecentre/html?eventId=" + fixtureId);

        matchContainer.removeClass("hidden");
        $("#liveMarkets-container").removeClass("hidden");

        next();
    }

    function favorites(ctx, next)
    {
        mode = "";

        Breadcrumb.make({operation: "favorites"});

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
            "operation": "search",
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

});

Handlebars.registerPartial('breadcrumb', '\
    <div class="breadcrumb">\
        {{#if_in operation "favorites"}}\
            <span class="selected">Favoritos</span>\
        {{/if_in}}\
        {{#if_eq operation "search"}}\
            Pesquisa &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="selected">{{query}}</span>\
        {{/if_eq}}\
        {{#if_eq operation "highlights"}}\
            Destaques &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="selected">{{competition}}</span>\
        {{/if_eq}}\
        {{#if_in operation "competition,markets"}}\
            {{sport}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            {{region}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="selected">{{competition}}</span>\
        {{/if_in}}\
    </div>\
');
var Breadcrumb = new (function ()
{
    var options = {};

    var container = $("#breadcrumb-container");

    this.make = function (_options)
    {
        make(_options);

        return this;
    };

    function make(_options)
    {
        update(_options);

        if ((options.operation == "markets")
            || ((options.operation == "competition" || options.operation == "highlights") && !options.competition)) {
            fetch();

            return;
        }

        render();
    }

    function fetch()
    {
        container.html('<div style="position: relative; left: -20px; height: 120px;" class="spinner"></div>');

        new Spinner().spin(container.find(".spinner").get(0));

        $.get(ODDS_SERVER +
            "fixtures?" + mode() +
            "&with=sport,competition.region",
            "&take=1"
        ).done(render);
    }

    function update(_options)
    {
        for (var i in _options)
            options[i] = _options[i];
    }

    function render(data)
    {
        if (data && data.fixtures && data.fixtures.length) {
            var fixture = data.fixtures[0];

            options.competition = fixture.competition.name;
            options.region = fixture.competition.region.name;
            options.sport = fixture.sport.name;
        }

        container.html(Template.apply('breadcrumb', options));
    }

    function mode()
    {
        return (options.operation == "markets") ? "ids=" + options.fixtureId : "competition=" + options.competitionId;
    }
});

Handlebars.registerPartial('info', '\
    <div class="info">\
        <div class="header">SUPORTE \
            <i id="info-close" class="fa fa-times"></i>\
            <i id="info-print" class="fa fa-print"></i>\
            </div>\
        <div class="links">\
            <div class="links-content">\
                <a id="info-sobre_nos" href="/info/sobre_nos" class="link">Sobre Nós <i class="fa fa-plus"></i></a>\
                <a id="info-termos_e_condicoes" href="/info/termos_e_condicoes" class="link">Termos e Condições <i class="fa fa-plus"></i></a>\
                <a id="info-politica_privacidade" href="/info/politica_privacidade" class="link">Politica de Privacidade <i class="fa fa-plus"></i></a>\
                <a id="info-faq" href="/info/faq"class="link last">FAQ <i class="fa fa-plus"></i></a>\
                <a id="info-bonus_e_promocoes" href="/info/bonus_e_promocoes" class="link">Bónus e Promoções <i class="fa fa-plus"></i></a>\
                <a id="info-pagamentos" href="/info/pagamentos" class="link">Pagamentos <i class="fa fa-plus"></i></a>\
                <a id="info-jogo_responsavel" href="/info/jogo_responsavel" class="link">Jogo Responsável <i class="fa fa-plus"></i></a>\
                <a id="info-contactos" href="/info/contactos" class="link last">Contactos <i class="fa fa-plus"></i></a>\
            </div>\
        </div>\
        <div id="info-content" class="content">&nbsp;</div>\
    </div>\
');

var Info = new (function () {

    var defaultTerm = "sobre_nos";

    var term = defaultTerm;

    var terms = {
        "sobre_nos": '/textos/sobre_nos',
        "termos_e_condicoes": '/textos/termos_e_condicoes',
        "contactos": '/textos/contactos',
        "bonus_e_promocoes":  '/textos/bonus_e_promocoes',
        "faq": '/textos/faq',
        "pagamentos": '/textos/pagamentos',
        "politica_privacidade": '/textos/politica_priv',
        "jogo_responsavel": '/textos/jogo_responsavel'
    };

    init();

    function init()
    {
        $("#info-container").html(Template.apply("info"));

        $("#info-close").click(closeClick);

        $("#info-print").click(printClick);
    }

    this.make = function (_term)
    {
        make(_term);
    };

    function make(_term)
    {
        term = (terms[_term] ? _term : defaultTerm);

        select(term);

        fetch();
    }

    function fetch()
    {
        $.get(terms[term]).done(render);
    }

    function render(data)
    {
        var content = "";

        for (var i in data) {
            content = data[i];
            break;
        }

        $("#info-content").html(content);
    }

    function select(term)
    {
        var links = $("#info-container").find(".links-content").find(".link");

        links.removeClass("selected");

        var icons = links.find("i");

        icons.addClass("fa-plus");
        icons.removeClass("fa-caret-down");

        var link = $("#info-" + term);

        link.addClass("selected");

        link.find("i").addClass("fa-caret-down");
    }

    function closeClick()
    {
        page('/');
    }

    function printClick()
    {

        $("#info-content").print({
            addGlobalStyles : false,
            stylesheet : null,
            rejectWindow : true,
            noPrintSelector : ".no-print",
            iframe : true,
            append : null,
            prepend : null
        });
    }
});

Handlebars.registerPartial('sports_menu', '\
    <ul>\
        {{#each sports}}\
            <li class="level1">\
                <div class="menu1-option sportsMenu-box-sport" data-sport-id="{{id}}" data-sport-name="{{name}}">\
                    <span class="sportsMenu-text sport expand">&nbsp;<i class="i1 fa fa-plus sportsMenu-icon-sport expand"></i></span>\
                    <span class="n1 sportsMenu-text-sport"><i class="fa fa-futbol-o" aria-hidden="true"></i> &nbsp; {{this.name}}</span>\
                </div>\
                <ul></ul>\
            </li>\
        {{/each}}\
    </ul>\
');

Handlebars.registerPartial('regions_submenu','\
    {{#each regions}}\
        <li class="level2"> \
            <div class="menu2-option sportsMenu-box menu region" data-region-id="{{id}}" data-region-name="{{name}}">\
                <span class="sportsMenu-text-region count">{{this.competition_count}}</span>\
                <i class="i2 fa fa-caret-down sportsMenu-icon-region-selected hidden"></i>\
                <span class="n2 sportsMenu-text-region">{{this.name}}</span>\
            </div>\
            <ul></ul>\
        </li>\
    {{/each}}\
');

Handlebars.registerPartial('competitions_submenu','\
    {{#each competitions}}\
        <li class="level3" data-competition-id="{{this.id}}" data-competition-name="{{name}}">\
            <div class="menu3-option sportsMenu-box menu competition">\
                <span class="sportsMenu-text competition expand">&nbsp;<i class="i3 fa fa-caret-right hidden"></i></span>\
                <span class="n3 sportsMenu-text competition">{{this.name}}</span>\
            </div>\
        </li>\
    {{/each}}\
');

Handlebars.registerPartial('highlights_submenu','\
    {{#each competitions}}\
        <div class="sportsMenu-box-highlights-submenu" data-competition-id="{{id}}" data-competition-name="{{name}}" data-type="highlight">{{name}}</div>\
    {{/each}}\
');
Handlebars.registerPartial('fixtures', '\
    <table class="fixtures">\
        <tr class="header {{options.mode}}">\
            <th class="date">\
                {{#if_eq options.mode "sport"}}\
                    <i class="fa fa-futbol-o" aria-hidden="true"></i>\
                {{/if_eq}}\
            </th>\
            <th class="game">{{options.sportName}}</th>\
            <th class="{{#if options.live}}live{{/if}}" colspan="2">{{#if options.live}}DIRETO{{/if}}</th>\
            <th class="separator">&nbsp;</th>\
            <th class="selection">1</th>\
            <th class="selectionSeparator"></th>\
            <th class="selection">X</th>\
            <th class="selectionSeparator"></th>\
            <th class="selection">2</th>\
            <th class="separator">&nbsp;</th>\
            <th class="marketCount">&nbsp;</th>\
        </tr>\
        {{#each fixtures}}\
            <tr class="fixture">\
                <td class="date {{parity @index}}">{{date}}<br>{{time}}</td>\
                <td class="game {{parity @index}}" data-game-id="{{id}}" data-type="fixture">{{name}}</td>\
                <td class="favorite {{parity @index}}">{{> favorite}}</td>\
                <td class="statistics {{parity @index}}">{{> statistics}}</td>\
                <td class="separator">&nbsp;</td>\
                {{#each markets}}\
                    {{#if_in market_type_id "2,306"}}\
                        {{> get_selection outcomeId=1 fixture=.. index=@../index}}\
                    {{/if_in}}\
                    {{#if_eq market_type_id 322}}\
                        {{> get_selection outcomeId=25 fixture=.. index=@../index}}\
                    {{/if_eq}}\
                    <td class="separator"></td>\
                        {{> get_selection outcomeId=2 fixture=.. index=@../index}}\
                    <td class="separator"></td>\
                    {{#if_in market_type_id "2,306"}}\
                        {{> get_selection outcomeId=3 fixture=.. index=@../index}}\
                    {{/if_in}}\
                    {{#if_eq market_type_id 322}}\
                        {{> get_selection outcomeId=26 fixture=.. index=@../index}}\
                    {{/if_eq}}\
                {{/each}}\
                <td class="separator">&nbsp;</td>\
                <td class="marketsCount {{parity @index}}" data-game-id="{{id}}" data-type="fixture">+{{markets_count}}</td>\
            </tr>\
        {{/each}}\
    </table>\
');

Handlebars.registerPartial('get_selection', '\
    <td class="selection {{type}} {{parity index}}">\
        {{#each selections}}\
            {{#if_eq outcome.id ../outcomeId}}\
                {{> selection fixture=../fixture market=..}}\
            {{/if_eq}}\
        {{/each}}\
    </td>\
');

Handlebars.registerPartial('get_selection_name', '\
    {{#each selections}}\
        {{#if_eq outcome.id ../outcome}}\
            {{name}}\
        {{/if_eq}}\
    {{/each}}\
');

Handlebars.registerPartial('selection', '\
    {{#if_eq trading_status "Trading"}}\
        <button class="selection-button"\
            data-game-id="{{fixture.id}}"\
            data-game-name="{{fixture.name}}"\
            data-game-date="{{fixture.start_time_utc}}"\
            data-event-id="{{id}}"\
            data-event-name="{{#if_eq market.market_type.is_handicap 1}}{{market.handicap}} - {{/if_eq}}{{name}}"\
            data-event-price="{{decimal}}"\
            data-market-id="{{market.id}}"\
            data-market-name="{{market.market_type.name}}"\
            data-type="odds">{{decimal}}</button>\
    {{/if_eq}}\
');

Handlebars.registerPartial('favorite', '\
    <button class="fa fa-star markets-button-favorite"\
        data-game-id="{{id}}"\
        data-game-name="{{name}}"\
        data-game-date="{{start_time_utc}}"\
        data-type="favorite"> \
    </button>\
');

Handlebars.registerPartial('statistics', '\
    <button id="statistics-{{id}}"\
        class="fa fa-bar-chart markets-button-statistics"\
        data-game-id="{{id}}"\
        data-game-name="{{name}}"\
        data-game-date="{{start_time_utc}}"\
        data-game-sport=""\
        data-selected-css="markets-text statistics selected"\
        data-type="statistics"> \
    </button>\
');

Handlebars.registerPartial('markets','\
    {{#each fixtures}}\
        <div class="markets">\
            {{#if_not ../live}}\
                <div class="header">\
                    <span>{{name}}</span>\
                    <i id="markets-close" class="fa fa-times close" aria-hidden="true"></i>\
                </div>\
            {{/if_not}}\
            {{> market_singleRow type=2 outcomes=../outcomes}}\
            {{> market_singleRow type=306 outcomes=../outcomes}}\
            {{> market_singleRow type=322 outcomes=../outcomes}}\
            {{> market_multiRow type=259 outcomes=../outcomes}}\
            {{> market_multiRow type=105 outcomes=../outcomes}}\
            {{> market_singleRow type=122 outcomes=../outcomes}}\
            {{> market_singleRow type=7202 outcomes=../outcomes}}\
            {{> market_singleRow type=25 outcomes=../outcomes}}\
            {{> market_singleRow type=60 outcomes=../outcomes}}\
            {{> market_singleRow type=62 outcomes=../outcomes}}\
            {{> market_singleRow type=104 outcomes=../outcomes}}\
            {{> market_singleRow type=169 outcomes=../outcomes}}\
            {{> market_singleRow type=6832 outcomes=../outcomes}}\
            {{> market_singleRow type=7591 outcomes=../outcomes}}\
            <div id="markets-others" class="hidden">\
            </div>\
            <div id="markets-more" class="markets-box more hidden">\
                <span class="markets-text more">Outras &nbsp; <i class="fa fa-plus" aria-hidden="true"></i></span>\
            </div>\
        </div>\
    {{/each}}\
');

Handlebars.registerPartial('market_singleRow','\
    {{#with (lookup (lookup this type) 0)}}\
        <div class="title">\
            {{market_type.name}}\
        </div>\
        <table>\
            {{> markets_headers type=../type outcomes=../outcomes}}\
            {{> market_selections type=../type fixture=.. index=@index}}\
        </table>\
    {{/with}}\
');

Handlebars.registerPartial('market_multiRow', '\
    {{#with (lookup this type)}}\
        <div class="title">\
            {{this.[0].market_type.name}}\
        </div>\
        <table>\
            {{#each this}}\
                {{#if_eq @index 0}}\
                    {{> markets_headers type=../../type fixture=../.. outcomes=../../outcomes}}\
                {{/if_eq}}\
                {{> market_selections type=../../type fixture=../.. index=@index}}\
            {{/each}}\
        </table>\
    {{/with}}\
');

Handlebars.registerPartial('markets_headers', '\
    <tr class="header">\
        {{#if_in type "2,306,6832,7591"}}\
            {{> market_header_triple outcome1=1 outcome2=2 outcome3=3}}\
        {{/if_in}}\
        {{#if_eq type 322}}\
            {{> market_header_double outcome1=25 outcome2=26}}\
        {{/if_eq}}\
        {{#if_in type "122,60,62,104,169"}}\
            {{> market_header_double outcome1=1 outcome2=3}}\
        {{/if_in}}\
        {{#if_eq type 7202}}\
            {{> market_header_triple outcome1=7 outcome2=8 outcome3=9}}\
        {{/if_eq}}\
        {{#if_eq type 25}}\
            {{> market_header_triple outcome1=27 outcome2=28 outcome3=29}}\
        {{/if_eq}}\
        {{#if_eq type 105}}\
            {{> market_header_triple outcome1=4 outcome2=5 outcome3=6}}\
        {{/if_eq}}\
        {{#if_eq type 259}}\
            {{> market_header_double outcome1=30 outcome2=31}}\
        {{/if_eq}}\
    </tr>\
');

Handlebars.registerPartial('market_selections', '\
    {{#if_in type "2,306,832,,6832,7591"}}\
        {{> market_selections_triple outcome1=1 outcome2=2 outcome3=3}}\
    {{/if_in}}\
    {{#if_in type "122,60,62,104,169"}}\
        {{> market_selections_double outcome1=1 outcome2=3}}\
    {{/if_in}}\
    {{#if_eq type 7202}}\
        {{> market_selections_triple outcome1=7 outcome2=8 outcome3=9}}\
    {{/if_eq}}\
    {{#if_eq type 25}}\
        {{> market_selections_triple outcome1=27 outcome2=28 outcome3=29}}\
    {{/if_eq}}\
    {{#if_eq type 322}}\
        {{> market_selections_double outcome1=25 outcome2=26}}\
    {{/if_eq}}\
    {{#if_eq type 259}}\
        {{> market_selections_double outcome1=30 outcome2=31}}\
    {{/if_eq}}\
    {{#if_eq type 105}}\
        {{> market_selections_triple outcome1=4 outcome2=5 outcome3=6}}\
    {{/if_eq}}\
');

Handlebars.registerPartial('market_header_triple', '\
    <th class="selection">{{> get_selection_name outcome=outcome1}}</th>\
    <th class="separator"></th>\
    <th class="selection">{{> get_selection_name outcome=outcome2}}</th>\
    <th class="separator"></th>\
    <th class="selection">{{> get_selection_name outcome=outcome3}}</th>\
');

Handlebars.registerPartial('market_selections_triple','\
    <tr class="row">\
        {{> get_selection outcomeId=outcome1 market=this type="triple"}}\
        <td class="separator"></td>\
        {{> get_selection outcomeId=outcome2 market=this type="triple"}}\
        <td class="separator"></td>\
        {{> get_selection outcomeId=outcome3 market=this type="triple"}}\
    </tr>\
');

Handlebars.registerPartial('market_header_double', '\
    <th class="selection"></th>\
    <th class="separator"></th>\
    <th class="selection">{{> get_selection_name outcome=outcome1}}</th>\
    <th class="separator"></th>\
    <th class="selection">{{> get_selection_name outcome=outcome2}}</th>\
');

Handlebars.registerPartial('market_selections_double','\
    <tr class="row">\
        <td class="handicap">{{#if_eq market_type.is_handicap 1}}{{handicap}}{{/if_eq}}</td>\
        <th class="separator"></th>\
        {{> get_selection outcomeId=outcome1 market=this type="double"}}\
        <td class="separator"></td>\
        {{> get_selection outcomeId=outcome2 market=this type="double"}}\
    </tr>\
');

Handlebars.registerPartial('betslip_simple' , '\
    <div id="betslip-simpleBet-box-{{id}}" class="betslip-box bet">\
        <div class="betslip-box row">\
            <button id="betslip-simpleBet-button-removeBet-{{id}}" class="betslip-button remove"><i class="fa fa-times" aria-hidden="true"></i></button>\
            <span class="betslip-text gameName">{{date}} - {{time}}<br>{{gameName}}</span>\
        </div>\
        <div class="betslip-box row">\
            <span class="betslip-text marketName">{{marketName}}</span>\
            <div class="pull-right">€ <input id="betslip-field-amount-{{id}}" type="text" class="betslip-field amount" value="{{amount}}" data-id="{{id}}"></div>\
        </div>\
        <div class="acenter">\
            <span id="simpleBet-text-error-{{id}}" class="betslip-text error"></span>\
        </div class="betslip-box row">\
        <div class="betslip-box row">\
            <span class="betslip-text eventName">{{name}}</span>\
            <span class="betslip-text odds">{{odds}}</span>\
        </div>\
        <div  class="betslip-box row">\
            <span class="betslip-text profit">Possível Retorno</span>\
            <span id="betslip-text-profit-{{id}}" class="betslip-text profit amount">€ {{multiply amount odds}}</span>\
        </div>\
    </div>\
');

Handlebars.registerPartial('betslip_multi' , '\
    <div id="betslip-multiBet-box-{{id}}" class="betslip-box event">\
        <div class="betslip-box row">\
            <button id="betslip-multiBet-button-removeBet-{{id}}" class="betslip-button remove"><i class="fa fa-times" aria-hidden="true"></i></button>\
            <span class="betslip-text gameName">{{date}} - {{time}}<br>{{gameName}}</span>\
        </div>\
        <div class="betslip-box row">\
            <span class="betslip-text marketName">{{marketName}}</span>\
        </div>\
        <div class="acenter">\
            <span id="multiBet-text-error-{{id}}" class="betslip-text error"></span>\
        </div>\
        <div class="betslip-box row">\
            <span class="betslip-text eventName">{{name}}</span>\
            <span class="betslip-text odds">{{odds}}</span>\
        </div>\
    </div>\
');

Handlebars.registerPartial('betslip_open_bets' , '\
    {{#each bets}}\
        <div class="betslip-box bet">\
            {{#each events}}\
                <div class="betslip-box row">\
                    <span class="betslip-text gameName">{{date}} - {{time}}<br>{{game_name}}</span>\
                    {{#if_eq ../type "simple"}}\
                        <span class="betslip-text amount">€ {{../amount}}</span>\
                    {{/if_eq}}\
                </div>\
                <div class="betslip-box row">\
                    <span class="betslip-text marketName">{{market_name}}</span>\
                </div>\
                <div class="betslip-box row">\
                    <span class="betslip-text eventName">{{event_name}}</span>\
                    <span class="betslip-text odds">\
                    {{#if_eq status "won"}}\
                        <span class="betslip-text win"><i class="fa fa-check-circle" aria-hidden="true"></i> &nbsp;</span>\
                    {{/if_eq}}\
                    {{odd}}\
                    </span>\
                </div>\
            {{/each}}\
            {{#if_eq type "multi"}}\
                <div class="betslip-box row">\
                    <span class="betslip-text amountLabel">Total Apostas</span>\
                    <span id="betslip-simpleProfit" class="betslip-text profit amount white"> € {{amount}}</span>\
                </div>\
                <div class="betslip-box row">\
                    <span class="betslip-text oddsLabel">Total Odds</span>\
                    <span id="betslip-multiOdds" class="betslip-text profit amount white">{{odd}}</span>\
                </div>\
            {{/if_eq}}\
            <div class="betslip-box row">\
                <span class="betslip-text profit white">Possível retorno</span>\
                <span class="betslip-text profit amount white">€ {{multiply amount odd}}</span>\
            </div>\
        </div>\
    {{/each}}\
');
Handlebars.registerPartial('liveMenu_sports', '\
    <ul>\
        {{#each sports}}\
            <li class="level1">\
                <div class="sport" data-sport-id="{{id}}" data-sport-name="{{name}}">\
                    <i class="fa fa-plus expand"></i>\
                    <i class="fa fa-futbol-o" aria-hidden="true"></i> &nbsp; {{this.name}}\
                </div>\
                <ul></ul>\
            </li>\
        {{/each}}\
    </ul>\
');

Handlebars.registerPartial('liveMenu_regions','\
    {{#each regions}}\
        <li class="level2"> \
            <div class="region" data-region-id="{{id}}" data-region-name="{{name}}">\
                <span class="count">{{this.fixtures_count}}</span>\
                <i class="fa fa-caret-down collapse hidden"></i>\
                {{this.name}}\
            </div>\
            <ul></ul>\
        </li>\
    {{/each}}\
');

Handlebars.registerPartial('liveMenu_fixtures','\
    {{#each fixtures}}\
        <li class="level3" data-game-id="{{this.id}}" data-game-name="{{name}}">\
            <table>\
                <tr>\
                    <td class="favorite">{{> favorite}}</td>\
                    <td class="fixture" data-game-id="{{id}}" data-mode="live">{{this.name}}</td>\
                </tr>\
            </table>\
        </li>\
    {{/each}}\
');
var SportsMenu = new (function()
{
    var until;

    init();

    function init()
    {
        until = encodeURIComponent(moment.utc().add(1, "years").format());

        new Spinner().spin(document.getElementById("sportsSpinner"));

        new Spinner().spin(document.getElementById("highlightsSpinner"));

        $("#sportsMenu-button-live").click(liveClick);

        $("#sportsMenu-button-prematch").click(prematchClick);

        $("#sportsMenu-interval").click(intervalClick);

        $(".sportsMenu-container div[data-interval]").click(intervalOptionClick);

        make();
    };

    function intervalClick()
    {
        var expand = $(this).find("i");

        expand.toggleClass("fa-plus");
        expand.toggleClass("fa-caret-down");
        expand.toggleClass("collapse");

        $(this).toggleClass("selected");

        $("#sportsMenu-interval-content").toggleClass("hidden");
    }

    function intervalOptionClick()
    {
        $("#sportsMenu-interval-text").html($(this).html());

        var interval = $(this).data("interval");

        var until = (interval == "today") ?
            moment.utc().add(1, 'days').hours(0).minutes(0).seconds(0).format() :
            moment.utc().add(interval, "hours").format();

        until = encodeURIComponent(until);

        Fixtures.make({until: until});

        $("#sportsMenu-interval-content").toggleClass("hidden");
    }

    function make()
    {
        fetchSports();

        loaded = true;
    }

    this.make = function()
    {
        make();
    };

    function fetchSports ()
    {
        $.get(ODDS_SERVER + "sports")
            .done(renderSports);
    }

    function renderSports (data)
    {
        $("#sportsMenu-popular").html(Template.apply("sports_menu", data));

        $("#sportsMenu-prematch-container").find(".menu1-option").click(sportClick);
    }

    function sportClick ()
    {
        var containerEmpty = ($(this).next().html() === "");

        var sportId = $(this).data("sport-id");

        if (containerEmpty)
            fetchRegions(sportId);

        toggleSport.call(this);
    }

    function toggleSport ()
    {
        $(this).toggleClass("selected");
        $(this).parent().find(".level2").toggleClass("hidden");

        var expand = $(this).find("span i");

        expand.toggleClass("fa-plus");
        expand.toggleClass("fa-caret-down");
        expand.toggleClass("collapse");
    }

    function fetchRegions (sportId)
    {
        $.get("http://genius.ibetup.eu/regions?sport=" + sportId + "&until=" + until)
            .done(function (data) {renderRegions(data, sportId)})
    }

    function renderRegions (data, sportId) {
        var container = $("#sportsMenu-popular").find("div[data-sport-id=" + sportId + "]").next();

        container.html(Template.apply("regions_submenu", data));

        regionsClick(container, sportId);
    }

    function regionsClick(container, sportId)
    {
        container.find(".menu2-option")
            .click(function () {regionClick.call(this, sportId)});
    }

    function regionClick(sportId)
    {
        var containerEmpty = $(this).next().html() === "";

        if (containerEmpty && $(this).hasClass("selected"))
            return;

        var regionId = $(this).data("region-id");

        if (containerEmpty)
            fetchCompetitions(sportId, regionId);

        toggleRegion.call(this);
    }

    function toggleRegion() {
        $(this).parent().find(".level3").toggleClass("hidden");
        $(this).find(".i2").toggleClass("hidden");
        $(this).find(".sportsMenu-text-region.count").toggleClass("hidden");
        $(this).find(".n2").toggleClass("selected");
    }

    function fetchCompetitions(sportId, regionId)
    {
        $.get("http://genius.ibetup.eu/competitions?sport=" + sportId + "&region=" + regionId)
            .done(function (data) {renderCompetitions(data, sportId, regionId)});
    }

    function renderCompetitions(data, sportId, regionId)
    {
        var container = $("#sportsMenu-popular").find("div[data-sport-id=" + sportId + "]").next()
            .find("div[data-region-id=" + regionId + "]").next();

        container.html(Template.apply('competitions_submenu', data));

        $(container).find(".menu3-option").click(competitionClick);
    }

    function competitionClick()
    {
        $(".n3").removeClass("selected");
        $(".i3").addClass("hidden");
        $(this).find(".i3").removeClass("hidden");
        $(this).find(".n3").addClass("selected");

        page('/desportos/competicao/' + $(this).parent().data('competition-id'));
    }

    this.competitionInfo = function (competitionId)
    {
        var competition = $("#sportsMenu-popular").find("li[data-competition-id="+competitionId+"]");
        var region = competition.parent().prev();
        var sport = region.parent().parent().prev();

        return {
            competition : competition.data("competition-name"),
            region : region.data("region-name"),
            sport : sport.data("sport-name")
        };
    };


    this.makeHighlights = function(highlights)
    {
        fetchHighlights(highlights);
    };

    function fetchHighlights(highligths)
    {
        if (highligths.length == 0) {
            $("#highlights-container").html("");

            return;
        }

        $.get("http://genius.ibetup.eu/competitions?ids=" + highligths.join(','))
            .done(renderHighlights)
    }

    function renderHighlights(data)
    {
        var container = $("#sportsMenu-highlights");

        container.html(Template.apply('highlights_submenu', data));

        container.find("div[data-type=highlight]").click(highlightClick);
    }

    function highlightClick()
    {
        page('/desportos/destaque/' + $(this).data("competition-id"));
    }

    function liveClick()
    {
        page('/direto');
    }

    function prematchClick()
    {
        page('/desportos');
    }

})();

var LiveMenu = new (function () {

    var options = {};

    var loaded = false;

    var selectedFixture;

    init();

    function init()
    {
        // make();
    }

    function make()
    {
        fetchSports();
    }

    this.make = function (_options)
    {
        update(_options);

        make();
    };

    this.loaded = function()
    {
        return loaded;
    };

    this.selected = function(fixtureId)
    {
        if (fixtureId) {
            var container = $("#sportsMenu-live-container");

            container.find("td[data-game-id]").removeClass("selected");
            container.find("td[data-game-id=" + fixtureId + "]").addClass("selected");
        }

        return selectedFixture;
    };

    function fetchSports ()
    {
        $.get(ODDS_SERVER + "sports?live")
            .done(renderSports);
    }


    function renderSports (data)
    {
        if (!data.sports.length)
            return;

        var container = $("#sportsMenu-live-container");

        container.html(Template.apply("liveMenu_sports", data));

        var sports = container.find(".level1 > .sport");

        sports.click(sportClick);

        if (!loaded)
            sports.first().click();

    }

    function sportClick ()
    {
        var containerEmpty = ($(this).next().html() === "");

        var sportId = $(this).data("sport-id");

        if (containerEmpty)
            fetchRegions(sportId);

        toggleSport.call(this);
    }

    function toggleSport ()
    {
        $(this).toggleClass("selected");
        $(this).parent().find(".level2").toggleClass("hidden");

        var expand = $(this).children(".expand");

        expand.toggleClass("fa-plus");
        expand.toggleClass("fa-caret-down");
        expand.toggleClass("collapse");
    }

    function fetchRegions (sportId)
    {
        $.get("http://genius.ibetup.eu/regions?sport=" + sportId + "&live&fixturesCount")
            .done(function (data) {renderRegions(data, sportId)})
    }

    function renderRegions (data, sportId) {
        var container = $("#sportsMenu-live-container").find("div[data-sport-id=" + sportId + "]").next();

        container.html(Template.apply("liveMenu_regions", data));

        regionsClick(container, sportId);
    }

    function regionsClick(container, sportId)
    {
        var regions = container.find(".level2 > .region");

        regions.click(function () {regionClick.call(this, sportId)});

        if (!loaded)
            regions.first().click();
    }

    function regionClick(sportId)
    {
        var containerEmpty = $(this).next().html() === "";

        if (containerEmpty && $(this).hasClass("selected"))
            return;

        var regionId = $(this).data("region-id");

        if (containerEmpty)
            fetchFixtures(sportId, regionId);

        toggleRegion.call(this);
    }

    function toggleRegion() {
        $(this).parent().find(".level3").toggleClass("hidden");
        $(this).find(".collapse").toggleClass("hidden");
        $(this).find(".count").toggleClass("hidden");
    }

    function fetchFixtures(sportId, regionId)
    {
        $.get("http://genius.ibetup.eu/fixtures?sport=" + sportId + "&region=" + regionId + "&live")
            .done(function (data) {renderFixtures(data, sportId, regionId)});
    }

    function renderFixtures(data, sportId, regionId)
    {
        var container = $("#sportsMenu-live-container").find("div[data-sport-id=" + sportId + "]").next()
            .find("div[data-region-id=" + regionId + "]").next();

        container.html(Template.apply('liveMenu_fixtures', data));

        Favorites.apply();

        var fixtures = $(container).find(".fixture");

        fixtures.click(fixtureClick);

        if (!loaded) {
            loaded = true;
            if (options.markets)
                fixtures.first().click();
        }

        $(container).find("button[data-type=favorite]").click(favoriteClick);
    }

    function favoriteClick()
    {
        Favorites.toggle.call(this);
    }

    function fixtureClick()
    {
        $("#sportsMenu-live-container").find("table").removeClass("selected");
        $("#sportsMenu-live-container").find("table .fixture").removeClass("selected");
        $(this).parent().parent().parent().addClass("selected");
        $(this).addClass("selected");
        $(".i3").addClass("hidden");

        selectedFixture = $(this).data("game-id");

        page('/direto/mercados/' + selectedFixture);
    }

    function update(_options)
    {
        for (var i in _options)
            options[i] = _options[i];
    }

});

function Fixtures(_options)
{
    var options = {mode: "competition"};

    var market_types = "2,306,322,259,105,122,7202,25,60,62,104,169,6832,7591";

    init(_options);

    function init(_options)
    {
        update(_options);
    }

    function make(_options)
    {
        update(_options);

        fetch();
    }

    function update(_options)
    {
        for (var i in _options)
            options[i] = _options[i];
    }

    function fetch()
    {
        $.get(ODDS_SERVER + "fixtures?" +
            mode() +
            "&marketType=2,306,322&orderBy=start_time_utc,asc" +
            live() +
            until() +
            "&marketsCount=" + market_types +
            take()
        ).done(render);
    }

    function render(data)
    {
        var container = options.container;

        if (!data.fixtures.length) {
            container.html("");

            return;
        }

        fixturesData(data);

        data.options = options;

        container.html(Template.apply("fixtures", data));

        container.find("[data-type='fixture']").click(fixtureClick);

        container.find("[data-type='odds']").click(selectionClick);

        container.find("[data-type='favorite']").click(favoriteClick);

        Betslip.applySelected(container);

        Favorites.apply();
    }

    function fixturesData(data)
    {
        var fixtures = data.fixtures;

        for (var i in fixtures) {
            fixtures[i].date = moment.utc(fixtures[i]['start_time_utc']).local().format("DD MMM");
            fixtures[i].time = moment.utc(fixtures[i]['start_time_utc']).local().format("HH:mm");
        }
    }

    function mode()
    {
        switch (options.mode) {
            case "sport":
                return "sport=" + options.sportId;
            case "competition":
                return "competition=" + options.competitionId;
            case "favorites":
                return favorites();
            case "search":
                return "query=" + options.query;
        }
    }

    function live()
    {
        return options.live ? "&live" : "";
    }

    function until()
    {
        if (options.mode != "competition")
            return "";

        return "&until=" + (options.until ? options.until : encodeURIComponent(moment.utc().add(1, "years").format()));
    }

    function take()
    {
        return options.take ? "&take=" + options.take : "&take=20";
    }

    function fixtureClick()
    {
        if (options.live) {
            page('/direto/mercados/' + $(this).data("game-id"));

            return;
        }

        page('/desportos/mercados/' + $(this).data("game-id"));
    }

    function selectionClick()
    {
        Betslip.toggle.call(this, {
            id: $(this).data("event-id"),
            name: $(this).data("event-name"),
            odds: $(this).data("event-price"),
            marketId: $(this).data("market-id"),
            marketName: $(this).data("market-name"),
            gameId: $(this).data("game-id"),
            gameName: $(this).data("game-name"),
            gameDate: $(this).data("game-date"),
            amount: 0
        });
    }

    function favoriteClick()
    {
        Favorites.toggle.call(this);
    }

    function favorites()
    {
        var favorites = [];

        var games = Favorites.games();

        for (var i in games)
            favorites.push(games[i].id);

        return "ids=" + favorites.join(',');
    }

    this.make = function (_options)
    {
        make(_options);
    };

};

SportsFixtures = new Fixtures();

LiveFixtures = new Fixtures();
TennisFixtures = new Fixtures();

LiveFavoritesFixtures = new Fixtures();
FavoritesFixtures = new Fixtures();

LiveSearchFixtures = new Fixtures();
SearchFixtures = new Fixtures();

var Markets = new (function ()
{
    var options = {};

    var outcomes = {};

    var market_types = "2,306,322,259,105,122,7202,25,60,62,104,169,6832,7591";

    function make(_options)
    {
        update(_options);

        fetch();
    }

    this.make = function(_options)
    {
        make(_options);
    };


    function fetch()
    {
        $.get(ODDS_SERVER + "fixtures?ids=" + options.fixtureId +
            "&withMarketTypes=" + market_types
            + live()
        ).done(render);
    }


    function live()
    {
        return options.live ? "&live" : "";
    }

    function render(data)
    {
        headerData(data);

        fixturesData(data, true);

        var container = options.container;

        container.html(Template.apply('markets', data));

        container.find("[data-type='odds']").click(selectionClick);

        Betslip.applySelected(container);

        $("#markets-close").click(closeClick);
    }

    function fixturesData(data)
    {
        var fixtures = data.fixtures;

        for (var i in fixtures)
            fixtureData(fixtures[i]);

        data.outcomes = outcomes;
    }

    function fixtureData(fixture)
    {
        fixture.date = moment.utc(fixture['start_time_utc']).local().format("DD MMM");
        fixture.time = moment.utc(fixture['start_time_utc']).local().format("HH:mm");

        outcomesFomFixture(fixture);

        fixtureMarkets(fixture);
    }

    function fixtureMarkets(fixture) {
        var markets = fixture.markets;

        for (var i in markets) {
            var market = markets[i];

            if (!fixture[market.market_type_id])
                fixture[market.market_type_id] = [];

            fixture[market.market_type_id].push(market);
        }
    }

    function outcomesFomFixture(fixture)
    {
        var markets = fixture.markets;

        for (var i in markets)
            outcomesFromMarket(markets[i]);

        return outcomes;
    }

    function outcomesFromMarket(market)
    {
        var selections = market.selections;

        for (var i in selections) {
            var outcome = selections[i].outcome;

            if (outcome)
                outcomes[outcome.id] = outcome.name;
        }
    }

    function applySelected(container)
    {
        var bets = Betslip.bets();

        for (var i in bets)
            container.find("[data-event-id='" + bets[i].id + "']").addClass("selected");
    }

    function headerData(data)
    {
        data.live = options.live;
        data.sport = options.sport;
        data.region = options.region;
        data.competition = options.competition;
        data.now = moment().format("DD MMM HH:mm");
    }

    function selectionClick()
    {
        Betslip.toggle.call(this, {
            id: $(this).data("event-id"),
            name: $(this).data("event-name"),
            odds: $(this).data("event-price"),
            marketId: $(this).data("market-id"),
            marketName: $(this).data("market-name"),
            gameId: $(this).data("game-id"),
            gameName: $(this).data("game-name"),
            gameDate: $(this).data("game-date"),
            amount: 0
        });
    }

    function closeClick ()
    {
        if (history.length) {
            history.back();

            return;
        }

        page('/');
    }

    function update(_options)
    {
        for (var i in _options)
            options[i] = _options[i];
    }

})();

var Betslip = new (function () {

    var bets = [];

    var betMode = "simple";

    var multiAmount = 0;

    init();

    function init()
    {
        restore();

        $("#betslip-bulletinTab").click(bulletinClick);

        $("#betslip-openBetsTab").click(openBetsClick);

        $("#betslip-simpleTab").click(simpleClick);

        $("#betslip-multiTab").click(multiClick);

        $("#betslip-multiAmount").on("keyup", multiAmountChange);

        $(window).unload(persistBets);

        $("#betslip-submit").click(submit);

        $("#betslip-login").click(login);
    }

    function bulletinClick()
    {
        $("#betslip-bulletinTab").addClass("selected");
        $("#betslip-openBetsTab").removeClass("selected");

        $("#betslip-bulletinContainer").removeClass("hidden");
        $("#betslip-openBetsContainer").addClass("hidden");
    }

    function openBetsClick()
    {
        $.get("/open-bets").done(renderOpenBets);
    }

    function renderOpenBets(data)
    {
        if (data.bets.length == 0)
            return;

        openBetsData(data);

        $("#betslip-openBetsContainer").html(Template.apply('betslip_open_bets', data));


        $("#betslip-bulletinTab").removeClass("selected");
        $("#betslip-openBetsTab").addClass("selected");

        $("#betslip-bulletinContainer").addClass("hidden");
        $("#betslip-openBetsContainer").removeClass("hidden");
    }

    function openBetsData(data)
    {
        var bets = data.bets;

        for (var i in bets)
            openEventsData(bets[i].events);
    }

    function openEventsData(events)
    {
        for (var i in events)
            dateAndTime(events[i], 'game_date');
    }

    function dateAndTime(event, fieldName)
    {
        event.date = moment.utc(event[fieldName]).local().format("DD MMM");
        event.time = moment.utc(event[fieldName]).local().format("HH:mm");
    }

    this.toggle = function (bet) {
        var index = find(bet.id);

        if (index > -1) {
            remove(index);

            return;
        }

        add(bet);
    };

    this.bets = function()
    {
        return bets;
    };

    function add(bet)
    {
        if (!canAdd(bet))
            return;

        $("button[data-event-id='" + bet.id + "']").addClass("selected");

        bets.push(bet);

        renderBet(bet);

        selectBetMode("add");

        updateFooters();
    }

    function renderBet(bet)
    {

        betData(bet);

        $("#betslip-simpleContent").append(Template.apply("betslip_simple", bet));

        $("#betslip-simpleBet-button-removeBet-" + bet.id).click(function () {remove(find(bet.id))});

        $("#betslip-field-amount-" + bet.id).on("keyup", function () {simpleAmountChange.call(this, bet)});

        $("#betslip-multiBets-content").append(Template.apply('betslip_multi', bet));

        $("#betslip-multiBet-button-removeBet-" + bet.id).click(function () {remove(find(bet.id))});

    }

    function betData(bet)
    {
        dateAndTime(bet, 'gameDate');
    }

    function simpleAmountChange(bet)
    {
        $(this).val(parseAmount($(this).val()));

        bets[find(bet.id)].amount = $(this).val();

        $("#betslip-text-profit-" + bet.id).html("€ " + (bet.amount * bet.odds).toFixed(2));

        updateSimpleFooter();
    }

    function multiAmountChange()
    {
        var amount = parseAmount($(this).val());

        $(this).val(amount);

        multiAmount = amount;

        updateMultiFooter();
    }

    function updateFooters()
    {
        updateSimpleFooter();
        updateMultiFooter();
    }

    function updateSimpleFooter()
    {
        var total = 0;
        var profit = 0;

        for (var i in bets) {
            total += bets[i].amount*1;
            profit += bets[i].amount*bets[i].odds;
        }

        $("#betslip-simpleCount").html(bets.length);
        $("#betslip-simpleTotal").html("€ " + total.toFixed(2));
        $("#betslip-simpleProfit").html("€ " + profit.toFixed(2));
    }

    function updateMultiFooter()
    {
        var totalOdds = 1;

        for (var i = 0; i < bets.length; i++)
            totalOdds *= bets[i].odds;

        $("#betslip-multiOdds").html(totalOdds.toFixed(2));
        $("#betslip-multiProfit").html("€ " + (multiAmount*totalOdds).toFixed(2));
    }

    function find(value, fieldName)
    {
        var field = fieldName?fieldName:"id";

        for (var i = 0; i < bets.length; i++)
            if (bets[i][field] == value)
                return i;
        return -1;
    }

    function has(value, fieldName)
    {
        return find(value, fieldName) > -1;
    }

    function remove(index)
    {
        var bet = bets[index];

        $("button[data-event-id='" + bet.id + "']").removeClass("selected");

        $("#betslip-simpleBet-box-" + bet.id).remove();
        $("#betslip-multiBet-box-" + bet.id).remove();

        bets.splice(index, 1);

        selectBetMode();

        updateFooters();
    }

    function removeById(id)
    {
        remove(find(id));
    }

    function parseAmount(amount)
    {
        return amount.substr(0,3).replace(/\D/g,'')*1;
    }

    function canAdd(bet)
    {
        if (betMode == "simple")
            return true;

        return !has(bet.gameId, "gameId");
    }

    function selectBetMode(operation)
    {
        if (bets.length == 0)
            noBetsDefault();

        if (bets.length == 1)
            activate();

        var multiBetsTab = $("#betslip-multiTab");

        if (!hasRepeatedGames() && bets.length > 1) {
            multiBetsTab.prop("disabled", false);

            if (bets.length === 2 && operation == "add")
                multiBetsTab.click();

            return;
        }

        $("#betslip-simpleTab").click();
    }

    function activate()
    {
        $("#betslip-noBets").addClass("hidden");
        $("#betslip-simpleFooter").removeClass("hidden");
        $("#betslip-multiFooter").removeClass("hidden");

        $("#betslip-submit").prop("disabled", false);
    }

    function hasRepeatedGames()
    {
        var games = {};

        for (var i=0; i < bets.length; i++) {
            if (games[bets[i].gameId])
                return true;

            games[bets[i].gameId] = true;
        }

        return false;
    }

    function simpleClick()
    {
        $(this).addClass("selected");

        var simpleIcon = $(this).find("i");

        simpleIcon.removeClass("fa-plus");
        simpleIcon.addClass("fa-caret-down");
        simpleIcon.removeClass("inactive");
        simpleIcon.addClass("active");

        var multiTab = $("#betslip-multiTab");
        var multiIcon = multiTab.find("i");

        multiIcon.removeClass("fa-caret-down");
        multiIcon.addClass("fa-plus");
        multiIcon.removeClass("active");
        multiIcon.addClass("inactive");

        multiTab.removeClass("selected");
        $("#betslip-simpleContainer").removeClass("hidden");
        $("#betslip-multiContainer").addClass("hidden");

        betMode = "simple";
    }

    function multiClick()
    {
        if (hasRepeatedGames() || bets.length < 2)
            return;

        var simpleTab = $("#betslip-simpleTab");
        var simpleIcon = simpleTab.find("i");

        simpleIcon.removeClass("fa-caret-down");
        simpleIcon.addClass("fa-plus");
        simpleIcon.addClass("inactive");
        simpleIcon.removeClass("active");

        var multiIcon = $(this).find("i");

        multiIcon.removeClass("fa-plus");
        multiIcon.addClass("fa-caret-down");
        multiIcon.addClass("active");
        multiIcon.removeClass("inactive");

        $(this).addClass("selected");
        simpleTab.removeClass("selected");
        $("#betslip-multiContainer").removeClass("hidden");
        $("#betslip-simpleContainer").addClass("hidden");

        betMode = "multi";
    }

    function clear()
    {
        bets = [];

        $("#betslip-simpleContent").html("");
        $("#betslip-multiBets-content").html("");
        $(".markets-button").removeClass("selected");

        noBetsDefault();
    }

    function noBetsDefault()
    {
        $("#betslip-noBets").removeClass("hidden");
        $("#betslip-simpleFooter").addClass("hidden");
        $("#betslip-multiFooter").addClass("hidden");
        $("#multiBet-text-error").html("");
        $("#betslip-simpleTab").click();
        $("#betslip-submit").prop("disabled", true);

        multiAmount = 0;

        $("#betslip-multiAmount").val(multiAmount);
    }

    function persistBets()
    {
        Cookies.set("bets", bets, {expires: 30});
    }

     function restore()
     {
        var oldBets = Cookies.getJSON("bets");

        if (!oldBets)
            return;

        for (var i = 0; i < oldBets.length; i++)
            add(oldBets[i]);
    }

    function submit()
    {
        disableSubmit();

        $.post("/desporto/betslip", makeRequest())
            .done(submitDone)
            .fail(submitFail)
            .always(submitAlways);
    }

    function makeRequest()
    {
        var request = {bets: []};

        if (betMode === "multi")
            return makeMulti(request);

        return makeSimple(request);
    }

    function makeMulti(request)
    {
        request.bets.push({
            rid: "multi",
            type: betMode,
            amount: parseInt(multiAmount),
            events: bets
        });

        return request;
    }

    function makeSimple(request)
    {
        for (var i in bets)
            request.bets.push({
                rid: bets[i].id,
                type: "simple",
                amount: bets[i].amount,
                events: [bets[i]]
            });

        return request;
    }

    function submitDone(data)
    {
        var bets = data.bets;

        for (var i in bets)
        {
            var bet = bets[i];

            if (bet.rid === "multi") {
                multiResponse(bet);

                return;
            }

            simpleResponse(bet);
        }
    }

    function multiResponse(bet)
    {
        if (bet.code === 0)
            multiSuccess();
        else
            multiError(bet.errorMsg);
    }

    function multiSuccess()
    {
        var html = '<div id="betslip-multiBet-success" class="bets-box vmargin-small">' +
            '<p class="betSuccess">Aposta submetida com sucesso</p>' +
            '</div>';
        $("#betslip-multiBets-content").html(html);
        $("#betslip-multiFooter").addClass("hidden");
        setTimeout(function () {
            clear();
            $("#betslip-multiBet-success").remove();
        }, 2000);
    }

    function multiError(msg)
    {
        $("#multiBet-text-error").html(msg);
    }

    function simpleResponse(bet)
    {
        if (bet.code === 0)
            simpleSuccess(bet.rid);
        else
            simpleError(bet);
    }

    function simpleSuccess(rid)
    {
        $("#betslip-simpleBet-box-"+rid).html('<p class="betSuccess">Aposta submetida com sucesso</p>');
        setTimeout(function () {
            removeById(rid);
        }, 2000);
    }

    function simpleError(bet)
    {
        $("#simpleBet-text-error-"+bet.rid).html(bet.errorMsg);
    }

    function disableSubmit()
    {
        var submitBtn = $("#betslip-submit");

        submitBtn.prop("disabled", true);
        submitBtn.html("Aguarde...");
    }

    this.enableSubmit = function()
    {
        enableSubmit();
    };

    function enableSubmit()
    {
        setTimeout(function() {
            var submitBtn = $("#betslip-submit");

            submitBtn.prop("disabled", bets.length == 0);
            submitBtn.html("EFECTUAR APOSTA");
        }, 2100);
    }

    function submitFail()
    {
        alert("O serviço de apostas não está disponível.");
    }

    function submitAlways()
    {
        enableSubmit();
    }

    function login()
    {
        var username = $("#user-login");
        var password = $("#pass-login");

        if (!username.val())
            username.focus();
        else if (!password.val())
            password.focus();
        else
            $("#submit-login").click();
    }

    this.applySelected = function (container)
    {
        applySelected(container)
    };

    function applySelected (container)
    {
        for (var i in bets)
            container.find("[data-event-id='" + bets[i].id + "']").addClass("selected");
    }

})();

var Favorites = new (function () {

    var favorites = Cookies.getJSON("favorites") || [];

    init();

    function init()
    {
        restore();

        $("#btnFavorites").click(showFavorites);

        $(window).unload(persist);

        activeIcon();
    }

    this.toggle = function()
    {
        $(this).toggleClass("selected");

        var id = $(this).data('game-id');

        if (has(id))
            remove(id);
        else
            add.call(this);

        activeIcon();
    };

    this.games = function()
    {
        return favorites;
    };

    this.apply = function()
    {
        apply();
    };

    function has(id)
    {
        for (var i in favorites)
            if (favorites[i].id == id)
                return true;

        return false;
    }

    function add()
    {
        favorites.push({
            'id': $(this).data("game-id"),
            'name': $(this).data("game-name"),
            'date': $(this).data("game-date")
        });
    }

    function remove(id)
    {
        for (var i in favorites)
            if (favorites[i].id == id) {
                favorites.splice(i, 1);
                return;
            }

    }

    function restore()
    {
        if (!favorites)
            return;

        for (var i=0; i<favorites.length; i++)
            if (moment(favorites[i].date).add(1, "days")<moment()) {
                favorites.splice(i, 1);
                i--;
            }

        apply();
    }

    function persist()
    {
        Cookies.set("favorites", favorites, {expires: 30});
    }

    function apply()
    {
        $("button[data-type=favorite]").removeClass("selected");

        for (var i in favorites)
            $("button[data-type=favorite][data-game-id=" + favorites[i].id + "]").addClass("selected");
    }

    function showFavorites(e)
    {
        if (favorites.length)
            page("/favoritos");

        e.preventDefault();

        return false;
    }

    function activeIcon()
    {
        $("#btnFavorites").find("i").css("color", (favorites.length ? "#ff9900" : "#cccccc"));
    }

})();

var Search = new (function ()
{
    init();

    function init()
    {

        $("#searchForm").submit(queryChange);
    }

    function queryChange(e)
    {
        e.preventDefault();

        var query = $("#textSearch").val();

        if (query.length && (query.length < 3))  {
            alert("A pequisa necessita de pelo menos 3 caracteres.");

            return false;
        }

        page('/pesquisa/' + query);

    }
})();

var Updater = new (function() {

    init();

    function init()
    {
        setInterval(updateSelections, 10000);
    }

    function updateSelections()
    {
        var selections = $("button[data-type=odds]:visible");

        var ids = [];

        for (var i=0; i<selections.length; i++)
            ids.push($(selections[i]).data("event-id"));

        if (ids.length)
            $.get('http://genius.ibetup.eu/selections?ids=' + ids.join(',') + '&since=' + 15)
                .done(fetchSelections);
    }

    function fetchSelections(data)
    {
        var selections = data.selections;

        for (var i in selections)
            update(selections[i]);
    }

    function update(selection)
    {
        var btn = $("button[data-event-id=" + selection.id + "]");

        if (btn.data("event-price") == selection.decimal)
            return;

        var className = (btn.data("event-price") > selection.decimal ? "updater-down" : "updater-up");

        btn.addClass(className);
        btn.data('event-price', selection.decimal);
        btn.html(selection.decimal);

        setTimeout(function() {
            btn.removeClass(className);
        }, 5000);
    }

})();

//# sourceMappingURL=sports.js.map
