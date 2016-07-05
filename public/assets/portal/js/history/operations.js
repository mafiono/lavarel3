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

    var statuses = {
        'waiting_result': 'esperando',
        'won': 'ganhou',
        'lost': 'perdeu',
        'returned': 'devolvido'
    };

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

    function detailsClick()
    {
        $("tr[data-type=detail]").remove();

        var self = $(this);

        $.get('/historico/details/' + $(this).data('id'))
            .done(function (data) {
                var html = "";

                for(var i in data.events) {
                    html += '<tr class="cursor: pointer;" data-type="detail">' +
                        '<td>'+moment(data.events[i].game_date).format('DD/MM/YY HH:mm')+'</td>' +
                        '<td style="color: #666;">'+data.events[i].game_name +'</td>' +
                        '<td  colspan="2" style="color: #666;">'+
                            data.events[i].market_name + ' &nbsp; ' +
                            data.events[i].event_name + ' &nbsp; ' +
                            statuses[data.events[i].status] + ' &nbsp; ' +
                            '( cota: ' + data.events[i].odd +
                        ' )</td>' +
                    '</tr>';
                }

                self.after(html);
                self.next().click(closeDetails);
            });
    }

    function closeDetails() {

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
                        '<td class="col-12" colspan="4">Sem dados</td>' +
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