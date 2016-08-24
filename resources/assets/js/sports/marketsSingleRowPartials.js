Handlebars.registerPartial('market_singleRow3Cols','\
    {{#if_in market_type_id "2,306,6832,7202,7591"}}\
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
    {{#if_in market_type_id "82,91"}}\
        {{#with markets.[0]}}\
            {{#if_eq selections.length 2}}\
                <div class="title">\
                    {{market_type.name}}\
                </div>\
                <table>\
                    <tr class="header">\
                        <th class="selection">{{selections.[0].outcome.name}}</th>\
                        <th class="separator"></th>\
                        <th class="selection"></th>\
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

