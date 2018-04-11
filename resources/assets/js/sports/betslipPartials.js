Handlebars.registerPartial('betslip_simple' , '\
    <div id="betslip-simpleBet-box-{{id}}" class="bets">\
        <div class="row">\
            <i id="betslip-simpleBet-button-removeBet-{{id}}" class="cp-cross remove"></i>\
            <span class="game">{{date}} - {{time}} | {{sport_name sportId}}<br>{{gameName}}</span>\
        </div>\
        <div class="row">\
            <span class="market">{{marketName}}</span>\
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
            <i id="betslip-multiBet-button-removeBet-{{id}}" class="cp-cross remove"></i>\
            <span class="game">{{date}} - {{time}} | {{sport_name sportId}} <br>{{gameName}}</span>\
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

Handlebars.registerPartial('betslip_open_bets' , `
    {{#each bets}}
        <div class="bets">
            <div class="row">
                <i class="cp-printer"></i>
            </div>
            {{#each events}}
                <div class="row">
                    <span class="game">{{date}} - {{time}}<br>{{game_name}}</span>
                    {{#if_eq ../type "simple"}}
                        <span class="amount">€ {{../amount}}</span>
                    {{/if_eq}}
                </div>
                <div class="row">
                    <span>{{market_name}}</span>
                </div>
                <div class="betslip-box row">
                    <span class="event">{{event_name}}</span>
                    <span class="odds">
                    {{#if_eq status "won"}}
                        <i class="cp-check-circle"></i> &nbsp; 
                    {{/if_eq}}
                    {{odd}}
                    </span>
                </div>
            {{/each}}
            {{#if_eq type "multi"}}
                <div class="row">
                    <span class="total">Total Apostas</span>
                    <span id="betslip-simpleProfit" class="total amount"> € {{amount}}</span>
                </div>
                <div class="row">
                    <span class="total">Total Odds</span>
                    <span id="betslip-multiOdds" class="odds">{{odd}}</span>
                </div>
            {{/if_eq}}
            <div class="row">
                <span class="profit">Possível retorno</span>
                <span class="profit amount">€ {{multiply amount odd}}</span>
            </div>
            <table class="receipt" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>&nbsp</td>
                </tr>          
                <tr>
                    <td>&nbsp</td>
                </tr>
                <tr>
                    <td>&nbsp</td>
                </tr>                    
                <tr>
                    <td class="bold">CASINO PORTUGAL, EMOÇÂO PELO JOGO</td>
                </tr>
                <tr>
                    <td>www.casinoportugal.pt</td>         
                </tr>
                <tr>
                    <td>100% português</td>         
                </tr>
                <tr>
                    <td>&nbsp</td>
                </tr>                          
                <tr>
                    <td class="align-left bold">{{#if_eq type "multi"}}Aposta Múltipla{{else}}Aposta Simples{{/if_eq}}</td>         
                </tr>
                <tr>
                    <td colspan="2" class="lineSplitter">___________________________________</td>         
                </tr>
                {{#each events}}
                <tr>
                    <td>&nbsp</td>
                </tr>          
                <tr>
                    <td>{{ receipt_line (concat (sport_name fixture.sport_id) ' | ' date ' - ' time) '' }}</td>
                </tr>
                <tr>
                    <td>{{ receipt_line game_name '' }}</td>
                </tr>
                <tr>
                    <td>{{ receipt_line (concat 'Resultado: ' event_name) odd }}</td>
                </tr>
                {{/each}}
                <tr>
                    <td class="lineSplitter">___________________________________</td>         
                </tr>
                <tr>
                    <td>&nbsp</td>
                </tr>                          
                <tr>
                    <td class="bold">{{ receipt_line 'Total Apostas:' (concat '€ ' amount) }}</td>
                </tr>
                <tr>
                    <td>{{ receipt_line 'Total Cotas:' odd }}</td>
                </tr>
                <tr>
                    <td>{{ receipt_line 'Possível Retorno:' (concat '€ ' (multiply amount odd)) }}</td>
                </tr>
                <tr>
                    <td>&nbsp</td>
                </tr>
                <tr>
                    <td class="bold">Data/Hora: {{ format_date created_at 'YYYY-MM-DD hh:mm:ss' }}</td>
                </tr>          
                <tr>
                    <td>&nbsp</td>
                </tr>          
                <tr>
                    <td class="align-center">Licenças de jogo nº 007 e nº 009</td>
                </tr>
                <tr>
                    <td class="align-center">emitidas pelo SRIJ.</td>
                </tr>
                <tr>
                    <td class="align-center">Apostas interditas a menores de 18.</td>
                </tr>
                <tr>
                    <td class="align-center">É da sua responsabilidade ler e</td>
                </tr>
                <tr>
                    <td class="align-center">compreender os nossos Termos e </td>
                </tr>                
                <tr>
                    <td class="align-center">Condições em www.casinoportugal.pt.</td>
                </tr>
                <tr>
                    <td>&nbsp</td>
                </tr>          
                <tr>
                    <td class="align-center">APOSTE NA DIVERSÂO</td>
                </tr>                              
                <tr>
                    <td class="align-center">JOGUE COM MODERAÇÂO</td>
                </tr>                              
            </table>
        </div>
    {{/each}}\
`);