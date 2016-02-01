//TODO: Move functions out that are out of bets scopefunction populateOpenBets(data) {    if (data.bets.length)        $("#bets-button-openBets").prop("disabled", false);    Template.get("/assets/portal/templates/bets_openBets.html", function (template) {        data.bets.forEach(function (bet) {            bet.date_time = moment.unix(bet.date_time).format("ddd HH:mm");            if (bet.events[0].event_name == "W1") {               bet["user_bet"] = bet.events[0].team1;            }else if (bet.events[0].event_name == "W2") {                bet["user_bet"] = bet.events[0].team2;            }else{                bet["user_bet"] = "Empate";            }        });        $("#openBets-container").html(template(data));    });}function addBet(newBet) {    addSimpleBet(newBet);    addMultiBet(newBet);    if (BetSlip.count() == 1) {        $("#betSlip-text-noBets").addClass("hidden");        $("#betSlip-multiBets-footer").removeClass("hidden");        $("#betSlip-button-submit").prop("disabled", false);        $("#betSlip-button-clear").prop("disabled", false);    }    if (BetSlip.count() == 2) {        var multiBetsTab = $("#betSlip-multiBets-tab");        multiBetsTab.prop("disabled", false);        if (BetSlip.count(newBet.marketId, "marketId") == 1)            multiBetsTab.click();    }}function addSimpleBet(newBet) {    Template.get("/assets/portal/templates/bets_simpleBet.html", function (template) {        var simpleBetsContainer = $("#betSlip-simpleBets-container");        simpleBetsContainer.append(template(newBet));        $("#betSlip-simpleBet-button-removeBet-"+newBet.id).click(function (id) {            return function() {BetSlip.remove(id)}        }(newBet.id));        $("#betSlip-field-amount-"+newBet.id).on("keyup", function (id) {            return function() {                $(this).val(parseBetAmount($(this).val()));                BetSlip.update(id, {amount: $(this).val()});            }        }(newBet.id));    });}function addMultiBet(newBet) {    Template.get("/assets/portal/templates/bets_multiBet.html", function (template) {        var multiBetsContent = $("#betSlip-multiBets-content");        multiBetsContent.append(template(newBet));        $("#betSlip-multiBet-button-removeBet-"+newBet.id).click(function (id) {            return function() {BetSlip.remove(id)}        }(newBet.id));    });}function updateBet(newBet) {    updateSimpleBet(newBet);}function updateSimpleBet(newBet) {    $("#betSlip-text-profit-"+newBet.id).html((newBet.amount*newBet.odds).toFixed(2)*1+"€");}function removeBet(removedBet) {    if (BetSlip.count() == 0)        noBetsDefault();    if (BetSlip.count() == 1) {        $("#betSlip-multiBets-tab").prop("disabled", true);        $("#betSlip-simpleBets-tab").click();    }    $("button[data-event-id='"+removedBet.id+"']").removeClass("markets-button-selected");    $("#betSlip-simpleBet-box-"+removedBet.id).remove();    $("#betSlip-multiBet-box-"+removedBet.id).remove();}function clearBets () {    $("#betSlip-simpleBets-container").html("");    $("#betSlip-multiBets-content").html("");    $(".markets-button").removeClass("markets-button-selected"); //TODO: remove style dependence    noBetsDefault();}function noBetsDefault() {    $("#betSlip-text-noBets").removeClass("hidden");    $("#betSlip-multiBets-footer").addClass("hidden");    $("#multiBet-text-error").html("");    $("#betSlip-simpleBets-tab").click();    $("#betSlip-multiBets-tab").prop("disabled",true);    $("#betSlip-button-submit").prop("disabled", true);    $("#betSlip-button-clear").prop("disabled", true);    BetSlip.betMode("simple");}function updateMultiBet(multiBet) {    $("#betSlip-multiBet-totalOdds").html(multiBet.totalOdds());    $("#betSlip-multiBet-totalProfit").html(multiBet.totalProfit()+"€");}function parseBetAmount(amount) {    return amount.substr(0,3).replace(/\D/g,'')*1;}function submitSimpleBets() {    submitButtonHold();    var bets = BetSlip.bets();    for (var i=0; i<bets.length; i++) {        submitSimpleBet(bets[i]);    }}function submitSimpleBet(bet) {    var request = {"command":"do_bet","rid":bet.id,"params": {"type":1,"mode":1,"amount":bet.amount*1,"bets":[{event_id: bet.id, price: bet.odds}]}};    BetsService.request(request);}function simpleBetSuccess(betResponse) {    $("#betSlip-simpleBet-box-"+betResponse.rid).html('<p class="betSuccess">Aposta submetida com sucesso</p>');    setTimeout(function () {        BetSlip.remove(betResponse.rid);    }, 2000); //TODO: this needs to change to a confirmation template and remove must not be delayed}function multiBetSuccess() {    var html = '<div id="betSlip-multiBet-success" class="bets-box vmargin-small">' +                    '<p class="betSuccess">Aposta submetida com sucesso</p>' +                '</div>';    $("#betSlip-multiBets-content").html(html);    setTimeout(function () { //TODO: this needs to change to a confirmation template and remove must not be delayed        BetSlip.clear();        $("#betSlip-multiBet-success").remove();    }, 2000);}function simpleBetError(betResponse, msg) {    $("#simpleBet-text-error-"+betResponse.rid).html(msg);}function multiBetError(betResponse, msg) {    $("#multiBet-text-error").html(msg);    // TEMPORARY -- this logic will be moved into betsValidator    var details = betResponse.data.details;    for (var i=0; i<details.length; i++)        if (details[i].status != "OK")            $("#multiBet-text-error-"+details[i].event_id).html(BetValidator.errorMsg(details[i].status));}//TEMPORARYfunction submitMultiBets() {    submitButtonHold();    var request = {        "command": "do_bet",        "rid": "mx",        "params": {            "type": 2, // Express/Parlay            "mode": 1, // Accept bet(s) if odd has not been changed OR if odd has been increased            "amount": BetSlip.multiBet().amount(),            "bets": []        }    };    BetSlip.bets().forEach(function(bet) {        request.params.bets.push({            "event_id": bet.id,            "price": bet.odds //event price (odds)        });    });    BetsService.request(request);}//Temporaryfunction handleSubmitedBetsResponse(response) {    if ((response.rid == "mx" || $.isNumeric(response.rid)) && response.code == 0) {        BetValidator.validate(response);    }}function submitButtonHold() {    var submitBtn = $("#betSlip-button-submit");    submitBtn.prop("disabled", true);    $("#betSlip-button-clear").prop("disabled", true);    var oldVal = submitBtn.html();    submitBtn.html("Aguarde...");    setTimeout(function() {        $("#betSlip-button-submit").prop("disabled", false);        $("#betSlip-button-clear").prop("disabled", false);        submitBtn.html(oldVal);    }, 5000);}$(function() {    BetSlip.onAdd(addBet);    BetSlip.onUpdate(updateBet);    BetSlip.onClear(clearBets);    BetSlip.onRemove(removeBet);    BetSlip.multiBet().onUpdate(updateMultiBet);    BetValidator.onSimpleBetSuccess(simpleBetSuccess);    BetValidator.onMultiBetSuccess(multiBetSuccess);    BetValidator.onSimpleBetError(simpleBetError);    BetValidator.onMultiBetError(multiBetError);    BetsService.addHandler(handleSubmitedBetsResponse);    $("#bets-button-betSlip").click(function() {        $("#betSlip-container").removeClass("hidden");        $("#openBets-container").addClass("hidden");        var selectedCss = $(this).data("selected-css");        $(this).addClass(selectedCss);        $("#bets-button-openBets").removeClass(selectedCss);    });    $("#bets-button-openBets").click(function() {        $("#betSlip-container").addClass("hidden");        $("#openBets-container").removeClass("hidden");        var selectedCss = $(this).data("selected-css");        $(this).addClass(selectedCss);        $("#bets-button-betSlip").removeClass(selectedCss);        requestUserBets();    });    $("#betSlip-simpleBets-tab").click(function() {        var selectedCss = $(this).data("selected-css");        $(this).addClass(selectedCss);        $("#betSlip-multiBets-tab").removeClass(selectedCss);        $("#betSlip-simpleBets-container").removeClass("hidden");        $("#betSlip-multiBets-container").addClass("hidden");        $("#betSlip-button-submit").off().click(submitSimpleBets);        BetSlip.betMode("simple");    });    $("#betSlip-multiBets-tab").click(function() {        var selectedCss = $(this).data("selected-css");        $(this).addClass(selectedCss);        $("#betSlip-simpleBets-tab").removeClass(selectedCss);        $("#betSlip-multiBets-container").removeClass("hidden");        $("#betSlip-simpleBets-container").addClass("hidden");        $("#betSlip-button-submit").off().click(submitMultiBets);        BetSlip.betMode("multi");    });    $("#betSlip-multiBet-field-amount").on("keyup", function() {        var amount = parseBetAmount($(this).val());        $(this).val(amount);        BetSlip.multiBet().amount(amount);    });    $("#betSlip-button-login").click(function() {        var username = $("#user-login");        var password = $("#pass-login");        if (!username.val())            username.focus();        else if (!password.val())            password.focus();        else            $("#submit-login").click();    });    $("#betSlip-button-clear").click(BetSlip.clear);    $("#betSlip-button-submit").click(submitSimpleBets);    BetSlip.restore();});