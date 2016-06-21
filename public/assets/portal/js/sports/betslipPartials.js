Handlebars.registerPartial('betslip_simple' , '\
    <div id="betslip-simpleBet-box-{{id}}" class="betslip-box bet">\
        <div class="betslip-box row">\
            <span class="betslip-text gameName">{{gameName}}</span>\
            <button id="betslip-simpleBet-button-removeBet-{{id}}" class="betslip-button remove">X</button>\
        </div>\
        <div class="betslip-box row">\
            <span class="betslip-text marketName">{{marketName}}</span>\
            <div class="pull-right">€ <input id="betslip-field-amount-{{id}}" type="text" class="betslip-field amount" value="{{amount}}" data-id="{{id}}"></div>\
        </div>\
        <div class="acenter">\
            <span id="simpleBet-text-error-{{id}}" class="betslip-text error"></span>\
        </div class="betslip-box row">\
        <div class="betslip-box row">\
            <span class="betslip-text eventName">{{name}}</span>\
            <span class="betslip-text odds">{{odds}}</span>\
        </div>\
        <div  class="betslip-box row">\
            <span class="betslip-text profitLabel">Possível Retorno</span>\
            <span id="betslip-text-profit-{{id}}" class="betslip-text profit">€ {{multiply amount odds}}</span>\
        </div>\
    </div>\
');

Handlebars.registerPartial('betslip_multi' , '\
    <div id="betslip-multiBet-box-{{id}}" class="betslip-box event">\
        <div class="betslip-box row">\
            <span class="betslip-text gameName">{{gameName}}</span>\
            <button id="betslip-multiBet-button-removeBet-{{id}}" class="betslip-button remove">X</button>\
        </div>\
        <div class="betslip-box row">\
            <span class="betslip-text marketName">{{marketName}}</span>\
        </div>\
        <div class="acenter">\
            <span id="multiBet-text-error-{{id}}" class="betslip-text error"></span>\
        </div>\
        <div class="betslip-box row">\
            <span class="betslip-text eventName">{{name}}</span>\
            <span class="betslip-text odds">{{odds}}</span>\
        </div>\
    </div>\
');

Handlebars.registerPartial('betslip_open_bets' , '\
    {{#each bets}}\
        <div class="bets-box vmargin-small">\
            {{#if_eq type 2}}\
                {{#each events}}\
                    <div class="hBox">\
                        <span class="bets-text-gameName">{{game_name}}</span>\
                    </div>\
                    <div class="hBox">\
                        <span class="bets-text-marketName">{{market_name}}</span>\
                    </div>\
                    <div class="hBox">\
                        <span class="bets-text-eventName {{#if_eq outcome 3}}bets-text-win{{/if_eq}}{{#if_eq outcome 1}}bets-text-loss{{/if_eq}}">{{event_name}}\
                            {{#if_eq outcome 3}}\
                                &nbsp; <i class="fa fa-check bets-text-win">Ganha</i>\
                            {{/if_eq}}\
                            {{#if_eq outcome 1}}\
                                &nbsp; <i class="fa fa-times bets-text-loss"> Perdida</i>\
                            {{/if_eq}}\
                        </span>\
                        <span class="bets-text-odds">{{coeficient}}</span>\
                    </div>\
                {{/each}}\
            <div class="hBox vmargin-normal">\
                <span class="bets-text-label-small">Montante Apostado</span>\
                <span class="bets-text-amount">{{amount}}€</span>\
            </div>\
            <div class="hBox">\
                <span class="bets-text-label">Possível Retorno</span>\
                <span class="bets-text-profit">{{possible_win}}€</span>\
            </div>\
            {{#if (multiply cash_out 1)}}\
                <div class="bets-box-cashOut vmargin-small">\
                    <span>CASH OUT</span>\
                    <span class="fright">{{cash_out}}€</span>\
                </div>\
            {{/if}}\
            {{else}}\
                <div class="hBox">\
                    <span class="bets-text-gameName">{{events.0.competition_name}}</span>\
                    <span class="bets-text-amount">{{amount}}€</span>\
                </div>\
                <div class="hBox">\
                    <span class="bets-text-marketName">{{events.0.market_name}}</span>\
                </div>\
                <div class="hBox">\
                    <span class="bets-text-eventName">{{user_bet}}</span>\
                    <span class="bets-text-odds">{{k}}</span>\
                </div>\
                <div class="hBox">\
                    <span class="bets-text-profitLabel">Possível Retorno</span>\
                    <span class="bets-text-profit">{{possible_win}}€</span>\
                </div>\
                {{#if (multiply cash_out 1)}}\
                    <div class="bets-box-cashOut vmargin-small">\
                        <span>CASH OUT</span>\
                            <span class="fright">-> {{cash_out}}€</span>\
                    </div>\
                {{/if}}\
            {{/if_eq}}\
        </div>\
    {{/each}}\
');