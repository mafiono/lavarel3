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
    <table class="markets-table">\
        <tr class="markets-tr header">\
            <th class="markets-th date">&nbsp;</th>\
            <th class="markets-th game">&nbsp;</th>\
            <th class="markets-th favorite">&nbsp;</th>\
            <th class="markets-th statistics">&nbsp;</th>\
            <th class="markets-th separator">&nbsp;</th>\
            <th class="markets-th selection">1</th>\
            <th class="markets-th separator onePx"></th>\
            <th class="markets-th selection">X</th>\
            <th class="markets-th separator onePx"></th>\
            <th class="markets-th selection">2</th>\
            <th class="markets-th separator">&nbsp;</th>\
            <th class="markets-th marketCount">&nbsp;</th>\
        </tr>\
        {{#each fixtures}}\
            <tr class="markets-tr">\
                <td class="markets-td date {{parity @index}}">{{date}}<br>{{time}}</td>\
                <td class="markets-td game {{parity @index}}" data-game-id="{{id}}" data-type="fixture">{{name}}</td>\
                <td class="markets-td favorite {{parity @index}}">{{> favorite}}</td>\
                <td class="markets-td statistics {{parity @index}}">{{> statistics}}</td>\
                <td class="markets-td separator">&nbsp;</td>\
                {{#each markets}}\
                    {{#if_in market_type_id "2,306"}}\
                        {{> get_selection outcomeId=1 fixture=.. index=@../index}}\
                    {{/if_in}}\
                    {{#if_eq market_type_id 322}}\
                        {{> get_selection outcomeId=25 fixture=.. index=@../index}}\
                    {{/if_eq}}\
                    <td class="markets-th separator onePx"></td>\
                        {{> get_selection outcomeId=2 fixture=.. index=@../index}}\
                    <td class="markets-th separator onePx"></td>\
                    {{#if_in market_type_id "2,306"}}\
                        {{> get_selection outcomeId=3 fixture=.. index=@../index}}\
                    {{/if_in}}\
                    {{#if_eq market_type_id 322}}\
                        {{> get_selection outcomeId=26 fixture=.. index=@../index}}\
                    {{/if_eq}}\
                {{/each}}\
                <td class="markets-td separator">&nbsp;</td>\
                <td class="markets-td-marketsCount {{parity @index}}" data-game-id="{{id}}" data-type="fixture">+{{markets_count}}</td>\
            </tr>\
        {{/each}}\
    </table>\
');

Handlebars.registerPartial('get_selection', '\
    <td class="markets-td selection {{type}} {{parity index}}">\
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
        <button class="markets-button selection"\
            data-game-id="{{fixture.id}}"\
            data-game-name="{{fixture.name}}"\
            data-game-date="{{fixture.start_time_utc}}"\
            data-event-id="{{id}}"\
            data-event-name="\
            {{#if_eq market.market_type.is_handicap 1}}\
                {{market.handicap}} - \
            {{/if_eq}}\
            {{name}}"\
            data-event-price="{{decimal}}"\
            data-market-id="{{market.id}}"\
            data-market-name="{{market.market_type.name}}"\
            data-type="odds">\
            {{decimal}}\
        </button>\
    {{/if_eq}}\
');

Handlebars.registerPartial('favorite', '\
    <button id="favorite-button-{{id}}"\
        class="fa fa-star markets-button-favorite"\
        data-game-id="{{id}}"\
        data-game-name="{{name}}"\
        data-game-date="{{start_time_utc}}"\
        data-type="favorite"> \
    </button>\
');

Handlebars.registerPartial('statistics', '\
    <button id="statistics-{{id}}"\
        class="fa fa-bar-chart markets-button statistics"\
        data-game-id="{{id}}"\
        data-game-name="{{name}}"\
        data-game-date="{{start_time_utc}}"\
        data-game-sport=""\
        data-selected-css="markets-text statistics selected"\
        data-type="statistics"> \
    </button>\
');

Handlebars.registerPartial('markets_navigation', '\
    <div class="markets-box navigation">\
        {{#if_eq operation "Favoritos"}}\
            <span class="markets-text navigation selected">{{operation}}</span>\
        {{/if_eq}}\
        {{#if_eq operation "Pesquisa"}}\
            {{operation}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="markets-text navigation selected">{{query}}</span>\
        {{/if_eq}}\
        {{#if_eq operation "Destaques"}}\
            {{operation}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="markets-text navigation selected">{{competition}}</span>\
        {{/if_eq}}\
        {{#if_eq operation "Competition"}}\
            {{sport}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            {{region}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="markets-text navigation selected">{{competition}}</span>\
        {{/if_eq}}\
    </div>\
');

Handlebars.registerPartial('fixture_markets','\
    {{#each fixtures}}\
        {{> markets_navigation sport=../sport region=../region competition=../competition fixture=name}}\
        <div class="markets-content">\
            <div class="markets-box markets header">\
                <span class="markets-text markets header">{{name}}</span>\
                <button id="markets-hide" class="markets-button markets close">\
                    <i class="fa fa-times" aria-hidden="true"></i>\
                </button>\
            </div>\
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
        <div class="markets-box market title">\
            <span class="markets-text market title">{{market_type.name}}</span>\
        </div>\
        <table class="markets-table market">\
            {{> markets_headers type=../type outcomes=../outcomes}}\
            {{> market_selections type=../type fixture=.. index=@index}}\
        </table>\
    {{/with}}\
');

Handlebars.registerPartial('market_multiRow', '\
    {{#with (lookup this type)}}\
        <div class="markets-box market title">\
            <span class="markets-text market title">{{this.[0].market_type.name}}</span>\
        </div>\
        <table class="markets-table market">\
            {{#each this}}\
                {{#if_eq @index 0}}\
                    {{> markets_headers type=../../type fixture=../.. outcomes=../../outcomes}}\
                {{/if_eq}}\
                {{> market_selections type=../../type fixture=../.. index=@index}}\
            {{/each}}\
        </table>\
    {{/with}}\
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

Handlebars.registerPartial('market_selections_triple','\
    <tr class="markets-tr">\
        <td class="markets-td marketName">\
            {{#if_eq market_type.is_handicap 1}}\
                {{handicap}}\
            {{/if_eq}}\
        </td>\
        <th class="markets-td selection separator"></th>\
        {{> get_selection outcomeId=outcome1 market=this type="triple"}}\
        <td class="markets-td selection separator"></td>\
        {{> get_selection outcomeId=outcome2 market=this type="triple"}}\
        <td class="markets-td selection separator"></td>\
        {{> get_selection outcomeId=outcome3 market=this type="triple"}}\
    </tr>\
');

Handlebars.registerPartial('market_selections_double','\
    <tr class="markets-tr">\
        <td class="markets-td marketName">\
            {{#if_eq market_type.is_handicap 1}}\
                {{handicap}}\
            {{/if_eq}}\
        </td>\
        <th class="markets-td selection separator"></th>\
        {{> get_selection outcomeId=outcome1 market=this type="double"}}\
        <td class="markets-td selection separator"></td>\
        {{> get_selection outcomeId=outcome2 market=this type="double"}}\
    </tr>\
');

Handlebars.registerPartial('markets_headers', '\
    <tr class="markets-tr">\
        <th class="markets-th marketName"></th>\
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

Handlebars.registerPartial('market_header_triple', '\
    <th class="markets-th selection separator"></th>\
    <th class="markets-th selection triple">{{> get_selection_name outcome=outcome1}}</th>\
    <th class="markets-th selection separator"></th>\
    <th class="markets-th selection triple">{{> get_selection_name outcome=outcome2}}</th>\
    <th class="markets-th selection separator"></th>\
    <th class="markets-th selection triple">{{> get_selection_name outcome=outcome3}}</th>\
');

Handlebars.registerPartial('market_header_double', '\
    <th class="markets-th selection separator"></th>\
    <th class="markets-th selection double">{{> get_selection_name outcome=outcome1}}</th>\
    <th class="markets-th selection separator"></th>\
    <th class="markets-th selection double">{{> get_selection_name outcome=outcome2}}</th>\
');

Handlebars.registerPartial('markets_header', '\
    {{> markets_header_navigation}}\
    <div class="markets-header hidden">\
        <span class="markets-text-title">{{sport}}</span>\
        <select id="markets-select" class="markets-select">\
            {{#each markets}}\
                <option value="{{market_type.id}}">{{market_type.name}}</option>\
            {{/each}}\
        </select>\
    </div>\
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
var SportsMenu = new (function()
{
    var until;

    init();

    function init()
    {
        until = encodeURIComponent(moment.utc().add(1, "years").format());

        new Spinner().spin(document.getElementById("sportsSpinner"));

        new Spinner().spin(document.getElementById("highlightsSpinner"));

        $("#sportsMenu-interval").click(intervalClick);

        $(".sportsMenu-container div[data-interval]").click(intervalOptionClick);

        make();
    };

    function intervalClick()
    {
        var expand = $(this).find("span i");

        expand.toggleClass("fa-plus");
        expand.toggleClass("fa-caret-down");
        expand.toggleClass("collapse");

        $(this).toggleClass("selected");

        $("#sportsMenu-interval-content").toggleClass("hidden");
    }

    function intervalOptionClick()
    {
        $("#sportsMenu-interval-text").html($(this).find("span").html());

        var interval = $(this).data("interval");

        var until = (interval == "today") ?
            moment.utc().add(1, 'days').hours(0).minutes(0).seconds(0).format() :
            moment.utc().add(interval, "hours").format();

        until = encodeURIComponent(until);

        Markets.makeUntil(until);

        $("#sportsMenu-interval-content").toggleClass("hidden");
    }

    function make()
    {
        fetchSports();
    }

    this.make = function()
    {
        make();
    };

    function fetchSports ()
    {
        $.get("/odds/sports?ids=10,24,4&until" + until)
            .done(renderSports);
    }

    function renderSports (data)
    {
        $("#sportsMenu-popular").html(Template.apply("sports_menu", data));

        sportsClick();
    }

    function sportsClick ()
    {
        $(".menu1-option").click(sportClick);
    }

    function sportClick ()
    {
        var containerEmpty = ($(this).next().html() === "");

        if (containerEmpty && $(this).hasClass("selected"))
            return;

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
        $.get("/odds/regions?sport=" + sportId + "&until=" + until)
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
        $.get("/odds/competitions?sport=" + sportId + "&region=" + regionId)
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

        competitionId = $(this).parent().data('competition-id');

        Markets.make(marketsOptions.call(this));
    }

    function marketsOptions()
    {
        var competition = $(this).parent();
        var region = competition.parent().prev();
        var sport = region.parent().parent().prev();

        return {
            competition : competition.data("competition-name"),
            competitionId : competition.data("competition-id"),
            region : region.data("region-name"),
            sport : sport.data("sport-name")
        };
    }

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

        $.get("/odds/competitions?ids=" + highligths.join(','))
            .done(renderHighlights)
    }

    function renderHighlights(data)
    {
        var container = $("#highlights-container");

        container.html(Template.apply('highlights_submenu', data));

        container.find("div[data-type=highlight]").click(highlightClick);
    }

    function highlightClick()
    {
        Markets.makeHighlight({
            competition : $(this).data("competition-name"),
            competitionId : $(this).data("competition-id")
        });
    }

})();

var Markets = new (function ()
{
    var options = {
        sport: "Futebol",
        region: "Europa",
        competition: "UEFA Champions League",
        competitionId: 19,
        until: encodeURIComponent(moment.utc().add(1, "years").format()),
        operation: "Competition"
    };

    var outcomes = {};

    var fixtureId;

    var market_types = "2,306,322,259,105,122,7202,25,60,62,104,169,6832,7591";

    init();

    function init()
    {
        new Spinner().spin(document.getElementById("marketsSpinner"));

        make(fromCompetition(options));
    }

    function make(from)
    {
        renderHeader();

        fetchFixtures(from);
    }

    this.make = function(_options)
    {
        if (_options)
            updateOptions(_options);

        options["operation"] = "Competition";

        make(fromCompetition(options));
    };

    this.makeUntil = function (until)
    {
        options.until = until ? until : encodeURIComponent(moment.utc().add(1, "years").format());

        make(fromCompetition(options));
    };

    this.makeFavorites = function ()
    {
        options["operation"] = "Favoritos";

        make(fromFavorites());
    };

    this.makeQuery = function (query)
    {
        options["operation"] = "Pesquisa";
        options["query"] = query;

        make(fromQuery(query));
    };

    this.makeHighlight = function(_options)
    {
        options["operation"] = "Destaques";

        updateOptions(_options);

        make(fromCompetition(options));
    };

    function renderHeader()
    {
        $("#intro-banners").addClass("hidden");
        $("#markets-header-container").removeClass("hidden");

        $("#markets-header-container").html(Template.apply('markets_navigation', options));
    }

    function fromCompetition()
    {
        return "competition=" + options.competitionId;
    }

    function fromFavorites()
    {
        var favorites = [];

        var games = Favorites.games();

        for (var i in games)
            favorites.push(games[i].id);

        return "ids=" + favorites.join(',');
    }

    function fromQuery(query)
    {
        return "query=" + query;
    }

    function fetchFixtures(from)
    {
        $.get("/odds/fixtures?" + from +
            "&marketType=2&orderBy=start_time_utc,asc" +
            "&until=" + options.until +
            "&marketsCount=" + market_types +
            "&take=" + 40
        ).done(renderFixtures);
    }

    function renderFixtures(data)
    {
        fixturesData(data);

        var marketsContent = $("#markets-content");

        marketsContent.html(Template.apply("fixtures", data));

        applySelected(marketsContent);

        marketsContent.find("[data-type='fixture']").click(fixtureClick);

        marketsContent.find("[data-type='odds']").click(selectionClick);

        marketsContent.find("[data-type='favorite']").click(favoriteClick);

        Favorites.apply();

        showFixtures();
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

    function fixtureClick()
    {
        var id = $(this).data("game-id");

        if (fixtureId == id) {
            hideFixtures();

            return;
        }

        fixtureId = id;

        $.get("/odds/fixtures?ids=" + fixtureId +
            "&withMarketTypes=" + market_types
        ).done(renderFixture);
    }

    function renderFixture(data)
    {
        headerData(data);

        fixturesData(data, true);

        var container = $("#markets-fixtureMarketsContainer");

        container.html(Template.apply('fixture_markets', data));

        container.find("[data-type='odds']").click(selectionClick);

        applySelected(container);

        $("#markets-hide").click(showFixtures);

        container.find("#markets-more").click(moreMarketsClick);

        hideFixtures();
    }

    function showFixtures()
    {
        $("#markets-fixturesContainer").removeClass("hidden");
        $("#markets-fixtureMarketsContainer").addClass("hidden");
    }

    function hideFixtures()
    {
        $("#markets-fixturesContainer").addClass("hidden");
        $("#markets-fixtureMarketsContainer").removeClass("hidden");
    }

    function moreMarketsClick() {
        $(this).addClass("hidden");

        $("#markets-others").removeClass("hidden");
    }

    function headerData(data)
    {
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

    function favoriteClick()
    {
        Favorites.toggle.call(this);
    }

    function updateOptions(_options) {
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

})();

var Favorites = new (function () {

    var favorites = Cookies.getJSON("favorites") || [];

    init();

    function init()
    {
        restore();

        $("#btnFavorites").click(showFavorites);

        $(window).unload(persist);
    }

    this.toggle = function()
    {
        $(this).toggleClass("selected");

        var id = $(this).data('game-id');

        if (has(id))
            remove(id);
        else
            add.call(this);
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
            if (favorites[i].id == id)
                favorites.splice(i, 1);
    }

    function restore()
    {
        var oldFavorites = Cookies.getJSON("favorites");
        
        if (!oldFavorites)
            return;
        
        apply();
    }

    function persist()
    {
        Cookies.set("favorites", favorites, {expires: 30});
    }

    function apply()
    {
        for (var i in favorites)
            $("#favorite-button-"+favorites[i].id).addClass("selected");
    }

    function showFavorites(e)
    {
        if (favorites.length)
            Markets.makeFavorites();

        e.preventDefault();

        return false;
    }

})();

var Search = new (function ()
{
    init();

    function init()
    {

        $("#textSearch").change(queryChange);
    }

    function queryChange()
    {
        var query = $(this).val();

        if (query.length && (query.length < 3))  {
            alert("A pequisa necessita de pelo menos 3 caracteres.");

            return;
        }

        Markets.makeQuery($(this).val());
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
        var selections = $("button[data-type=odds]");

        var ids = [];

        for (var i=0; i<selections.length; i++)
            ids.push($(selections[i]).data("event-id"));

        if (ids.length)
            $.get('/odds/selections?ids=' + ids.join(',') + '&since=' + 15)
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

        var className = (btn.data("event-price") > selection.decimal ? "updated-down" : "updated-up");

        btn.addClass(className);
        btn.data('event-price', selection.decimal);
        btn.html(selection.decimal);

        setTimeout(function() {
            btn.removeClass(className);
        }, 500);
    }

})();

//# sourceMappingURL=sports.js.map
