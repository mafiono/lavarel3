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
                {{/if_eq}}\
            {{/each}}\
        </table>\
    {{/if_in}}\
');

Handlebars.registerPartial('market_multiRow3ColsUnlabeled','\
    {{#if_in market_type_id "91"}}\
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
                                    <th class="selection">{{round range.high}} - {{round range.low}}</th>\
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
                                <th class="selection">{{round range.high}} - {{round range.low}}</th>\
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
                                    <th class="selection">{{round range.high}} - {{round range.low}}</th>\
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
