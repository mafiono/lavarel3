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
