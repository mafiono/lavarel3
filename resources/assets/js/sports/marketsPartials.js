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

Handlebars.registerPartial('statistics_button', '\
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
                    {{#if external_id}}\
                        <i id="markets-statistics" class="fa fa-bar-chart" aria-hidden="true"></i>\
                    {{/if}}\
                </div>\
            {{/if_not}}\
            {{#each marketsSet}}\
                {{> market_singleRow3Cols market_type_id=@key fixture=.. markets=this}}\
                {{> market_multiRow2Cols market_type_id=@key fixture=.. markets=this}}\
                {{> market_multiRow3Cols market_type_id=@key fixture=.. markets=this}}\
                {{> market_multiRow3ColsUnlabeled market_type_id=@key fixture=.. markets=this}}\
            {{/each}}\
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
