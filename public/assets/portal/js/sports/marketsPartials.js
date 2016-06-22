//Handlebars.registerPartial('match_result', '{{> market type="2"}}');

Handlebars.registerPartial('match_result', '{{> fixtures}}');

Handlebars.registerPartial('draw_no_bet', '{{> market type="122"}}');

Handlebars.registerPartial('double_chance', '{{> market type="7202"}}');

Handlebars.registerPartial('highest_scoring_half', '{{> market type="25"}}');

Handlebars.registerPartial('win_both_halves', '{{> market type="60"}}');

Handlebars.registerPartial('clean_sheet', '{{> market type="62"}}');

Handlebars.registerPartial('win_either_half', '{{> market type="104"}}');

Handlebars.registerPartial('handicap_with_tie', '{{> market type="105"}}');

Handlebars.registerPartial('to_win_to_nil', '{{> market type="169"}}');

Handlebars.registerPartial('first_half_result', '{{> market type="6832"}}');

Handlebars.registerPartial('second_half_result', '{{> market type="7591"}}');

Handlebars.registerPartial('market', '\
    {{> container_open}}\
        {{> headers}}\
        {{#each fixtures}}\
            {{> fixture type=../type}}\
        {{/each}}\
    {{> container_close}}\
');

Handlebars.registerPartial('headers', '\
    {{#if_in type "2,6832,7591"}}\
        {{> header title1=outcomes.[1] title2=outcomes.[2] title3=outcomes.[3]}}\
    {{/if_in}}\
    {{#if_in type "122,60,62,104,169"}}\
        {{> header title1=outcomes.[1] title2="" title3=outcomes.[3]}}\
    {{/if_in}}\
    {{#if_eq type 7202}}\
        {{> header title1=outcomes.[7] title2=outcomes.[8] title3=outcomes.[9]}}\
    {{/if_eq}}\
    {{#if_eq type 25}}\
        {{> header title1=outcomes.[27] title2=outcomes.[28] title3=outcomes.[29]}}\
    {{/if_eq}}\
    {{#if_eq type 105}}\
        {{> header title1=outcomes.[4] title2=outcomes.[5] title3=outcomes.[6]}}\
    {{/if_eq}}\
');

Handlebars.registerPartial('header', '\
    <tr>\
        <td class="markets-td-label"></td>\
        <td class="markets-td-event">{{title1}}</td>\
        <td class="markets-td-event">{{title2}}</td>\
        <td class="markets-td-event">{{title3}}</td>\
    </tr>\
');

Handlebars.registerPartial('fixture','\
    {{> date}}\
    <tr>\
        {{> game}}\
        {{#each markets}}\
            {{> market_selections fixture=.. type=../type}}\
        {{/each}}\
    </tr>\
');

Handlebars.registerPartial('market_selections', '\
    {{#if_in type "2,6832,7591"}}\
        {{> selections outcomeId=1 market=this}}\
        {{> selections outcomeId=2 market=this}}\
        {{> selections outcomeId=3 market=this}}\
    {{/if_in}}\
    {{#if_in type "122,60,62,104,169"}}\
        {{> selections outcomeId=1 fixture=fixture market=this}}\
        {{> empty_selection}}\
        {{> selections outcomeId=3 fixture=fixture market=this}}\
    {{/if_in}}\
    {{#if_eq type 7202}}\
        {{> selections outcomeId=7 fixture=fixture market=this}}\
        {{> selections outcomeId=8 fixture=fixture market=this}}\
        {{> selections outcomeId=9 fixture=fixture market=this}}\
    {{/if_eq}}\
    {{#if_eq type 25}}\
        {{> selections outcomeId=27 fixture=fixture market=this}}\
        {{> selections outcomeId=28 fixture=fixture market=this}}\
        {{> selections outcomeId=29 fixture=fixture market=this}}\
    {{/if_eq}}\
    {{#if_eq type 105}}\
        {{> selections outcomeId=4 fixture=fixture market=this}}\
        {{> selections outcomeId=5 fixture=fixture market=this}}\
        {{> selections outcomeId=6 fixture=fixture market=this}}\
    {{/if_eq}}\
');

