/**
 * Created by miguel on 04/02/2016.
 */
$(function() {
    $('#history-filters-container').slimScroll({
        height: '320px',
        width: '194px'
    });
    var divOps = $("#operations-history-container");
    divOps.slimScroll({
        //width: '600px',
        height: '430px'
    });
    var tBodyOps = divOps.find('tbody');

    populateOperationsTable();
    function getToolTip(tip){
        if (tip == '0.00') return '';
        return ' <i class="tip fa fa-question-circle" onmouseover="vanillaTip.over(this);"  onmouseout="vanillaTip.out(this);" /><div class="popover top">' +
            '<div class="arrow"></div>' +
            '<div class="popover-content">' +
            tip +
            '</div>' +
            '</div>';
    }

    Handlebars.registerPartial('history_bet_details' , '\
        {{#with bet}}\
            <tr >\
                        {{#each events}}\
                                <td >{{date}} - {{time}} - {{game_name}}</td>\
                                {{#if_eq ../type "simple"}}\
                                    <td>€ {{../amount}}</td>\
                                {{/if_eq}}\
                                <td >{{market_name}}</td>\
                                <td>{{event_name}}</td>\
                                {{#if_eq status "won"}}\
                                    <td><i class="fa fa-check-circle" aria-hidden="true"></i> &nbsp;</td>\
                                {{/if_eq}}\
                                {{#if_eq status "pushed"}}\
                                    <td ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> &nbsp;</td>\
                                {{/if_eq}}\
                                {{#if_eq status "lost"}}\
                                    <td><i class="fa fa-times-circle betslip-icon-loss" aria-hidden="true"></i> &nbsp;</td>\
                                {{/if_eq}}\
                                {{odd}}\
                        {{/each}}\
                        {{#if_eq type "multi"}}\
                                <td >Total Apostas</td>\
                                <td id="betslip-simpleProfit" > € {{amount}}</td>\
                                <td >Total Odds</td>\
                                <td id="betslip-multiOdds" >{{odd}}</td>\
                        {{/if_eq}}\
                            <td>Possível retorno</td>\
                            <td >€ {{multiply amount odd}}</td>\
            </tr>\
        {{/with}}\
    ');

    function detailsClick()
    {
        var details = $("tr[data-type=detail]");

        if (details.length)
            details.remove();

        var self = $(this);

        $.get('/historico/details/' + $(this).data('id'))
            .done(function (data) {
                betData(data);

                var html = Template.apply('history_bet_details', data);
                self.after(html);
                $("tr[data-type=detail]").click(closeDetails);
            });
    }

    function closeDetails()
    {
        $(this).remove();
    }

    function betData(data)
    {
        var events = data.bet.events || [];

        for (var i in events)
            dateAndTime(events[i], 'game_date');
    }

    function dateAndTime(event, fieldName)
    {
        event.date = moment.utc(event[fieldName]).local().format("DD MMM");
        event.time = moment.utc(event[fieldName]).local().format("HH:mm");
    }

    function populateOperationsTable() {
        $.post("/historico/operacoes", $("#operations-filter-form").serialize())
            .error(function (err){
                var html = '<tr>' +
                '<td class="col-12" colspan="4">Erro ao Obter dados</td>' +
                '</tr>';
                tBodyOps.html(html);
            })
            .done(function(operations_history) {
                var operations = JSON.parse(operations_history);
                var html = "";
                for (var i=0; i<operations.length; i++) {
                    html += '<tr data-id="' + operations[i].id + '" data-type="' + operations[i].type + '" style="cursor: pointer">' +
                        '<td class="col-2">'+moment(operations[i].date).format('DD/MM/YY HH:mm')+'</td>' +
                        '<td class="col-3 cap settings-text-darker">'+operations[i].type+'</td>' +
                        '<td class="col-5 settings-text-darker">'+operations[i].description+'</td>' +
                        '<td class="col-2">' + operations[i].value + ' €' + getToolTip(operations[i].tax) + '</td>' +
                        '</tr>';
                }
                if (operations.length == 0)
                    html = '<tr>' +
                        '<td>Sem dados</td>' +
                        '</tr>';
                tBodyOps.html(html);

                tBodyOps.find("tr[data-type=sportsbook]").click(detailsClick);
            });
    }

    $(".settings-switch").on('click', function() {
        populateOperationsTable();
    });

    var cur = -1, prv = -1;
    prv = $.datepicker.parseDate( 'dd/mm/y', $('#date-begin-text').val() ).getTime();
    cur = $.datepicker.parseDate( 'dd/mm/y', $('#date-end-text').val() ).getTime();
    $('#date-picker').datepicker({
        defaultDate: $("#date-end-text").val(),

        beforeShowDay: function ( date ) {
            return [true, ( (date.getTime() >= Math.min(prv, cur) && date.getTime() <= Math.max(prv, cur)) ? 'date-range-selected' : '')];
        },
        onSelect: function ( dateText, inst ) {
            var d1, d2;
            prv = cur;
            cur = (new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay)).getTime();
            if ( prv == -1 || prv == cur ) {
                prv = cur;
                $('#date-begin-text').val( dateText );
                $('#date-end-text').val( dateText );
            } else {
                d1 = $.datepicker.formatDate( 'dd/mm/y', new Date(Math.min(prv,cur)), {} );
                d2 = $.datepicker.formatDate( 'dd/mm/y', new Date(Math.max(prv,cur)), {} );
                $('#date-begin-text').val( d1 );
                $('#date-end-text').val( d2 );
            }
            populateOperationsTable();
        },
        onChangeMonthYear: function ( year, month, inst ) {
            //prv = cur = -1;
        },
        onAfterUpdate: function ( inst ) {

        }
    });
});