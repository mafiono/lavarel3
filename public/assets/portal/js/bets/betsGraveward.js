var socket,
    leftBar,
    preMatch,
    live,
    middle,
    level3,
    sid,
    marketSelect,
    middleMarket,
    eventBet,
    simpleBets,
    multipleBets,
    defaultBet,
    removeUserBet,
    totalBets,
    gameValue,
    clearAllBets,
    submitBet,
    betType = 0,
    userTotalBets = 0,
    userError = false,
    userBetCount = 1,
    //switchEventType = 0,
    pendentes,
    pendentesId,
    boletim,
    boletimId,
    apostas,
    rightBar,
    leftSpinner,
    leftSpinnerItem,
    middleSpinner,
    middleSpinnerItem,
    rightSpinner,
    rightSpinnerItem;
function connect(){
    try{
        //var host = "ws://swarm-partner.betconstruct.com";
        var host = "ws://swarm-partner.betconstruct.com";
        socket = new WebSocket(host);
        socket.onopen = function(){
            //console.log('<p class="event">Socket Status: '+socket.readyState+' (open)');
            requestSession();
        }
        socket.onclose = function(){
            //console.log('<p class="event">Socket Status: '+socket.readyState+' (Closed)');
        }
    } catch(exception){
        console.log('<p>Error'+exception);
    }
}
function send(params)
{
    params = JSON.stringify(params);
    try{
        socket.send(params);
        socket.onmessage = function(msg){
            msg = $.parseJSON(msg.data);
            if (msg.rid == 'request_session') {
                handleRequestSessionResponse(msg);
            }else if (msg.rid == 'restore_login') {
                handleRestoreLoginResponse(msg);
            }else if (msg.rid == 'get_balance') {
                handleGetBalanceResponse(msg);
            }else if (msg.rid == 'get') {
                handleGetResponse(msg);
            }else if (msg.rid == 'get_middle') {
                handleGetMiddleResponse(msg);
            }else if (msg.rid == 'bet_history') {
                handleBetHistoryResponse(msg);
            }else {
                // handleNewBetResponse(msg);
                BetValidator.validate(msg);
            }
        }
    } catch(exception){
        console.log('<p class="warning"> Error:' + exception);
    }
}
function handleRequestSessionResponse(msg)
{
    if (msg.code == 0) {
        sid = msg.data.sid;
        console.log('Session ID: '+sid);
        if (phpAuthUser != null) {
            loginUser();
        }else{
            //switchMode(0);
            rightBar.show();
        }
        switchMode(0);
    }
}
function handleRestoreLoginResponse(msg)
{
    if (msg.code == 0) {
        console.log('Player logged in.');
        setInterval(getBalance, 30000);
        getUserBets();
        //switchMode(0);
        //rightBar.show();
    }
}
function handleGetBalanceResponse(msg)
{
    if (msg.code == 0) {
        //do nothing
    }else{
        window.location.href = '/logout';
    }
}

