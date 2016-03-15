/**
 * Created by miguel on 04/02/2016.
 */
$(function() {
    $('#history-filters-container').slimScroll({
        height: '320px',
        width: '188px'
    });
    $('#operations-history-container').parent().slimScroll({
        height: '410px'
    });

    populateOperationsTable();
    function populateOperationsTable() {
        $.post("/historico/operacoes", $("#operations-filter-form").serialize())
            .error(function (err){
                var html = "<tr>" +
                    "<td></td>" +
                    "<td>Erro ao Obter dados</td>" +
                    "<td></td><td></td><td></td>" +
                    "</tr>";
                $("#operations-history-container").html(html);
            })
            .done(function(operations_history) {
                var operations = JSON.parse(operations_history);
                var html = "";
                for (var i=0; i<operations.length; i++)
                    html += "<tr>" +
                        "<td>"+moment(operations[i].date).format("DD/MM/YY HH:mm")+"</td>"+
                        "<td class='settings-text-darker' title='"+operations[i].description+"'>"+operations[i].description+"</td>"+
                        "<td>"+(operations[i].credit*1?operations[i].credit+" €":"")+"</td>"+
                        "<td>"+(operations[i].debit*1?operations[i].debit+" €":"")+"</td>"+
                        "<td>0 €</td>"+
                        "<tr>";
                $("#operations-history-container").html(html);
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