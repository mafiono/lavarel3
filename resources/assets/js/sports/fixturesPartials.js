Handlebars.registerPartial('fixtures', '\
    <table class="fixtures noselect">\
        <tr class="header">\
            <th class="date {{options.mode}}">\
                {{#if_eq options.mode "sport"}}\
                    <i class="{{sport_icon options.sportId}}"></i>\
                {{/if_eq}}\
            </th>\
            <th class="{{options.mode}} game"><span>{{options.sportName}}</span></th>\
            <th class="prematch {{options.mode}} {{#if options.live}}live{{/if}}" colspan="2">{{#if options.live}}DIRETO{{/if}}</th>\
            <th class="separator">&nbsp;</th>\
            <th class="selection">1</th>\
            <th class="selectionSeparator"></th>\
            <th class="selection">X</th>\
            <th class="selectionSeparator"></th>\
            <th class="selection">2</th>\
            <th class="separator">&nbsp;</th>\
            <th class="marketCount {{options.mode}}"><i class="cp-caret-down"></i></th>\
        </tr>\
        {{#each fixtures}}\
            <tr class="fixture">\
                <td class="date {{parity @index}}">\
                {{#is_inPlay}}\
                    {{elapsed}}\'<br>{{score}}\
                {{else}}\
                    {{date}}<br>{{time}}\
                {{/is_inPlay}}\
                </td>\
                <td class="game {{parity @index}}" data-game-id="{{id}}" data-type="fixture" title="{{name}}">\
                    <div class="gameName">{{homeTeam name}} - {{awayTeam name}}</div>\
                </td>\
                <td class="favorite {{parity @index}}" title="Favorito">{{> favorite}}</td>\
                <td class="statistics {{parity @index}}" title="Estatística">{{#if external_id}}{{> statistics_button}}{{/if}}</td>\
                <td class="separator">&nbsp;</td>\
                {{#each markets}}\
                    {{#if_in market_type_id "2,15,306,6662,7469,8133"}}\
                        {{> get_selection outcomeId=1 fixture=.. index=@../index}}\
                    {{/if_in}}\
                    {{#if_in market_type_id "322,6734"}}\
                        {{> get_selection outcomeId=25 fixture=.. index=@../index}}\
                    {{/if_in}}\
                    <td class="separator"></td>\
                        {{> get_selection outcomeId=2 fixture=.. index=@../index}}\
                    <td class="separator"></td>\
                    {{#if_in market_type_id "2,15,306,6662,7469,8133"}}\
                        {{> get_selection outcomeId=3 fixture=.. index=@../index}}\
                    {{/if_in}}\
                    {{#if_in market_type_id "322,6734"}}\
                        {{> get_selection outcomeId=26 fixture=.. index=@../index}}\
                    {{/if_in}}\
                {{/each}}\
                <td class="separator">&nbsp;</td>\
                <td class="marketsCount {{parity @index}}" data-game-id="{{id}}" data-type="fixture">+{{markets_count}}</td>\
            </tr>\
        {{/each}}\
    </table>\
    {{#if options.expand}}\
        <div class="fixtures-more">\
            <span>Todos &nbsp; <i class="cp-plus"></i></span>\
        </div>\
    {{/if}}\
');
