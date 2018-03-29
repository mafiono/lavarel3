/**
 * Created by miguel on 04/02/2016.
 */
module.exports.load = function(){
    $(".history.table-like .place").slimScroll({
        //width: '600px',
        height: '730px',
        allowPageScroll: false
    });
    var container = $('.profile-content .history .place');

    var eventsClicks = {};
    var populate = null;

    function detailsClick(event)
    {
        event.preventDefault();
        event.stopPropagation();

        var self = $(this).parent();
        var id = self.data('id');

        var details = self.find("div.details");

        if (details.length) {
            details.parents('.bag').remove();
        } else if (eventsClicks[id]) {
            eventsClicks[id].abort();
            details.parents('.bag').remove();
            delete eventsClicks[id];
        } else {
            eventsClicks[id] = $.get('/ajax-perfil/historico/details/' + id)
                .done(function (data) {
                    delete eventsClicks[id];
                    betData(data);

                    var html = Template.apply('history_bet_details', data);
                    self.append($('<div class="bag">').html(html));
                });
        }
    }

    function sessionDetailsClick()
    {
        var self = $(this).parent();

        var details = self.find('.bag');

        if (details.length) {
            details.remove();

            return;
        }

        $.get('/ajax-perfil/historico/session-details/' + self.data('id'))
            .done(function (html) {
                self.append($('<div class="bag">').html(html));
            });
    }

    function transactionsDetailsClick()
    {
        var self = $(this).parent();

        var details = self.find('.bag');

        if (details.length) {
            details.remove();

            return;
        }
        let data = self.data('obj');

        let html = $(Template.apply('history_transaction_details', data));
        // html.find('button').click(function () {
        //     console.log('Click Done...');
        //     $.fn.popup({
        //         title: 'Deseja cancelar o Pedido de Levantamento?',
        //         text: data.description,
        //         type: "error",
        //         showCancelButton: true,
        //         showConfirmButton: true,
        //         confirmButtonText: 'Confirmar',
        //     });
        // });
        self.append($('<div class="bag">').append(html));
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
        if (populate) populate.abort();
        populate = $.post("/historico/operacoes", $("#operations-filter-form").serialize())
            .error(function (err){
                var html = '<div class="row">' +
                    '<div class="col-xs-12">Erro ao Obter dados</div>' +
                '</div>';
                container.html(html);
            })
            .done(function(operations_history) {
                var operations = operations_history;

                var html = $('<div>');
                for (var i=0; i<operations.length; i++) {
                    let reserved = '', classReserved = '';
                    if (operations[i].reserved !== "0.00") {
                        reserved = '<br>' + number_format(operations[i].reserved, 2, '.', ' ') + '€';
                        classReserved = 'single-line';
                    }
                    let div = $(`<div class="row" data-id="${operations[i].id}" data-type="${operations[i].type}">
                        <div class="col-xs-2 hist-value single-line">${moment(operations[i].date).format('DD/MM/YY HH:mm')}</div>
                        <div class="col-xs-5 hist-value text-center ellipsis">${operations[i].description}</div>
                        <div class="col-xs-2 hist-value text-right ${classReserved}">${number_format(operations[i].value, 2, '.', ' ')}€${reserved}</div>
                        <div class="col-xs-3 hist-value text-right">${number_format(operations[i].final_balance, 2, '.', ' ')}€</div>
                    </div>`);
                    switch (operations[i].type) {
                        case 'sportsbook': div.find(".hist-value").click(detailsClick); break;
                        case 'casino_session': div.find(".hist-value").click(sessionDetailsClick); break;
                        case 'transaction':
                            div.data('obj', operations[i]);
                            div.find(".hist-value").click(transactionsDetailsClick); break;
                    }
                    html.append(div);
                }
                if (operations.length === 0)
                    html.append($('<div class="row">' +
                        '<div class="col-xs-12">Sem dados</div>' +
                        '</div>'));
                container.empty().append(html.children());

                // container.find("div[data-type=sportsbook] > div:not(.bag)").click(detailsClick);

                // container.find("div[data-type=casino_session]").click(sessionDetailsClick);

                // container.find("div[data-type=transaction]").click(transactionsDetailsClick);
            });
    }

    $(".settings-switch").on('click', function() {
        populateOperationsTable();
    });
    $('#date_begin, #date_end').datepicker({
        onSelect: function ( dateText, inst ) {
            populateOperationsTable();
        }
    });
    // render the table with defaults
    populateOperationsTable();
};
module.exports.unload = function () {
};