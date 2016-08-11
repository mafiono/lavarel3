// <i class="fa fa-futbol-o" aria-hidden="true" style=""></i>
Handlebars.registerPartial('fixtures', '\
    <table class="fixtures">\
        <tr class="header {{options.mode}}">\
            <th class="date">\
                {{#if_eq options.mode "sport"}}\
                    <i class="fa fa-futbol-o" aria-hidden="true"></i>\
                {{/if_eq}}\
            </th>\
            <th class="game"><span>{{options.sportName}}</span></th>\
            <th class="{{#if options.live}}live{{/if}}" colspan="2">{{#if options.live}}DIRETO{{/if}}</th>\
            <th class="separator">&nbsp;</th>\
            <th class="selection">1</th>\
            <th class="selectionSeparator"></th>\
            <th class="selection">X</th>\
            <th class="selectionSeparator"></th>\
            <th class="selection">2</th>\
            <th class="separator">&nbsp;</th>\
            <th class="marketCount"><i class="fa fa-caret-down"></i></th>\
        </tr>\
        {{#each fixtures}}\
            <tr class="fixture">\
                <td class="date {{parity @index}}">\
                {{#if_eq is_over 0}}\
                    {{> match_state }}\
                {{else}}\
                    {{date}}<br>{{time}}\
                {{/if_eq}}\
                </td>\
                <td class="game {{parity @index}}" data-game-id="{{id}}" data-type="fixture">{{name}}</td>\
                <td class="favorite {{parity @index}}">{{> favorite}}</td>\
                <td class="statistics {{parity @index}}">{{#if external_id}}{{> statistics_button}}{{/if}}</td>\
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
    {{#if options.expand}}\
        <div class="fixtures-more">\
            <span>Todos &nbsp; <i class="fa fa-plus"></i></span>\
        </div>\
    {{/if}}\
');
