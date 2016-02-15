/**
 * Created by miguel on 04/02/2016.
 */
$(function() {
    populateOperationsTable();
    function populateOperationsTable() {
        $.post("/historico/operacoes", $("#operations-filter-form").serialize())
            .done(function(operations_history) {
                var operations = JSON.parse(operations_history);
                var html = "";
                if ($("#transactions-filter").is(":checked"))
                    for (var i=0; i<operations.length; i++)
                        html += "<tr>" +
                            "<td>"+moment(operations[i].date).format("DD/MM/YY HH:mm")+"</td>"+
                            "<td class='settings-text-darker' title='"+operations[i].description+"'>"+operations[i].description+"</td>"+
                            "<td>"+(operations[i].credit*1?operations[i].credit+" €":"")+"</td>"+
                            "<td>"+(operations[i].charge*1?operations[i].charge+" €":"")+"</td>"+
                            "<td>0 €</td>"+
                            "<tr>";
                else
                    for (var i=0; i<operations.length; i++)
                        html += "<tr>" +
                            "<td>"+moment(operations[i].created_at).format("DD/MM/YY HH:mm")+"</td>"+
                            "<td class='settings-text-darker' title='Aposta nº"+operations[i].api_bet_id+"'>Aposta nº"+operations[i].api_bet_id+"</td>"+
                            "<td>"+(operations[i].result_amount*1?operations[i].result_amount/100+" €":"")+"</td>"+
                            "<td>"+(operations[i].amount*1?operations[i].amount+" €":"")+"</td>"+
                            "<td>0 €</td>"+
                            "<tr>";
                $("#operations-history-container").html(html);
            });
    }

    $("#bets-filter").on('click', function() {
        $("#transactions-filter").prop("checked", !$("#bets-filter").is(":checked"));
        populateOperationsTable();
    });

    $("#transactions-filter").on('click', function() {
        $("#bets-filter").prop("checked", !$("#transactions-filter").is(":checked"));
        populateOperationsTable();
    });

    var cur = -1, prv = -1;
    prv = $.datepicker.parseDate( 'dd/mm/y', $('#date-begin-text').val() ).getTime();
    cur = $.datepicker.parseDate( 'dd/mm/y', $('#date-end-text').val() ).getTime();
    $('#date-picker').datepicker({
        inline: true,
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
        },
        onChangeMonthYear: function ( year, month, inst ) {
            //prv = cur = -1;
        },
        onAfterUpdate: function ( inst ) {

        }
    });
/*
    $("#date-begin-text").on('click', function() {
        $('#date-picker').datepicker("setDate", $(this).val());
        $('#date-picker').datepicker("option", "minDate", null);
        $('#date-picker').datepicker("option", "maxDate", $("#date-end-text").val());
        $('#date-picker').datepicker("option", "onSelect", function(date) {
            $("#date-begin-text").val(date);
            populateOperationsTable();
        });
    });

    $("#date-end-text").on('click', function() {
        $('#date-picker').datepicker("setDate", $(this).val());
        $('#date-picker').datepicker("option", "minDate", $("#date-begin-text").val());
        $('#date-picker').datepicker("option", "maxDate", moment().format("DD/MM/YY"));
        $('#date-picker').datepicker("option", "onSelect", function(date) {
            $("#date-end-text").val(date);
            populateOperationsTable();
        });
    });

    $("#date-end-text").click();
    */
});