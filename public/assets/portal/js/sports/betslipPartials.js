Handlebars.registerPartial('betslip_simple' , '\
    <div id="betslip-simpleBet-box-{{id}}" class="betslip-box bet">\
        <div class="betslip-box row">\
            <span class="betslip-text gameName">{{gameName}}</span>\
            <button id="betslip-simpleBet-button-removeBet-{{id}}" class="betslip-button remove"><i class="fa fa-times" aria-hidden="true"></i></button>\
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
            <span class="betslip-text profit">Possível Retorno</span>\
            <span id="betslip-text-profit-{{id}}" class="betslip-text profit amount">€ {{multiply amount odds}}</span>\
        </div>\
    </div>\
');

Handlebars.registerPartial('betslip_multi' , '\
    <div id="betslip-multiBet-box-{{id}}" class="betslip-box event">\
        <div class="betslip-box row">\
            <span class="betslip-text gameName">{{gameName}}</span>\
            <button id="betslip-multiBet-button-removeBet-{{id}}" class="betslip-button remove"><i class="fa fa-times" aria-hidden="true"></i></button>\
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
        <div class="betslip-box bet">\
            {{#each events}}\
                <div class="betslip-box row">\
                    <span class="betslip-text gameName">{{date}} - {{time}}<br>{{game_name}}</span>\
                    <span class="betslip-text amount">€ {{../amount}}</span>\
                </div>\
                <div class="betslip-box row">\
                    <span class="betslip-text marketName">{{market_name}}</span>\
                </div>\
                <div class="betslip-box row">\
                    <span class="betslip-text gameName">{{event_name}}</span>\
                    <span class="betslip-text odds">\
                    {{#if_eq status "won"}}\
                        <span class="betslip-text win"><i class="fa fa-check-circle" aria-hidden="true"></i> &nbsp;</span>\
                    {{/if_eq}}\
                    {{odd}}\
                    </span>\
                </div>\
            {{/each}}\
            <div class="betslip-box row">\
                <span class="betslip-text profit white">Possível retorno</span>\
                <span class="betslip-text profit amount white">€ {{multiply amount odd}}</span>\
            </div>\
        </div>\
    {{/each}}\
');