//TODO: change function name
function handleGetResponse(msg)
{
    if (msg.code == 0) {
        populateSportsMenu(msg.data.data);
    }
}
function handleGetMiddleResponse(msg)
{
    if (msg.code == 0) {
        // Miguel Teixeira hook
        // TODO: clean this mess
        var middleTemplateScript = $("#middle-template-script").html();
        var middleTemplate = Handlebars.compile(middleTemplateScript);
        // TODO: use Modernizer or any other alternative (underscore).
        var sport = msg["data"]["data"]["sport"];
        var sportKey = Object.keys(sport)[0];
        var region = sport[sportKey].region;
        var regionKey = Object.keys(region)[0];
        var competition = region[regionKey].competition;
        var competitionKey = Object.keys(competition)[0];
        var game = competition[competitionKey].game;
        var gameKey = Object.keys(game)[0];
        var market = game[gameKey].market;
        var marketsForSelect = [];
        var games = [];
        var markets = [];
        for (var marketKey in market)
            if (market[marketKey]["id"]) {
                marketsForSelect.push({
                    "id": market[marketKey]["id"],
                    "name": market[marketKey]["name"]
                });
                var event = market[marketKey].event;
                for (var eventKey in event)
                    event[eventKey]["selectedClass"] = (betExists(eventKey*1)?"tabela-bet-active":"");
            }
        for (var gameKey in game) {
            game[gameKey]["date"] =
            games.push(game[gameKey]);
        }
        for (var marketKey in market) {markets.push(market[marketKey]);}
        middleSpinnerItem.stop();
        middle.html(middleTemplate({
            "sportName" : sport[sportKey]["alias"],
            "regionName" : region[regionKey]["name"],
            "competitionName" : competition[competitionKey]["name"].substr(0,40),
            "competition" : competition,
            "games" : games,
            "markets" : markets,
            "marketsForSelect" : marketsForSelect
        }));
        enableMiddle();
        //console.log(msg.data.data.sport["844"].region["65571"].competition["427833469"].game["1657021181"].market);
        //console.log(JSON.stringify(msg.data.data.sport["844"].region["65571"].competition["427833469"].game["1657021181"].market).length);
        //$.ajax({
        //    type: "POST",
        //    url: '/bets/load/middle',
        //    success:function(response){
        //        console.log("r: "+response);
        //        if (response.status == 'success'){
        //            middleSpinnerItem.stop();
        //            middle.html(response.html);
        //            enableMiddle();
        //        }else{
        //
        //        }
        //    }
        //});
    }
}
function handleNewBetResponse(msg)
{
    if (msg.rid == "get_multi_bets") {
        handleMultipleBetsResponse(msg);
        return;
    }
    if (msg.code == 0 && msg.data.result == "OK") {
        newBetSuccess(msg);
    }else {
        newBetError(msg);
    }
    userTotalBets--;
    if (userTotalBets <= 0) {
        if (!userError) {
            setTimeout(function() {
                //onClearAllBets();
                submitBet.text('EFECTUAR APOSTA');
                submitBet.on('click', onSubmitBet);
            }, 3000);
        }else{
            submitBet.off().on('click', onSubmitBet);
            submitBet.text('EFECTUAR APOSTA');
        }
        userTotalBets = 0;
    }
}
function handleBetHistoryResponse(msg)
{
    //rightSpinner.remove();
    if (msg.code == 0) {
        var bets = msg.data.bets;
        if (bets.length > 0) {
            populateOpenBets(msg.data);
            /*  $.each(bets, function(k, bet){
             if (bet.payout.length === 0) {
             var recentBet = $('.recentBet:first');
             var newBet = recentBet.clone();
             if (bet.events[0].event_name == "W1") {
             newBet.find('.userBet').text(bet.events[0].team1);
             }else if (bet.events[0].event_name == "W2") {
             newBet.find('.userBet').text(bet.events[0].team2);
             }else{
             newBet.find('.userBet').text("Empate");
             }
             newBet.find('.gameDate').text(formatTime(bet.date_time));
             newBet.find('.gameName').text(bet.events[0].game_name);
             newBet.find('.gameBet').text(bet.amount+"€");
             newBet.find('.gameOdd').text(bet.k);
             newBet.find('.gameProfit').text(bet.possible_win);
             var lastRecentBet = $('.recentBet:last');
             newBet.insertAfter(lastRecentBet);
             newBet.show();
             }
             });*/
        } else {
            apostas.html('<p>Não existem apostas recentes.</p>')
        }
    }
}
function requestSession()
{
    var params = {"command":"request_session","rid":"request_session","params": {"site_id":234,"language":"eng"}};
    send(params);
}
function loginUser()
{
    var params = {"command":"restore_login","rid":"restore_login","params": {"auth_token":phpAuthUser.api_password,"user_id":phpAuthUser.id}};
    send(params);
}

function switchMode(type)
{
    switchEventType = type;
    var params = {
        "command" : "get",
        "rid":"get",
        "params" : {
            "source" : "betting",
            "what" : {"sport" : [], "region" : [], "competition" : []},
            "where" : {
                //"sport" : {"id" : 844},
                "game" : {"type" : type},
                "market" : {
                    "@and": [
                        {"name": "Match Result"},
                        {"col_count": 3}
                    ]
                }
            }
        },
        "subscribe" : false
    };
    if (switchEventType == 0) {
        var gameFilter = {
            "@and" : [
                {"type" : type},
                {"start_ts" : {"@lt" : moment().add($("#events-period-select").val()*1, 'h').unix()}}
            ]
        }
        params.params.where["game"] = gameFilter;
        $("#events-period-select").show();
    } else
        $("#events-period-select").hide();
    send(params);
    return false;
}

