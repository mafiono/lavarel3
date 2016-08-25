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
            {{#with marketSet}}\
                {{> market_1Row3Cols market_type=2}}\
            {{/with}}\
            {{#each marketsSet}}\
                {{@key}}\
                {{> market_singleRow2Cols market_type_id=@key fixture=.. markets=this}}\
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

Handlebars.registerPartial('market_1Row3Cols','\
    xxx\
    {{#with (lookup this market_type)}}\
        {{this.[0]}}\
    {{/with}}\
');

Handlebars.registerPartial('market_singleRow3Cols','\
    {{#if_in market_type_id "2,295,306,6832,7202,7591"}}\
        {{#with markets.[0]}}\
            {{#if_eq selections.length 3}}\
                <div class="title">\
                    {{market_type.name}}\
                </div>\
                <table class="singleRow3Cols">\
                    <tr class="header">\
                        <th class="selection">{{selections.[0].outcome.name}}</th>\
                        <th class="separator"></th>\
                        <th class="selection">{{selections.[1].outcome.name}}</th>\
                        <th class="separator"></th>\
                        <th class="selection">{{selections.[2].outcome.name}}</th>\
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
    {{/if_in}}\
');

Handlebars.registerPartial('market_singleRow2Cols','\
    {{#if_in market_type_id "82,91,122,7354"}}\
        {{#with markets.[0]}}\
            {{#if_eq selections.length 2}}\
                <div class="title">\
                    {{market_type.name}}\
                </div>\
                <table class="singleRow2Cols">\
                    <tr class="header">\
                        <th class="selection">{{selections.[0].outcome.name}}</th>\
                        <th class="separator"></th>\
                        <th class="selection">{{selections.[1].outcome.name}}</th>\
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
    {{/if_in}}\
');

Handlebars.registerPartial('market_multiRow2Cols','\
    {{#if_in market_type_id "82,259"}}\
        <div class="title">\
            {{markets.[0].market_type.name}}\
        </div>\
        <table class="multiRow2Cols">\
            {{#each markets}}\
                {{#if_eq @index 0}}\
                    <tr class="header">\
                        <th class="handicap"></th>\
                        <th class="separator"></th>\
                        <th class="selection">{{selections.[0].outcome.name}}</th>\
                        <th class="separator"></th>\
                        <th class="selection">{{selections.[1].outcome.name}}</th>\
                    </tr>\
                {{/if_eq}}\
                {{#if_eq selections.length 2}}\
                    <tr class="row">\
                        <td class="handicap">{{#if_eq market_type.is_handicap 1}}{{handicap}}{{/if_eq}}</td>\
                        <td class="separator"></td>\
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
                {{/if_eq}}\
            {{/each}}\
        </table>\
    {{/if_in}}\
');

Handlebars.registerPartial('market_multiRow3Cols','\
    {{#if_in market_type_id "105"}}\
        <div class="title">\
            {{markets.[0].market_type.name}}\
        </div>\
        <table class="multiRow3Cols">\
            {{#each markets}}\
                {{#if_eq @index 0}}\
                    <tr class="header">\
                        <th class="handicap"></th>\
                        <th class="separator"></th>\
                        <th class="selection">{{selections.[0].outcome.name}}</th>\
                        <th class="separator"></th>\
                        <th class="selection">{{selections.[1].outcome.name}}</th>\
                        <th class="separator"></th>\
                        <th class="selection">{{selections.[2].outcome.name}}</th>\
                    </tr>\
                {{/if_eq}}\
                {{#if_eq selections.length 3}}\
                    <tr class="row">\
                        <td class="handicap">{{#if_eq market_type.is_handicap 1}}{{handicap}}{{/if_eq}}</td>\
                        <td class="separator"></td>\
                        <td class="selection {{parity @index}}">\
                            {{#with selections.[0]}}\
                                {{> selection fixture=../../fixture market=..}}\
                            {{/with}}\
                        </td>\
                        <td class="separator"></td>\
                        <td class="selection {{parity @index}}">\
                            {{#with selections.[1]}}\
                                {{> selection fixture=../../fixture market=..}}\
                            {{/with}}\
                        </td>\
                        <td class="separator"></td>\
                        <td class="selection {{parity @index}}">\
                            {{#with selections.[2]}}\
                                {{> selection fixture=../../fixture market=..}}\
                            {{/with}}\
                        </td>\
                    </tr>\
                {{/if_eq}}\
            {{/each}}\
        </table>\
    {{/if_in}}\
');

Handlebars.registerPartial('market_multiRow3ColsUnlabeled','\
    {{#if_in market_type_id "12,13,14,91,170,7809"}}\
        <div class="title">\
            {{markets.[0].market_type.name}}\
        </div>\
        <table class="multiRow3ColsUnlabeled">\
            {{#each markets.[0].selections}}\
                {{#if_eq (mod @index 3) 0}}\
                    <tr>\
                        <td class="selection">\
                            <table>\
                                <tr class="header">\
                                    <th class="selection">{{name}}</th>\
                                </tr>\
                                <tr class="row">\
                                    <td class="selection">\
                                        {{> selection fixture=../fixture market=../markets.[0]}}\
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
                                <th class="selection">{{name}}</th>\
                            </tr>\
                            <tr class="row">\
                                <td class="selection">\
                                    {{> selection fixture=../fixture market=../markets.[0]}}\
                                </td>\
                            </tr>\
                        </table>\
                    </td>\
                {{/if_eq}}\
                {{#if_eq (mod @index 3) 2}}\
                        <td class="separator"></td>\
                        <td class="selection">\
                            <table>\
                                <tr class="header">\
                                    <th class="selection">{{name}}</th>\
                                </tr>\
                                <tr class="row">\
                                    <td class="selection">\
                                        {{> selection fixture=../fixture market=../markets.[0]}}\
                                    </td>\
                                </tr>\
                            </table>\
                        </td>\
                    </tr>\
                {{/if_eq}}\
            {{/each}}\
            {{#if_eq (mod markets.[0].selections.length 3) 1}}\
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
            {{#if_eq (mod markets.[0].selections.length 3) 2}}\
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
    {{/if_in}}\
');

