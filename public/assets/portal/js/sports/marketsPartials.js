Handlebars.registerPartial('match_result', '{{> market type="2"}}');

Handlebars.registerPartial('draw_no_bet', '{{> market type="122"}}');

Handlebars.registerPartial('double_chance', '{{> market type="7202"}}');

Handlebars.registerPartial('market', '\
    {{> container_open}}\
        {{> headers type=type}}\
        {{#each fixtures}}\
            {{> fixture type=../type}}\
        {{/each}}\
    {{> container_close}}\
');

Handlebars.registerPartial('headers', '\
    {{#if_eq type 2}}\
        {{> header title1="Home" title2="Draw" title3="Home"}}\
    {{/if_eq}}\
    {{#if_eq type 122}}\
        {{> header title1="Home" title2="" title3="Home"}}\
    {{/if_eq}}\
    {{#if_eq type 7202}}\
        {{> header title1="Home or Draw" title2="Home or Away" title3="Draw or Away"}}\
    {{/if_eq}}\
');

Handlebars.registerPartial('header',
    '<tr>' +
    '<td class="markets-td-label"></td>' +
    '<td class="markets-td-event">{{title1}}</td>' +
    '<td class="markets-td-event">{{title2}}</td>' +
    '<td class="markets-td-event">{{title3}}</td>' +
    '</tr>'
);

Handlebars.registerPartial('fixture','\
    {{> date}}\
    <tr>\
        {{> game}}\
        {{#each markets}}\
            {{#if_eq ../type 2}}\
                {{> selections outcomeId=1 fixture=.. market=this}}\
                {{> selections outcomeId=2 fixture=.. market=this}}\
                {{> selections outcomeId=3 fixture=.. market=this}}\
            {{/if_eq}}\
            {{#if_eq ../type 122}}\
                {{> selections outcomeId=1}}\
                {{> empty_selection}}\
                {{> selections outcomeId=3}}\
            {{/if_eq}}\
            {{#if_eq ../type 7202}}\
                {{> selections outcomeId=7}}\
                {{> selections outcomeId=8}}\
                {{> selections outcomeId=9}}\
            {{/if_eq}}\
        {{/each}}\
    </tr>\
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

Handlebars.registerPartial('game',
    '<td class="markets-td-label">' +
    '<div class="markets-box-label" data-game-id="{{id}}" data-type="game">' +
    '<span class="markets-text-time">{{time}}</span>' +
    '{{> favorite }}' +
    '<span class="markets-text-gameName">{{name}}</span>' +
    '</div>' +
    '</td>'
);

Handlebars.registerPartial('favorite',
    '<button id="favorite-button-{{id}}"' +
    'class="fa fa-star markets-text-favorite"' +
    'data-game-id="{{id}}"' +
    'data-game-name="{{name}}"' +
    'data-game-date="{{start_time_utc}}"' +
    'data-game-sport=""' +
    'data-selected-css="markets-text-favoriteSelected"' +
    'data-type="favorite"> ' +
    '</button>'
);

Handlebars.registerPartial('selections',
    '{{#each selections}}' +
        '{{#if_eq outcome.id ../outcomeId}}' +
            '{{> selection fixture=../fixture market=../market}}' +
        '{{/if_eq}}' +
    '{{/each}}'
);

Handlebars.registerPartial('selection', '\
    <td class="markets-td-event">\
        <button class="markets-button"\
            data-game-id="{{fixture.id}}"\
            data-game-name="{{fixture.name}}"\
            data-game-date="{{fixture.start_time_utc}}"\
            data-event-id="{{id}}"\
            data-event-name="{{name}}"\
            data-event-price="{{decimal}}"\
            data-market-id="{{market.id}}"\
            data-market-name="{{market.name}}"\
            data-type="odds">\
            {{decimal}}\
        </button>\
    </td>\
');

Handlebars.registerPartial('empty_selection', '<td class="markets-td-event"></td>');

Handlebars.registerPartial('markets_header', '\
    <div class="markets-box-navigation">\
        <i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i> &nbsp;&nbsp;\
        <span class="fright">{{now}}</span>\
    </div>\
    <div class="markets-header">\
        <span class="markets-text-title">Sport</span>\
        <select id="markets-select" class="markets-select">\
            {{#each markets}}\
                <option value="{{market_type_id}}">{{name}}</option>\
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