function getUserBets()
{
    //$('.apostas:first').append('<p style="position: relative; margin-top:200px;" id="rightSpinner"> </p>')
    //rightSpinner = document.getElementById('rightSpinner');
    //rightSpinnerItem = new Spinner().spin(rightSpinner);
    var params = {"command":"bet_history","rid":"bet_history","params": {"where":{}}};
    send(params);
}


function onMarketSelectChange()
{
    middleMarket.hide();
    var marketSelected = convertToSlug($(this).val());
    $('.'+marketSelected).show();
}
/*
 function onEventBetClick()
 {
 var gameName = $(this).parents('.gameDescription').find('.gameName').text();
 var eventId = $(this).data('eventid');
 var eventOdd = $(this).data('eventodd');
 var eventName = $(this).data('eventname');
 if (eventName == 'W1')
 var gameBet = $(this).parents('.gameDescription').find('.gameName').data('team1');
 else if (eventName == 'W2')
 var gameBet = $(this).parents('.gameDescription').find('.gameName').data('team2');
 else
 var gameBet = eventName;
 simpleBets.show();
 //multipleBets.show();
 simpleBets.off().on('click', function(){
 betType = 0;
 });
 // multipleBets.off().on('click', function(){
 //     betType = 1;
 // });
 if ($(this).hasClass('tabela-bet-active')) {
 $(this).removeClass('tabela-bet-active');
 $('*[data-usereventid="'+eventId+'"]').remove();
 }else {
 $(this).addClass('tabela-bet-active');
 var newBet = defaultBet.clone();
 newBet.find('.gameName').text(gameName);
 newBet.find('.gameBet').text(gameBet);
 newBet.find('.gameOdd').text(eventOdd);
 newBet.find('.gameProfit').text('0');
 newBet.attr('data-usereventid', eventId);
 newBet.attr('data-userBetCount', userBetCount);
 var lastDefaultBet = $('.defaultBet:last');
 newBet.insertAfter(lastDefaultBet);
 newBet.show();
 userBetCount++;
 }
 gameValue = $('.gameValue');
 gameValue.on('keyup', function(){
 $(this).siblings('span.has-error').hide();
 if (! $.isNumeric($(this).val()) || $(this).val() == 0) {
 $(this).siblings('span.has-error').css('display', 'inline-block');
 return false;
 }
 updateTotalBets();
 });
 clearAllBets = $('#clearAllBets')
 clearAllBets.on('click', onClearAllBets);
 updateTotalBets();
 removeUserBet = $('.removeUserBet');
 removeUserBet.off().on('click', onRemoveUserBetClick);
 submitBet = $('.submitBet');
 submitBet.show();
 submitBet.off().on('click', onSubmitBet);
 onBoletimClick();
 }
 */
// Miguel Teixeira
// TODO: all betsData logic should be inside a Model Class
$(function() {
    $("#events-period-select").on('change',function() {
        requestSports(0);
    });
});
var betsData = {
    "bets": [],
    "simpleTotal": {
        "totalOdds": 0,
        "totalBet": 0,
        "totalProfit": 0
    },
    "multipleTotal": {
        "totalOdds": 0,
        "totalBet": 0,
        "totalProfit": 0
    }
};
// TODO: Move to a Model Class
function betExists(eventId) {
    for (var i = 0; i < betsData.bets.length; i++)
        if (betsData.bets[i].eventId == eventId)
            return true;
    return false;
}
// TODO: Move to a Model Class
function getBet(eventId) {
    for (var i=0; i<betsData.bets.length; i++)
        if (betsData.bets[i].eventId == eventId)
            return betsData.bets[i];
    return null;
}
// eventId is a weird name for a bet identifier.
// TODO: Move to a Model Class
function removeBet(eventId) {
    $('*[data-eventid="'+eventId+'"]').removeClass('tabela-bet-active');
    for (var i=0; i<betsData.bets.length; i++)
        if (betsData.bets[i].eventId == eventId) {
            betsData.bets.splice(i, 1);
            return;
        }
}
function updateBetsTotal() {
    calcTotal();
    if (betsData.bets.length) {
        if ($("#aposta-multipla").is(":checked")) {
            var totals = betsData.multipleTotal;
            $(".totalOdd > b").html(totals.totalOdds);
            $(".totalBet > b").html(totals.totalBet);
            $(".totalProfit > b").html(totals.totalProfit);
            $("#totalBets").removeClass("hidden");
        } else
            $("#totalBets").addClass("hidden");
        $("#bets-total-container").removeClass("hidden");
    } else
        $("#bets-total-container").addClass("hidden");
}

