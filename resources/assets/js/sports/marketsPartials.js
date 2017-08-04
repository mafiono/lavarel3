Handlebars.registerPartial('get_selection_name', '\
    {{#each selections}}\
        {{#if_eq outcome_id ../outcome}}\
            {{name}}\
        {{/if_eq}}\
    {{/each}}\
');

Handlebars.registerPartial('selection', '\
    {{#is_selection_valid}}\
        <button class="selection-button"\
            data-sport-id="{{fixture.sport_id}}"\
            data-game-id="{{fixture.id}}"\
            data-game-name="{{fixture.name}}"\
            data-game-date="{{fixture.start_time_utc}}"\
            data-event-id="{{id}}"\
            data-event-name="{{#if_eq market.market_type.is_handicap 1}}{{market.handicap}} - {{/if_eq}}{{name}}"\
            data-event-price="{{decimal}}"\
            data-market-id="{{market.id}}"\
            data-market-name="{{market.market_type.name}}"\
            data-type="odds">{{decimal}}{{fixture.sport.name}}</button>\
    {{/is_selection_valid}}\
');

Handlebars.registerPartial('favorite', '\
    <button class="cp-star-full markets-button-favorite"\
        data-game-id="{{id}}"\
        data-game-name="{{name}}"\
        data-game-date="{{start_time_utc}}"\
        data-type="favorite"> \
    </button>\
');

