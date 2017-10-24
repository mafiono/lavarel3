Handlebars.registerPartial('fixtures', '\
    {{#each sports}}\
        <table class="fixtures noselect" data-sport="{{sportId}}">\
            <tr class="header">\
                <th class="date {{../options.mode}}">\
                    {{#if_eq ../options.mode "highgames"}}\
                        <i class="cp-document-star"></i>\
                    {{/if_eq}}\
                    {{#if_in ../options.mode "sport,search,favorites"}}\
                        <i class="{{sport_icon sportId}} {{#if ../options.live}}cp-spin-4x{{/if}}"></i>\
                    {{/if_in}}\
                </th>\
                <th class="{{../options.mode}} game">\
                    {{#if_in ../options.mode "sport,search,favorites,highlights"}}\
                        <span class="icon"><i class="{{sport_icon sportId}} {{#if ../options.live}}cp-spin-4x{{/if}}"></i> &nbsp; </span>\
                    {{/if_in}}\
                    <span {{#if_not ../options.live}}style="width: auto"{{/if_not}}>\
                        {{#if_in ../options.mode "favorites,search"}}\
                            {{sport_name sportId}}\
                        {{else}}\
                            {{../options.sportName}}\
                        {{/if_in}}\
                    </span>\
                    {{#if ../options.live}}<div class="gameLive">DIRETO</div>{{/if}}\
                </th>\
                <th class="prematch {{../options.mode}} {{#if ../options.live}}live{{/if}}" colspan="2">{{#if ../options.live}}DIRETO{{/if}}</th>\
                <th class="separator">&nbsp;</th>\
                <th class="selection {{#if_in ../sport_id "24,91189"}}hidden{{/if_in}}twoResults{{/if_in}}">1</th>\
                <th class="selectionSeparator {{#if_eq sportId 24}}hidden{{/if_eq}}"></th>\
                <th class="selection {{#if_in ../sport_id "24,91189"}}hidden{{/if_in}}hidden{{/if_in}}">X</th>\
                <th class="selectionSeparator"></th>\
                <th class="selection {{#if_in ../sport_id "24,91189"}}hidden{{/if_in}}twoResults{{/if_in}}">2</th>\
                <th class="separator">&nbsp;</th>\
                <th class="marketCount {{../options.mode}}"><i class="cp-caret-down"></i></th>\
            </tr>\
            {{#each fixtures}}\
                <tr class="fixture">\
                    <td class="{{#is_inPlay}} score {{else}} date {{/is_inPlay}} {{parity @index}}">\
                    {{#is_inPlay}}\
                        {{elapsed}}<br>{{score}}\
                    {{else}}\
                        {{date}}<br>{{time}}\
                    {{/is_inPlay}}\
                    </td>\
                    <td class="game {{parity @index}}" data-game-id="{{id}}" data-type="fixture" title="{{name}}">\
                        <div class="gameName">\
                            <span>{{homeTeam name}} <span>-</span><br> {{awayTeam name}}</span>\
                            <div class="gameNameMobile">\
                                {{#is_inPlay}}\
                                    <span>{{score}} | </span>{{elapsed}}\
                                {{else}}\
                                    {{date}} {{time}}\
                                {{/is_inPlay}}\
                                {{> favorite}}\
                            </div>\
                        </div>\
                    </td>\
                    <td class="favorite {{parity @index}}" title="Favorito">{{> favorite}}</td>\
                    <td class="statistics {{parity @index}}" title="Estatística">{{#if external_id}}{{> statistics_button}}{{/if}}</td>\
                    <td class="separator">&nbsp;</td>\
                    {{#each markets}}\
                        {{#if_eq trading_status "Open"}}\
                            <td class="selection {{parity @../index}}">\
                                {{#if_in market_type_id "2,15,306,6662,6734,7469,8133"}}\
                                    {{> get_selection outcomeId=1 fixture=.. index=@../index}}\
                                {{/if_in}}\
                                {{#if_in market_type_id "322"}}\
                                    {{> get_selection outcomeId=25 fixture=.. index=@../index}}\
                                {{/if_in}}\
                            </td>\
                            <td class="separator {{#if_in ../sport_id "24,91189"}}hidden{{/if_in}}"></td>\
                            <td class="selection {{parity @../index}} {{#if_in ../sport_id "24,91189"}}hidden{{/if_in}}">\
                                {{> get_selection outcomeId=2 fixture=.. index=@../index}}\
                            </td>\
                            <td class="separator"></td>\
                            <td class="selection {{parity @../index}}">\
                                 {{#if_in market_type_id "2,15,306,6662,6734,7469,8133"}}\
                                    {{> get_selection outcomeId=3 fixture=.. index=@../index}}\
                                {{/if_in}}\
                                {{#if_in market_type_id "322"}}\
                                    {{> get_selection outcomeId=26 fixture=.. index=@../index}}\
                                {{/if_in}}\
                            </td>\
                        {{else}}\
                            <td class="selectionSuspended" colspan="{{#if_eq ../sport_id 24}}3{{else}}5{{/if_eq}}">\
                                <div>\
                                    <span>Suspenso {{markets_count}}</span>\
                                </div>\
                            </td>\
                        {{/if_eq}}\
                    {{/each}}\
                    <td class="separator">&nbsp;</td>\
                    <td class="marketsCount {{parity @index}}" data-game-id="{{id}}" data-type="fixture">+{{markets_count}}</td>\
                </tr>\
            {{/each}}\
        </table>\
        {{#if ../options.expand}}\
            <div class="fixtures-more">\
                <span>Todos &nbsp; <i class="cp-plus"></i></span>\
            </div>\
        {{/if}}\
    {{/each}}\
');

Handlebars.registerPartial('get_selection', '\
    {{#each selections}}\
        {{#if_eq outcome_id ../outcomeId}}\
            {{> selection fixture=../fixture market=..}}\
        {{/if_eq}}\
    {{/each}}\
');