// TODO: move this logic to a Model Class.
// TODO: change var names (atm using original names)
// TODO: total cotas, what is that?
function calcTotal() {
    var totalOdds = 1;
    var totalBet = 0;
    var totalProfit = 0;
    for (var i=0; i<betsData.bets.length; i++) {
        totalOdds *= betsData.bets[i].eventOdd;
        totalBet += betsData.bets[i].gameValue;
        totalProfit += betsData.bets[i].eventOdd*betsData.bets[i].gameValue;
    }
    betsData.simpleTotal.totalOdds = totalOdds.toFixed(2);
    betsData.simpleTotal.totalBet = totalBet.toFixed(2);
    betsData.simpleTotal.totalProfit = totalProfit.toFixed(2);
    betsData.multipleTotal.totalOdds = betsData.simpleTotal.totalOdds;
    betsData.multipleTotal.totalBet = Number($("#bets-multiple-input").val()).toFixed(2);
    betsData.multipleTotal.totalProfit = (Number($("#bets-multiple-input").val())*totalOdds).toFixed(2);
}
// TODO: rename, again... weird name.
function onClearAllBets() {
    betsData = {
        "bets": [],
        "simpleTotal": {
            "totalOdds": 0,
            "totalBet": 0,
            "totalProfit": 0
        },
        "multipleTotal": {
            "totalOdds": 0,
            "totalBet": 0,
            "totalProfit": 0
        }
    };
    listSportBets();
}
// TODO: rename func and its vars, again... weird name.
function onEventBetClick() {
    var eventId = $(this).data('eventid');
    // TODO: fix this, maybe add an addBet function to the controller
    //if (betExists(eventId)) {
    //    $(this).removeClass("tabela-bet-active");
    //    removeBet(eventId);
    //    listSportBets();
    //    return;
    //} else
    //    $(this).addClass("tabela-bet-active");
    var gameName = $(this).parents('.gameDescription').find('.gameName').text();
    var eventOdd = $(this).data('eventodd');
    var eventName = $(this).data('eventname');
    var gameBet = eventName;
    if (eventName == 'W1')
        gameBet = $(this).parents('.gameDescription').find('.gameName').data('team1');
    else
        gameBet = $(this).parents('.gameDescription').find('.gameName').data('team2');
    if (betsData.bets.length == 1) {
        $("#aposta-multipla").click();
        $("#bets-multiple-input").val(betsData.bets[0].gameValue);
    }


    if (BetSlip.has(eventId)) {
        $(this).removeClass("tabela-bet-active");
        BetSlip.remove(eventId);
    } else {
        BetSlip.add(new Bet(eventId, gameName, gameBet, eventName, Number(eventOdd), 0));
        $(this).addClass("tabela-bet-active");
    }

    //TODO: weird names given, they have to be changed in the future
    betsData.bets.push({
        "gameName": gameName,
        "eventId": eventId,
        "eventOdd": Number(eventOdd),
        "eventName": eventName,
        "gameBet": gameBet,
        "betProfit": 0,
        "userBetCount" : betsData.bets.length+1, // Just making sure that code doesn't break.
        "gameValue": 0
    });

}