Handlebars.registerPartial('statistics_button', '\
    <button id="statistics-{{id}}"\
        class="cp-stats-dots markets-button-statistics"\
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
        <div class="markets noselect">\
            {{#does_not_have_scoreCenter}}\
                <div class="header">\
                    <span>{{name}}</span>\
                    <i id="markets-close" class="cp-cross close"></i>\
                    {{#if external_id}}\
                        <i id="markets-statistics" class="cp-stats-dots"></i>\
                    {{/if}}\
                </div>\
            {{/does_not_have_scoreCenter}}\
            {{#each marketsOrder}}\
                {{> market_template type=template markets=list fixture=..}}\
                <div id="markets-more" class="markets-more hidden">\
                    <span class="markets-text more">Outras &nbsp; <i class="cp-plus"></i></span>\
                </div>\
                <div id="markets-others" class="hidden">\
                </div>\
            {{/each}}\
            <div id="markets-others" class="hidden">\
            </div>\
        </div>\
    {{/each}}\
');
// {{if_template template markets=list fixture=..}}\
Handlebars.registerPartial('market_template','\
    {{#if_eq template "market_multiRow2Col"}} {{> market_multiRow2Col markets=markets fixture=fixture }} {{/if_eq}}\
    {{#if_eq template "market_multiRow3Col"}} {{> market_multiRow3Col markets=markets fixture=fixture }} {{/if_eq}}\
    {{#if_eq template "market_multiRow3ColUnlabeled"}} {{> market_multiRow3ColUnlabeled markets=markets fixture=fixture }} {{/if_eq}}\
    {{#if_eq template "market_singleRow2Col"}} {{> market_singleRow2Col markets=markets fixture=fixture }} {{/if_eq}}\
    {{#if_eq template "market_singleRow3Col"}} {{> market_singleRow3Col markets=markets fixture=fixture }} {{/if_eq}}\
');

Handlebars.registerPartial('market_singleRow2Col','\
    {{#with markets.[0]}}\
        {{#if_eq selections.length 2}}\
            {{#if_eq trading_status "Open"}}\
                <div class="title" data-market-id="{{id}}">\
                    <span>{{market_type.name}}</span>\
                    <i class="{{#if (lookup @root.collapsed id)}}cp-plus{{else}}cp-caret-down{{/if}}"></i>\
                </div>\
                <table class="singleRow2Cols {{#if (lookup @root.collapsed id)}}hidden{{/if}}">\
                    <tr class="header">\
                        <th class="selection" title="{{selections.[0].name}}">{{selections.[0].name}}</th>\
                        <th class="separator"></th>\
                        <th class="selection" title="{{selections.[1].name}}">{{selections.[1].name}}</th>\
                    </tr>\
                    <tr class="row">\
                        <td class="selection">\
                            {{#with selections.[0]}}\
                                {{> selection fixture=../../fixture market=..}}\
                            {{/with}}\
                        </td>\
                        <td class="separator"></td>\
                        <td class="selection">\
                            {{#with selections.[1]}}\
                                {{> selection fixture=../../fixture market=..}}\
                            {{/with}}\
                        </td>\
                    </tr>\
                </table>\
            {{/if_eq}}\
        {{/if_eq}}\
        {{#if_in trading_status "Suspended,Closed"}}\
            <div class="title" data-market-id="{{id}}">\
                <span>{{market_type.name}}</span>\
                    <i class="{{#if (lookup @root.collapsed id)}}cp-plus{{else}}cp-caret-down{{/if}}"></i>\
            </div>\
            <table class="singleRow2Cols {{#if (lookup @root.collapsed id)}}hidden{{/if}}">\
                <td>\
                    <div class="markets-unavailable">\
                        <p>Suspenso</p>\
                    </div>\
                </td>\
            </table>\
        {{/if_in}}\
    {{/with}}\
');

Handlebars.registerPartial('market_singleRow3Col','\
    {{#with markets.[0]}}\
        {{#if_eq selections.length 3}}\
            <div class="title"  data-market-id="{{id}}">\
                <span>{{market_type.name}}</span>\
                <i class="{{#if (lookup @root.collapsed id)}}cp-plus{{else}}cp-caret-down{{/if}}"></i>\
            </div>\
            {{#if_eq trading_status "Open"}}\
            <table class="singleRow3Cols {{#if (lookup @root.collapsed id)}}hidden{{/if}}">\
                <tr class="header">\
                    <th class="selection" title="{{selections.[0].name}}">{{selections.[0].name}}</th>\
                    <th class="separator"></th>\
                    <th class="selection" title="{{selections.[1].name}}">{{selections.[1].name}}</th>\
                    <th class="separator"></th>\
                    <th class="selection" title="{{selections.[2].name}}">{{selections.[2].name}}</th>\
                </tr>\
                <tr class="row">\
                    <td class="selection">\
                        {{#with selections.[0]}}\
                            {{> selection fixture=../../fixture market=..}}\
                        {{/with}}\
                    </td>\
                    <td class="separator"></td>\
                    <td class="selection">\
                        {{#with selections.[1]}}\
                            {{> selection fixture=../../fixture market=..}}\
                        {{/with}}\
                    </td>\
                    <td class="separator"></td>\
                    <td class="selection">\
                        {{#with selections.[2]}}\
                            {{> selection fixture=../../fixture market=..}}\
                        {{/with}}\
                    </td>\
                </tr>\
            </table>\
        {{/if_eq}}\
        {{/if_eq}}\
        {{#if_in trading_status "Suspended,Closed"}}\
         <table class="singleRow3Cols {{#if (lookup @root.collapsed id)}}hidden{{/if}}">\
         <td>\
         <div class="markets-unavailable">\
        <p>Suspenso</p>\
         </div>\
         </td>\
         </table>\
        {{/if_in}}\
    {{/with}}\
');

Handlebars.registerPartial('market_multiRow2Col','\
    {{#with markets}}\
        <div class="title" data-market-id="{{[0].id}}">\
            <span>{{[0].market_type.name}}</span>\
            <i class="{{#if (lookup @root.collapsed [0].id)}}cp-plus{{else}}cp-caret-down{{/if}}"></i>\
        </div>\
        <table class="multiRow2Cols {{#if (lookup @root.collapsed [0].id)}}hidden{{/if}}">\
            {{#each this}}\
                {{#if_eq @index 0}}\
                    <tr class="header">\
                        <th class="handicap"></th>\
                        <th class="separator"></th>\
                        <th class="selection" title="{{selections.[0].name}}">{{selections.[0].name}}</th>\
                        <th class="separator"></th>\
                        <th class="selection" title="{{selections.[1].name}}">{{selections.[1].name}}</th>\
                    </tr>\
                {{/if_eq}}\
                {{#if_eq trading_status "Open"}}\
                {{#if_eq selections.length 2}}\
                    <tr class="row">\
                        <td class="handicap">{{#if_eq market_type.is_handicap 1}}{{handicap}}{{/if_eq}}</td>\
                        <td class="separator"></td>\
                        <td class="selection {{parity @index}}">\
                            {{#with selections.[0]}}\
                                {{> selection fixture=../../../fixture market=..}}\
                            {{/with}}\
                        </td>\
                        <td class="handicap">{{#if_eq market_type.is_handicap 1}}{{handicap}}{{/if_eq}}</td>\
                        <td class="separator"></td>\
                        <td class="selection {{parity @index}}">\
                            {{#with selections.[1]}}\
                                {{> selection fixture=../../../fixture market=..}}\
                            {{/with}}\
                        </td>\
                    </tr>\
                    {{/if_eq}}\
                    {{/if_eq}}\
                      {{#if_in trading_status "Suspended,Closed"}}\
                      <tr class="row">\
                       <td class="handicap">{{#if_eq market_type.is_handicap 1}}{{handicap}}{{/if_eq}}</td>\
                       <td class="separator"></td>\
                      <td>\
                      <div class="market-unavailable">\
                     <p>Suspenso</p>\
                    </div>\
                    </td>\
                    <td class="separator"></td>\
                     <td>\
                      <div class="market-unavailable">\
                     <p>Suspenso</p>\
                    </div>\
                    </td>\
                   </tr>\
                        {{/if_in}}\
                     {{/each}}\
                     </table>\
                        {{/with}}\
');

Handlebars.registerPartial('market_multiRow3Col','\
    {{#with markets}}\
        <div class="title" data-market-id="{{[0].id}}">\
            <span>{{[0].market_type.name}}</span>\
            <i class="{{#if (lookup @root.collapsed [0].id)}}cp-plus{{else}}cp-caret-down{{/if}}"></i>\
        </div>\
        <table class="multiRow3Cols {{#if (lookup @root.collapsed [0].id)}}hidden{{/if}}">\
            {{#each this}}\
                {{#if_eq @index 0}}\
                    <tr class="header">\
                        <th class="handicap"></th>\
                        <th class="separator"></th>\
                        <th class="selection" title="{{selections.[0].name}}">{{selections.[0].name}}</th>\
                        <th class="separator"></th>\
                        <th class="selection title="{{selections.[1].name}}">{{selections.[1].name}}</th>\
                        <th class="separator"></th>\
                        <th class="selection" title="{{selections.[2].name}}">{{selections.[2].name}}</th>\
                    </tr>\
                {{/if_eq}}\
                {{#if_eq selections.length 3}}\
                {{#if_eq trading_status "Open"}}\
                    <tr class="row">\
                        <td class="handicap">{{#if_eq market_type.is_handicap 1}}{{handicap}}{{/if_eq}}</td>\
                        <td class="separator"></td>\
                        <td class="selection {{parity @index}}">\
                            {{#with selections.[0]}}\
                                {{> selection fixture=../../../fixture market=..}}\
                            {{/with}}\
                        </td>\
                        <td class="separator"></td>\
                        <td class="selection {{parity @index}}">\
                            {{#with selections.[1]}}\
                                {{> selection fixture=../../../fixture market=..}}\
                            {{/with}}\
                        </td>\
                        <td class="separator"></td>\
                        <td class="selection {{parity @index}}">\
                            {{#with selections.[2]}}\
                                {{> selection fixture=../../../fixture market=..}}\
                            {{/with}}\
                        </td>\
                    </tr>\
                {{/if_eq}}\
               {{/if_eq}}\
               {{#if_eq trading_status "Suspended"}}\
                 <tr class="row">\
                        <td class="handicap">{{#if_eq market_type.is_handicap 1}}{{handicap}}{{/if_eq}}</td>\
                        <td class="separator"></td>\
                        <td class="selection {{parity @index}}">\
                             <div class="market-unavailable">\
                         <p>Suspenso</p>\
                             </div>\
                        </td>\
                        <td class="separator"></td>\
                        <td class="selection {{parity @index}}">\
                              <div class="market-unavailable">\
                         <p>Suspenso</p>\
                             </div>\
                        </td>\
                        <td class="separator"></td>\
                        <td class="selection {{parity @index}}">\
                              <div class="market-unavailable">\
                         <p>Suspenso</p>\
                             </div>\
                        </td>\
                    </tr>\
                    {{/if_eq}}\
            {{/each}}\
        </table>\
    {{/with}}\
');

Handlebars.registerPartial('market_multiRow3ColUnlabeled','\
    {{#with markets}}\
        <div class="title" data-market-id="{{[0].id}}">\
            <span>{{[0].market_type.name}}</span>\
            <i class="{{#if (lookup @root.collapsed [0].id)}}cp-plus{{else}}cp-caret-down{{/if}}"></i>\
        </div>\
        <table class="multiRow3ColsUnlabeled {{#if (lookup @root.collapsed [0].id)}}hidden{{/if}}">\
        {{#if_eq [0].trading_status "Open"}}\
            {{#each [0].selections}}\
                {{#if_eq (mod @index 3) 0}}\
                    <tr>\
                        <td class="selection">\
                            <table>\
                                <tr class="header">\
                                    <th class="selection" title="{{name}}">{{name}}</th>\
                                </tr>\
                                <tr class="row">\
                                    <td class="selection">\
                                        {{> selection fixture=../../fixture market=../[0]}}\
                                    </td>\
                                </tr>\
                            </table>\
                        </td>\
                {{/if_eq}}\
                {{#if_eq (mod @index 3) 1}}\
                    <td class="separator"></td>\
                    <td class="selection">\
                        <table>\
                            <tr class="header">\
                                <th class="selection" title="{{name}}">{{name}}</th>\
                            </tr>\
                            <tr class="row">\
                                <td class="selection">\
                                    {{> selection fixture=../../fixture market=../[0]}}\
                                </td>\
                            </tr>\
                        </table>\
                    </td>\
                {{/if_eq}}\
                {{#if_eq (mod @index 3) 2}}\
                        <td class="separator"></td>\
                        <td class="selection">\
                            <table>\
                                <tr class="header" title="{{name}}">\
                                    <th class="selection">{{name}}</th>\
                                </tr>\
                                <tr class="row">\
                                    <td class="selection">\
                                        {{> selection fixture=../../fixture market=../[0]}}\
                                    </td>\
                                </tr>\
                            </table>\
                        </td>\
                    </tr>\
                {{/if_eq}}\
            {{/each}}\
            {{#if_eq (mod [0].selections.length 3) 1}}\
                <td class="separator"></td>\
                <td class="selection">\
                    <table>\
                        <tr class="header">\
                            <th class="selection">&nbsp;</th>\
                        </tr>\
                        <tr class="row">\
                            <td class="selection"></td>\
                        </tr>\
                    </table>\
                </td>\
                <td class="separator"></td>\
                    <td class="selection">\
                        <table>\
                            <tr class="header">\
                                <th class="selection">&nbsp;</th>\
                            </tr>\
                            <tr class="row">\
                                <td class="selection"></td>\
                            </tr>\
                        </table>\
                    </td>\
                </tr>\
            {{/if_eq}}\
            {{#if_eq (mod [0].selections.length 3) 2}}\
                <td class="separator"></td>\
                    <td class="selection">\
                        <table>\
                            <tr class="header">\
                                <th class="selection">&nbsp;</th>\
                            </tr>\
                            <tr class="row">\
                                <td class="selection"></td>\
                            </tr>\
                        </table>\
                    </td>\
                </tr>\
            {{/if_eq}}\
        {{else}}\
            <tr>\
                <td>\
                    <div class="markets-unavailable">\
                        <p>Suspenso</p>\
                    </div>\
                </td>\
         </tr>\
        {{/if_eq}}\
        </table>\
    {{/with}}\
');

Handlebars.registerPartial('unavailable_markets', '\
    <div class="markets-unavailable">\
        <p>Mercados Fechados.</p>\
    </div>\
');