Handlebars.registerPartial('date', '\
   {{#if date}}\
        <tr>\
            <td class="markets-td-header" colspan="4">\
                <span class="markets-text-date">{{date}}</span>\
            </td>\
        </tr>\
    {{/if}}\
');

Handlebars.registerPartial('game', '\
    <td class="markets-td-label">\
        <div class="markets-box-label" data-game-id="{{id}}" data-type="game">\
            <span class="markets-text-time">{{time}}</span>\
            {{> favorite }}\
            <span class="markets-text-gameName">{{name}}</span>\
        </div>\
    </td>\
');

Handlebars.registerPartial('favorite', '\
    <button id="favorite-button-{{id}}"\
        class="fa fa-star markets-button favorite"\
        data-game-id="{{id}}"\
        data-game-name="{{name}}"\
        data-game-date="{{start_time_utc}}"\
        data-game-sport=""\
        data-selected-css="markets-text favorite selected"\
        data-type="favorite"> \
    </button>\
');





Handlebars.registerPartial('empty_selection', '<td class="markets-td-event"></td>');

Handlebars.registerPartial('markets_header_navigation', '\
    <div class="markets-box navigation">\
        &nbsp;&nbsp; \
            {{sport}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            {{region}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            {{#if fixture}} \
                {{competition}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
                <span class="markets-text navigation selected">{{fixture}}</span> \
            {{else}}\
                <span class="markets-text navigation selected">{{competition}}</span>\
            {{/if}}\
    </div>\
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

Handlebars.registerPartial('container_open', '\
    <div class="markets-content"><table class="markets-table">\
');

Handlebars.registerPartial('container_close', '\
        </table></div>\
');

Handlebars.registerPartial('fixture_markets','\
    {{#each fixtures}}\
        {{> markets_header_navigation sport=../sport region=../region competition=../competition fixture=name}}\
        <div class="markets-header">\
            <span class="markets-text-title">{{name}}</span>\
            <button id="markets-game-hide" class="markets-select fright">Voltar atrás</button>\
        </div>\
        <div class="markets-content">\
            {{#each markets}}\
                <table class="markets-table">\
                    {{> headers outcomes=../../outcomes type=market_type_id}}\
                    <tr>\
                        <td class="markets-td-header">{{market_type.name}}</td>\
                        {{> market_selections fixture=.. type=market_type_id}}\
                    </tr>\
                </table>\
                <br>\
            {{/each}}\
        </div>\
    {{/each}}\
');

Handlebars.registerPartial('get_selection', '\
    {{#each selections}}\
        {{#if_eq outcome.id ../outcomeId}}\
            {{> selection fixture=../fixture market=../market}}\
        {{/if_eq}}\
   {{/each}}\
');

Handlebars.registerPartial('selection', '\
    <td class="markets-td selection {{fixture.parity}}">\
        <button class="markets-button selection"\
            data-game-id="{{fixture.id}}"\
            data-game-name="{{fixture.name}}"\
            data-game-date="{{fixture.start_time_utc}}"\
            data-event-id="{{id}}"\
            data-event-name="{{outcome.name}}"\
            data-event-price="{{decimal}}"\
            data-market-id="{{market.id}}"\
            data-market-name="{{market.market_type.name}}"\
            data-type="odds">\
            {{decimal}}\
        </button>\
    </td>\
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
            <tr class="markets-tr odd">\
                <td class="markets-td date {{parity}}">{{date}}<br>{{time}}</td>\
                <td class="markets-td game {{parity}}">{{name}}</td>\
                <td class="markets-td favorite {{parity}}">{{> favorite}}</td>\
                <td class="markets-td statistics {{parity}}">{{> statistics}}</td>\
                <td class="markets-td separator">&nbsp;</td>\
                {{#each markets}}\
                    {{> get_selection outcomeId=1 fixture=.. market=this}}\
                    <td class="markets-th separator onePx"></td>\
                    {{> get_selection outcomeId=2 fixture=.. market=this}}\
                    <td class="markets-th separator onePx"></td>\
                    {{> get_selection outcomeId=3 fixture=.. market=this}}\
                {{/each}}\
                <td class="markets-td separator">&nbsp;</td>\
                <td class="markets-td marketsCount">&nbsp;</td>\
            </tr>\
        {{/each}}\
    </table>\
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
