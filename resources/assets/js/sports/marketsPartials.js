Handlebars.registerPartial('get_selection', '\
    <td class="selection {{type}} {{parity index}}">\
        {{#each selections}}\
            {{#if_eq outcome_id ../outcomeId}}\
                {{> selection fixture=../fixture market=..}}\
            {{/if_eq}}\
        {{/each}}\
    </td>\
');

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
            data-game-id="{{fixture.id}}"\
            data-game-name="{{fixture.name}}"\
            data-game-date="{{fixture.start_time_utc}}"\
            data-event-id="{{id}}"\
            data-event-name="{{#if_eq market.market_type.is_handicap 1}}{{market.handicap}} - {{/if_eq}}{{name}}"\
            data-event-price="{{decimal}}"\
            data-market-id="{{market.id}}"\
            data-market-name="{{market.market_type.name}}"\
            data-type="odds">{{decimal}}</button>\
    {{/is_selection_valid}}\
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
        <div class="markets noselect">\
            {{#does_not_have_scoreCenter}}\
                <div class="header">\
                    <span>{{name}}</span>\
                    <i id="markets-close" class="fa fa-times close" aria-hidden="true"></i>\
                    {{#if external_id}}\
                        <i id="markets-statistics" class="fa fa-bar-chart" aria-hidden="true"></i>\
                    {{/if}}\
                </div>\
            {{/does_not_have_scoreCenter}}\
            {{#with marketsSet}}\
                {{! Football }}\
                {{> market_singleRow3Col markets=[2] fixture=..}} {{! Match Result }}\
                {{> market_singleRow2Col markets=[122] fixture=..}} {{! Draw No Bet }}\
                {{> market_singleRow3Col markets=[7202] fixture=..}} {{! Double Chance }}\
                {{> market_singleRow2Col markets=[9535] fixture=..}} {{! Half-time Odd or Even Total }}\
                {{> market_singleRow2Col markets=[7354] fixture=..}} {{! Odd or Even Total }}\
                {{> market_singleRow3Col markets=[6832] fixture=..}} {{! Half-time Result }}\
                {{> market_singleRow3Col markets=[7591] fixture=..}} {{! 2nd Half Result }}\
                {{> market_singleRow3Col markets=[9536] fixture=..}} {{! Half-time First Team To Score }}\
                {{> market_singleRow3Col markets=[295] fixture=..}} {{! First Team To Score }}\
                {{> market_singleRow3Col markets=[6911] fixture=..}} {{! Last Team to Score }}\
                {{> market_singleRow3Col markets=[25] fixture=..}} {{! Highest Scoring Half }}\
                {{> market_singleRow2Col markets=[8480] fixture=..}} {{! Home Team Clean Sheet }}\
                {{> market_singleRow2Col markets=[8481] fixture=..}} {{! Away Team Clean Sheet }}\
                {{> market_singleRow2Col markets=[169] fixture=..}} {{! To Win to Nil }}\
                {{> market_singleRow2Col markets=[10459] fixture=..}} {{! Half-time Both Teams To Score }}\
                {{> market_singleRow2Col markets=[7079] fixture=..}} {{! Both Teams To Score }}\
                {{> market_multiRow3ColUnlabeled markets=[7086] fixture=..}} {{! Home Team Total Goals }}\
                {{> market_multiRow3ColUnlabeled markets=[7087] fixture=..}} {{! Away Team Total Goals }}\
                {{> market_multiRow3ColUnlabeled markets=[253] fixture=..}} {{! Total Goals }}\
                {{> market_multiRow2Col markets=[9498] fixture=..}} {{! Home Team Total Goals Over/Under }}\
                {{> market_multiRow2Col markets=[259] fixture=..}} {{! Over/Under }}\
                {{> market_multiRow3Col markets=[105] fixture=..}} {{! Handicap }}\
                {{> market_multiRow3ColUnlabeled markets=[91] fixture=..}} {{! Correct Score }}\
                {{> market_multiRow3ColUnlabeled markets=[7809] fixture=..}} {{! 2nd Half Correct Score }}\
                {{> market_multiRow3ColUnlabeled markets=[170] fixture=..}} {{! Half-time Correct Score }}\
                {{> market_multiRow3ColUnlabeled markets=[12] fixture=..}} {{! First Goalscorer }}\
                {{> market_multiRow3ColUnlabeled markets=[13] fixture=..}} {{! Last Goalscorer }}\
                {{> market_multiRow3ColUnlabeled markets=[14] fixture=..}} {{! Anytime Goalscorer }}\
                {{> market_multiRow3ColUnlabeled markets=[42] fixture=..}} {{! To score a Hat-trick }}\
                {{! Tennis }}\
                {{> market_singleRow2Col markets=[322] fixture=..}} {{! Match Result }}\
                {{> market_singleRow2Col markets=[77] fixture=..}} {{! First Set Winner}}\
                {{> market_singleRow2Col markets=[6599] fixture=..}} {{! Second Set Winner}}\
                {{> market_multiRow2Col markets=[8660] fixture=..}} {{! Set Handicap}}\
                {{> market_multiRow3ColUnlabeled markets=[120] fixture=..}} {{! Set Betting }}\
                {{> market_multiRow3ColUnlabeled markets=[7172] fixture=..}} {{! First Set Correct Score }}\
                {{> market_multiRow3ColUnlabeled markets=[7530] fixture=..}} {{! Second Set Correct Score }}\
                {{! Basketball }}\
                {{> market_singleRow3Col markets=[306] fixture=..}} {{! Match Result }}\
                {{> market_singleRow2Col markets=[7351] fixture=..}} {{! Odd or Even Total }}\
                {{> market_multiRow3Col markets=[193] fixture=..}} {{! Handicap Result }}\
                {{> market_singleRow3Col markets=[6950] fixture=..}} {{! 1st Quarter Result }}\
                {{> market_multiRow3Col markets=[6859] fixture=..}} {{! 1st Half Handicap }}\
                {{> market_multiRow2Col markets=[6736] fixture=..}} {{! 1st Quarter Total Points }}\
                {{> market_multiRow2Col markets=[6851] fixture=..}} {{! 2nd Quarter Total Points }}\
                {{> market_multiRow3ColUnlabeled markets=[6957] fixture=..}} {{! Half-time/Full-time }}\
                {{> market_multiRow2Col markets=[286] fixture=..}} {{! Total Points }}\
                {{> market_multiRow2Col markets=[147] fixture=..}} {{! Point Spread }}\
                {{! Handball }}\
                {{> market_singleRow3Col markets=[6662] fixture=..}} {{! Match Result }}\
                {{> market_singleRow2Col markets=[6690] fixture=..}} {{! Draw No Bet }}\
                {{> market_singleRow3Col markets=[7290] fixture=..}} {{! Double Chance }}\
                {{> market_singleRow2Col markets=[7024] fixture=..}} {{! Total Goals Odd or Even }}\
                {{> market_singleRow3Col markets=[7381] fixture=..}} {{! Half-time Result }}\
                {{> market_singleRow3Col markets=[8377] fixture=..}} {{! 2nd Half Result }}\
                {{> market_multiRow3ColUnlabeled markets=[7307] fixture=..}} {{! Half-time/Full-time }}\
                {{> market_multiRow2Col markets=[6799] fixture=..}} {{! Half-time Handicap }}\
                {{> market_multiRow2Col markets=[194] fixture=..}} {{! Handicap Result }}\
                {{> market_multiRow3Col markets=[9784] fixture=..}} {{! Handicap Result (With Draw)}}\
                {{> market_multiRow2Col markets=[6855] fixture=..}} {{! Half-time Total Goals Over/Under }}\
                {{> market_multiRow2Col markets=[6663] fixture=..}} {{! Total Goals Over/Under }}\
                {{! Futsal}}\
                {{> market_singleRow3Col markets=[7469] fixture=..}} {{! Match Result }}\
                {{> market_singleRow2Col markets=[8731] fixture=..}} {{! Draw No Bet }}\
                {{> market_singleRow2Col markets=[8733] fixture=..}} {{! Odd or Even Total }}\
                {{> market_multiRow2Col markets=[7471] fixture=..}} {{! Handicap }}\
                {{! Rugby League}}\
                {{> market_singleRow3Col markets=[8133] fixture=..}} {{! Match Result }}\
                {{! Rugby Union}}\
                {{> market_singleRow3Col markets=[15] fixture=..}} {{! Match Result }}\
                {{! Volleyball}}\
                {{> market_singleRow2Col markets=[6734] fixture=..}} {{! Match Result }}\
                {{> market_multiRow3ColUnlabeled markets=[6777] fixture=..}} {{! Set Betting }}\
                {{> market_singleRow2Col markets=[6779] fixture=..}} {{! First Set Winner }}\
                {{> market_singleRow2Col markets=[7012] fixture=..}} {{! Second Set Winner }}\
                {{> market_singleRow2Col markets=[7013] fixture=..}} {{! Third Set Winner }}\
                {{> market_singleRow2Col markets=[8576] fixture=..}} {{! Fourth Set Winner }}\
                <div id="markets-more" class="markets-more hidden">\
                    <span class="markets-text more">Outras &nbsp; <i class="fa fa-plus" aria-hidden="true"></i></span>\
                </div>\
                <div id="markets-others" class="hidden">\
                </div>\
            {{/with}}\
            <div id="markets-others" class="hidden">\
            </div>\
        </div>\
    {{/each}}\
');

Handlebars.registerPartial('market_singleRow2Col','\
    {{#with markets.[0]}}\
        {{#if_eq selections.length 2}}\
            <div class="title">\
                {{market_type.name}}\
                <i class="fa {{#if (lookup @root.collapsed id)}}fa-plus{{else}}fa-caret-down{{/if}}" data-market-id="{{id}}"></i>\
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
    {{/with}}\
');

Handlebars.registerPartial('market_singleRow3Col','\
    {{#with markets.[0]}}\
        {{#if_eq selections.length 3}}\
            <div class="title">\
                {{market_type.name}}\
                <i class="fa {{#if (lookup @root.collapsed id)}}fa-plus{{else}}fa-caret-down{{/if}}" data-market-id="{{id}}"></i>\
            </div>\
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
    {{/with}}\
');

Handlebars.registerPartial('market_multiRow2Col','\
    {{#with markets}}\
        <div class="title">\
            {{[0].market_type.name}}\
            <i class="fa {{#if (lookup @root.collapsed [0].id)}}fa-plus{{else}}fa-caret-down{{/if}}" data-market-id="{{[0].id}}"></i>\
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
                {{#if_eq selections.length 2}}\
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
                    </tr>\
                {{/if_eq}}\
            {{/each}}\
        </table>\
    {{/with}}\
');

Handlebars.registerPartial('market_multiRow3Col','\
    {{#with markets}}\
        <div class="title">\
            {{[0].market_type.name}}\
            <i class="fa {{#if (lookup @root.collapsed [0].id)}}fa-plus{{else}}fa-caret-down{{/if}}" data-market-id="{{[0].id}}"></i>\
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
            {{/each}}\
        </table>\
    {{/with}}\
');

Handlebars.registerPartial('market_multiRow3ColUnlabeled','\
    {{#with markets}}\
        <div class="title">\
            {{[0].market_type.name}}\
            <i class="fa {{#if (lookup @root.collapsed [0].id)}}fa-plus{{else}}fa-caret-down{{/if}}" data-market-id="{{[0].id}}"></i>\
        </div>\
        <table class="multiRow3ColsUnlabeled {{#if (lookup @root.collapsed [0].id)}}hidden{{/if}}">\
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
        </table>\
    {{/with}}\
');

Handlebars.registerPartial('unavailable_markets', '\
    <div class="markets-unavailable">\
        <p>Mercados indisponíveis.</p>\
    </div>\
');