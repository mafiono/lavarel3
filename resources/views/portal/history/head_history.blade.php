<div class="settings-col-20">
    <h2 class="settings-title">
        Histórico
    </h2>
    <div id="menu-container">
        <a class="settings-button settings-menu-margin  {{($active == 'APOSTAS RECENTES')?"settings-button-selected":"" }}" href="/historico/recente">
            APOSTAS RECENTES
        </a>
        <!--
        <a class="settings-button {{($active == 'DEPOSITOS')?"settings-button-selected":"" }}" href="/historico/depositos">
            DEPÓSITOS
        </a>
        <a class="settings-button {{($active == 'LEVANTAMENTOS')?"settings-button-selected":"" }}" href="/historico/levantamentos">
            LEVANTAMENTOS
        </a>
        -->

        <a class="settings-button settings-menu-margin {{($active == 'HISTORICO OPERACOES')?"settings-button-selected":"" }}" href="/historico/operacoes">
            HISTÓRICO OPERAÇÕES
        </a>
    </div>
    @if ($active == 'HISTORICO OPERACOES')
    <div id="history-filters-container" class="{{($active == 'HISTORICO OPERACOES')?"":"hidden"}}" style="overflow: auto">
        {!! Form::open(['url' => '/historico/operacoes', 'id' => 'operations-filter-form']) !!}
        <div class="settings-date-interval settings-menu-margin">
            <input type="text" id="date-begin-text" name="date_begin" class="fleft" value="{{$input['date_begin'] or \Carbon\Carbon::now()->subMonth(1)->format('d/m/y')}}" readonly>
                -
            <input type="text" id="date-end-text" name="date_end" class="fright" value="{{$input['date_end'] or \Carbon\Carbon::now()->format('d/m/y')}}" readonly>
        </div>
        <div id="date-picker"></div>
        <div class="settings-row">
            <div class="settings-row-label">Apostas</div>
            <div class="fright">
                <input id="bets-filter" name="operations_filter" type="checkbox" class="settings-switch" value="bets" checked>
                <label for="bets-filter"></label>
            </div>
        </div>
        <div class="settings-row">
            <div class="settings-row-label">Movimentos</div>
            <div class="fright">
                <input id="transactions-filter" name="operations_filter" type="checkbox" class="settings-switch" value="transactions">
                <label for="transactions-filter"></label>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    @endif
</div>

<link rel="stylesheet" type="text/css" href="/assets/portal/css/jquery-ui.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script src="/assets/portal/js/jquery-ui.js"></script>
<script src="/assets/portal/js/datepicker-pt.js"></script>

<style>

#history-filters-container {
    overflow:scroll;
    max-height: 340px;
}

#history-filters-container .settings-row-label {
    display: inline-block;
    line-height: 28px;
    color: #444;
    font-size: 120%;
}

#history-filters-container .settings-row {
    border-bottom: 1px solid #ccc;
}

/* date picker */
.ui-datepicker {
    font-size: 81%;
    border-collapse: collapse;
    margin: 15px auto 0;
    max-width: 178px;
}
.ui-datepicker td {
    padding: 0;
}
.ui-datepicker .ui-state-default {
    border: 0;
    text-align: center;
}
.ui-datepicker .ui-state-highlight {
    background: #f6f6f6;
    color: #000;
    font-weight: bold;
}
.ui-datepicker .ui-state-active {
    background: #019fe9;
    color: #FFF;
    border-radius: 2px;
}

.ui-datepicker-title > span {
    display: inline !important;
}
</style>

<script>
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
                                        "<td class='settings-text-darker'>"+operations[i].transaction.name+"</td>"+
                                        "<td>"+(operations[i].credit*1?operations[i].credit+" €":"")+"</td>"+
                                        "<td>"+(operations[i].charge*1?operations[i].charge+" €":"")+"</td>"+
                                        "<td>0 €</td>"+
                                    "<tr>";
                    else
                        for (var i=0; i<operations.length; i++)
                            html += "<tr>" +
                                        "<td>"+moment(operations[i].created_at).format("DD/MM/YY HH:mm")+"</td>"+
                                        "<td class='settings-text-darker'> Aposta nº"+operations[i].api_bet_id+"</td>"+
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

    $('#date-picker').datepicker({
        inline: true,
        defaultDate: $("#date-end-text").val()
    });

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
});
</script>
