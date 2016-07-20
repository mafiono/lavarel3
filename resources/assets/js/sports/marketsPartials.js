Handlebars.registerPartial('fixtures', '\
    <table class="markets-table-fixtures">\
        <tr class="header">\
            <th class="date">&nbsp;</th>\
            <th class="game">&nbsp;</th>\
            <th class="favorite">&nbsp;</th>\
            <th class="statistics">&nbsp;</th>\
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
        <button class="markets-button-selection"\
            data-game-id="{{fixture.id}}"\
            data-game-name="{{fixture.name}}"\
            data-game-date="{{fixture.start_time_utc}}"\
            data-event-id="{{id}}"\
            data-event-name="{{#if_eq market.market_type.is_handicap 1}}{{market.handicap}} - {{/if_eq}}{{name}}"\
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
        class="fa fa-bar-chart markets-button-statistics"\
        data-game-id="{{id}}"\
        data-game-name="{{name}}"\
        data-game-date="{{start_time_utc}}"\
        data-game-sport=""\
        data-selected-css="markets-text statistics selected"\
        data-type="statistics"> \
    </button>\
');



Handlebars.registerPartial('fixture_markets','\
    {{#each fixtures}}\
        <div class="markets-content">\
            <div class="markets-box-marketsHeader">\
                <span>{{name}}</span>\
                <i id="markets-hide" class="fa fa-times close" aria-hidden="true"></i>\
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
        <div class="markets-box-marketTitle">\
            {{market_type.name}}\
        </div>\
        <table class="markets-table-market">\
            {{> markets_headers type=../type outcomes=../outcomes}}\
            {{> market_selections type=../type fixture=.. index=@index}}\
        </table>\
    {{/with}}\
');

Handlebars.registerPartial('market_multiRow', '\
    {{#with (lookup this type)}}\
        <div class="markets-box-marketTitle">\
            {{this.[0].market_type.name}}\
        </div>\
        <table class="markets-table-market">\
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
    <tr class="row">\
        {{> get_selection outcomeId=outcome1 market=this type="triple"}}\
        <td class="markets-td-market-separator"></td>\
        {{> get_selection outcomeId=outcome2 market=this type="triple"}}\
        <td class="markets-td-market-separator"></td>\
        {{> get_selection outcomeId=outcome3 market=this type="triple"}}\
    </tr>\
');

Handlebars.registerPartial('market_selections_double','\
    <tr class="row">\
        <td class="markets-td marketName">\
            {{#if_eq market_type.is_handicap 1}}\
                {{handicap}}\
            {{/if_eq}}\
        </td>\
        <th class="markets-td-market-separator"></th>\
        {{> get_selection outcomeId=outcome1 market=this type="double"}}\
        <td class="markets-td-market-separator"></td>\
        {{> get_selection outcomeId=outcome2 market=this type="double"}}\
    </tr>\
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

Handlebars.registerPartial('market_header_triple', '\
    <th class="selection">{{> get_selection_name outcome=outcome1}}</th>\
    <th class="separator"></th>\
    <th class="selection">{{> get_selection_name outcome=outcome2}}</th>\
    <th class="separator"></th>\
    <th class="selection">{{> get_selection_name outcome=outcome3}}</th>\
');

Handlebars.registerPartial('market_header_double', '\
    <th class="selection"></th>\
    <th class="separator"></th>\
    <th class="selection">{{> get_selection_name outcome=outcome1}}</th>\
    <th class="separator"></th>\
    <th class="selection">{{> get_selection_name outcome=outcome2}}</th>\
');