function newBetSuccess(msg)
{
    BetSlip.remove(msg.rid);
    //var rid = msg.rid;
    //removeBet($('*[data-userBetCount="'+rid+'"]').data('usereventid'));
    //if (!betsData.bets.length)
    //    $("#bets-multiple-input-container").addClass("hidden");
    //updateBetsTotal();
    //$('*[data-userBetCount="'+rid+'"]').html('<p class="betSuccess">Aposta submetida com sucesso</p>');
    //setTimeout(function(){
    //    $('*[data-userBetCount="'+rid+'"]').remove();
    //}, 3000);
    //updateBalance();
}
function newBetError(msg)
{
    userError = true;
    var rid = msg.rid;
    if (msg.data.details != null) {
        if (msg.data.details[0].status == 'CHANGE_ODD') {
            $('*[data-userBetCount="'+rid+'"]').find('span.betError').text('O valor da odd alterou para: '+msg.data.details[0].price);
            $('*[data-userBetCount="'+rid+'"]').find('.gameOdd').text(msg.data.details[0].price);
        }else if(msg.data.result == "1560"){
            $('*[data-userBetCount="'+rid+'"]').find('span.betError').text('O valor introduzido é menor que o mínimo permitido.');
        }else if(msg.data.result == "1600"){
            $('*[data-userBetCount="'+rid+'"]').find('span.betError').text('O evento encontra-se suspenso.');
        }else{
            $('*[data-userBetCount="'+rid+'"]').find('span.betError').text('Ocorreu um erro ao submeter a aposta, verifique o saldo e tente novamente.');
        }
    }else{
        $('*[data-userBetCount="'+rid+'"]').find('span.betError').text('Ocorreu um erro ao submeter a aposta, verifique o saldo e tente novamente.');
    }
    alert("Error submitting bet. Don't be sad, it will soon be fixed...");
    //$('*[data-userBetCount="'+rid+'"]').find('.gameValue').val('');
    $('*[data-userBetCount="'+rid+'"]').find('span.betError').css('display', 'inline-block');
    //updateTotalBets();
}
function onSubmitBet()
{
    userError = false;
    if ($("#aposta-simples").is(":checked")) {
        error = validateAllBets();
        if (error) {
            return false;
        }
    }
    submitBet.off('click');
    submitBet.text('Aguarde...');
    var userBets = $('.defaultBet');
    if ($("#aposta-simples").is(":checked"))
        $.each(userBets, function(k, e){
            var bets = [];
            var userBet = $(e);
            if (userBet.is(':visible')) {
                var userBetValue =  $("#aposta-simples").is(":checked")?Number(userBet.find('.gameValue').val()):Number($('#bets-multiple-input').val());
                var eventId = userBet.data('usereventid');
                var rid = userBet.data('userbetcount');
                var eventPrice = Number(userBet.find('.gameOdd').text());
                bets = {"event_id":eventId,"price":eventPrice};
                // bets.push(bet);
                userTotalBets++;
                newBet(userBetValue, bets, rid);
            }
        });
    else
        submitMultipleBets();
    return false;
}
function submitMultipleBets() {
    var request = {
        "command": "do_bet",
        "rid": "get_multi_bets",
        "params": {
            "type": 2, // Express/Parlay
            "mode": 1, // Accept bet(s) if odd has not been changed OR if odd has been increased
            "amount": $("#bets-multiple-input").val()*1,
            "bets": []
        }
    };
    betsData.bets.forEach(function(bet) {
        request.params.bets.push({
            "event_id": bet.eventId,
            "price": bet.eventOdd // event prince (odd)
        });
    });
    send(request);
}
function handleMultipleBetsResponse(response) {
    //if (response.code == 0 && response.data.result == "OK") {
    //    BetSlip.clear(); //It may delete non submitted bets.
    //} else {
    //    alert("Ocorreu um erro ao submeter as apostas multiplas.");
    //}
    ////if (response.code == 0 && response.data.result == "OK") {
    //    showMultipleBetsSuccess(betsData.bets);
    //} else {
    //    $("#bets-multiple-input-container .betError").show();
    //    setTimeout(function() {
    //        $("#bets-multiple-input-container .betError").hide();
    //    }, 4000);
    //}
    //submitBet.on('click', onSubmitBet);
    //submitBet.text('EFECTUAR APOSTA');
}
function showMultipleBetsSuccess(bets) {
    //$("#bets-multiple-input-container").addClass("hidden");
    //bets.forEach(function(bet) {
    //    $('*[data-usereventid="'+bet.eventId+'"]').remove();
    //});
    //while (bets.length)
    //    removeBet(bets[0].eventId);
    //$("#bets-container").html('<div id="sucessMsgContainer" class="col-xs-12 aposta-box-botao tabela-bet-active defaultBet"><p class="betSuccess">Aposta submetida com sucesso</p></div>');
    //var sucessMsgContainer = $("#sucessMsgContainer");
    //setTimeout(function () {
    //    sucessMsgContainer.remove();
    //},3000);
    //updateBetsTotal();
    //updateBalance();
}
function validateAllBets()
{
    $('span.has-error').hide();
    var userBets = $('.defaultBet');
    if (userBets.length <= 1) {
        return false;
    }
    var error = false;
    /* Validade All Bets*/
    $.each(userBets, function(k, e){
        var userBet = $(e);
        if (userBet.is(':visible')) {
            var userBetValue = Number(userBet.find('.gameValue').val());
            if (! $.isNumeric(userBetValue) || userBetValue == 0) {
                userBet.find('.gameValue').siblings('span.has-error').css('display', 'inline-block');
                error = true;
            }
        }
    });
    return error;
}
function onRemoveUserBetClick()
{
    var userEvent = $(this).parents('.defaultBet');
    var eventId = userEvent.data('usereventid');
    $('*[data-eventid="'+eventId+'"]').removeClass('tabela-bet-active');
    userEvent.remove();
    //updateTotalBets();
    return false;
}
function updateTotalBets()
{
    var userBets = $('.defaultBet');
    submitBet = $('.submitBet');
    submitBet.off().on('click', onSubmitBet);
    submitBet.text('EFECTUAR APOSTA');
    if (userBets.length <= 1) {
        totalBets = $('#totalBets');
        //totalBets.hide();
    }else{
        var totalOdds = 1;
        var totalBet = 0;
        var totalProfit = 0;
        $.each(userBets, function(k, e){
            var userBet = $(e);
            if (userBet.is(':visible')) {
                var userBetOdd = Number(userBet.find('.gameOdd').text());
                var userBetValue = Number(userBet.find('.gameValue').val());
                var userBetProfit = Math.round(userBetOdd * userBetValue * 100) / 100;
                totalOdds = Math.round(totalOdds * userBetOdd * 100) / 100
                totalBet += Math.round(userBetValue * 100) / 100;
                userBet.find('.gameProfit').text(userBetProfit);
                totalProfit = Math.round((totalProfit + userBetProfit) * 100) / 100;
            }
        });
        totalBets.find('.totalOdd').text(totalOdds);
        totalBets.find('.totalBet').text(totalBet);
        totalBets.find('.totalProfit').text(totalProfit);
        totalBets.show();
    }
}
function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'')
        ;
}
function updateBalance()
{
    $.ajax({
        type: "GET",
        url: '/get-balance',
        success:function(response){
            $('.balance').text(response.balance);
        }
    });
}
function onBoletimClick()
{
    $('.recentBet:visible').remove();
    $(this).addClass('blue-active');
    boletimId.show();
    pendentes.removeClass('blue-active');
    pendentesId.hide();
    return false;
}
function onPendentesClick()
{
    $(this).addClass('blue-active');
    pendentesId.show();
    boletim.removeClass('blue-active');
    boletimId.hide();
    getUserBets();
    return false;
}
var formatTime = function(unixTimestamp) {
    var dt = new Date(unixTimestamp * 1000);
    var year = dt.getFullYear();
    var month = dt.getUTCMonth()+1;
    var day = dt.getUTCDate();
    var hours = dt.getHours();
    var minutes = dt.getMinutes();
    var seconds = dt.getSeconds();
    // the above dt.get...() functions return a single digit
    // so I prepend the zero here when needed
    if (hours < 10)
        hours = '0' + hours;
    if (minutes < 10)
        minutes = '0' + minutes;
    if (seconds < 10)
        seconds = '0' + seconds;
    return year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
}
$(function(){
    if(!("WebSocket" in window)){
        alert('Oh no, you need a browser that supports WebSockets. How about <a href="http://www.google.com/chrome">Google Chrome</a>?');
    }else{
        leftSpinner = document.getElementById('leftSpinner');
        leftSpinnerItem = new Spinner().spin(leftSpinner);
        middleSpinner = document.getElementById('middleSpinner');
        rightSpinner = document.getElementById('rightSpinner');
        rightSpinnerItem = new Spinner().spin(rightSpinner);
        leftBar = $('.leftbar');
        middle = $('.middle');
        boletim = $('.boletim');
        boletimId = $('#boletim');
        boletim.on('click', onBoletimClick);
        pendentes = $('.pendentes');
        pendentesId = $('#pending-bets-container');
        pendentes.on('click', onPendentesClick);
        apostas = $('.apostas');
        rightBar = $('.rightBar');
        boletim.click();
        //Initialize socket
        //connect();
    }
});