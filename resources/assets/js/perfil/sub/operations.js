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
                var operations = JSON.parse(operations_history);
                var html = "";
                for (var i=0; i<operations.length; i++) {
                    html += '<div class="row" data-id="' + operations[i].id + '" data-type="' + operations[i].type + '">' +
                        '<div class="col-xs-3">'+moment(operations[i].date).format('DD/MM/YY HH:mm')+'</div>' +
                        '<div class="col-xs-5 text-center ellipsis">'+operations[i].description+'</div>' +
                        '<div class="col-xs-2 text-right">' + operations[i].value + ' €</div>' +
                        '<div class="col-xs-2 text-right">' + operations[i].final_balance + ' €</div>' +
                    '</div>';
                }
                if (operations.length == 0)
                    var html = '<div class="row">' +
                        '<div class="col-xs-12">Sem dados</div>' +
                        '</div>';
                container.html(html);

                container.find("div[data-type=sportsbook] > div:not(.bag)").click(detailsClick);
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