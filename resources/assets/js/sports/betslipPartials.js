Handlebars.registerPartial('betslip_simple' , '\
    <div id="betslip-simpleBet-box-{{id}}" class="bets">\
        <div class="row">\
            <i id="betslip-simpleBet-button-removeBet-{{id}}" class="fa fa-times remove" aria-hidden="true"></i>\
            <span class="game">{{date}} - {{time}}<br>{{gameName}}</span>\
        </div>\
        <div class="row">\
            <span>{{marketName}}</span>\
            <div class="amount"><span>€</span> <input id="betslip-field-amount-{{id}}" type="text" value="{{amount}}" data-id="{{id}}"></div>\
        </div>\
        <div class="error">\
            <span id="simpleBet-text-error-{{id}}"></span>\
        </div>\
        <div class="row">\
            <span class="event">{{name}}</span>\
            <span class="odds">{{odds}}</span>\
            <span class="odds old">{{oldOdds}}</span>\
        </div>\
        <div  class="row">\
            <span class="profit">Possível Retorno</span>\
            <span id="betslip-text-profit-{{id}}" class="profit amount">€ {{multiply amount odds}}</span>\
        </div>\
    </div>\
');

Handlebars.registerPartial('betslip_multi' , '\
    <div id="betslip-multiBet-box-{{id}}" class="bets">\
        <div class="row">\
            <i id="betslip-multiBet-button-removeBet-{{id}}" class="fa fa-times remove" aria-hidden="true"></i>\
            <span class="game">{{date}} - {{time}}<br>{{gameName}}</span>\
        </div>\
        <div class="row">\
            <span>{{marketName}}</span>\
        </div>\
        <div class="error">\
            <span id="multiBet-text-error-{{id}}"></span>\
        </div>\
        <div class="row">\
            <span class="event">{{name}}</span>\
            <span class="odds">{{odds}}</span>\
            <span class="odds old">{{oldOdds}}</span>\
        </div>\
    </div>\
');

Handlebars.registerPartial('betslip_open_bets' , '\
    {{#each bets}}\
        <div class="bets">\
            {{#each events}}\
                <div class="row">\
                    <span class="game">{{date}} - {{time}}<br>{{game_name}}</span>\
                    {{#if_eq ../type "simple"}}\
                        <span class="amount">€ {{../amount}}</span>\
                    {{/if_eq}}\
                </div>\
                <div class="row">\
                    <span>{{market_name}}</span>\
                </div>\
                <div class="betslip-box row">\
                    <span class="event">{{event_name}}</span>\
                    <span class="odds">\
                    {{#if_eq status "won"}}\
                        <i class="fa fa-check-circle" aria-hidden="true"></i> &nbsp; \
                    {{/if_eq}}\
                    {{odd}}\
                    </span>\
                </div>\
            {{/each}}\
            {{#if_eq type "multi"}}\
                <div class="row">\
                    <span class="total">Total Apostas</span>\
                    <span id="betslip-simpleProfit" class="total amount"> € {{amount}}</span>\
                </div>\
                <div class="row">\
                    <span class="total">Total Odds</span>\
                    <span id="betslip-multiOdds" class="odds">{{odd}}</span>\
                </div>\
            {{/if_eq}}\
            <div class="row">\
                <span class="profit">Possível retorno</span>\
                <span class="profit amount">€ {{multiply amount odd}}</span>\
            </div>\
        </div>\
    {{/each}}\
');