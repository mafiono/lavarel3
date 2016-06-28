var Betslip = new (function () {

    var bets = [];

    var betMode = "simple";

    var multiAmount = 0;

    init();

    function init()
    {
        restore();

        $("#betslip-bulletinTab").click(bulletinClick);

        $("#betslip-openBetsTab").click(openBetsClick);

        $("#betslip-simpleTab").click(simpleClick);

        $("#betslip-multiTab").click(multiClick);

        $("#betslip-multiAmount").on("keyup", multiAmountChange);

        $(window).unload(persistBets);

        $("#betslip-submit").click(submit);

        $("#betslip-login").click(login);
    }

    function bulletinClick()
    {
        $("#betslip-bulletinTab").addClass("selected");
        $("#betslip-openBetsTab").removeClass("selected");

        $("#betslip-bulletinContainer").removeClass("hidden");
        $("#betslip-openBetsContainer").addClass("hidden");
    }

    function openBetsClick()
    {
        $.get("/open-bets").done(renderOpenBets);
    }

    function renderOpenBets(data)
    {
        if (data.bets.length == 0)
            return;

        openBetsData(data);

        $("#betslip-openBetsContainer").html(Template.apply('betslip_open_bets', data));


        $("#betslip-bulletinTab").removeClass("selected");
        $("#betslip-openBetsTab").addClass("selected");

        $("#betslip-bulletinContainer").addClass("hidden");
        $("#betslip-openBetsContainer").removeClass("hidden");
    }

    function openBetsData(data)
    {
        var bets = data.bets;

        for (var i in bets)
            openEventsData(bets[i].events);
    }

    function openEventsData(events)
    {
        for (var i in events)
            dateAndTime(events[i], 'game_date');
    }

    function dateAndTime(event, fieldName)
    {
        event.date = moment.utc(event[fieldName]).local().format("DD MMM");
        event.time = moment.utc(event[fieldName]).local().format("HH:mm");
    }

    this.toggle = function (bet) {
        var index = find(bet.id);

        if (index > -1) {
            remove(index);

            return;
        }

        add(bet);
    };

    this.bets = function()
    {
        return bets;
    };

    function add(bet)
    {
        if (!canAdd(bet))
            return;

        $("button[data-event-id='" + bet.id + "']").addClass("selected");

        bets.push(bet);

        renderBet(bet);

        selectBetMode("add");

        updateFooters();
    }

    function renderBet(bet)
    {

        betData(bet);

        $("#betslip-simpleContent").append(Template.apply("betslip_simple", bet));

        $("#betslip-simpleBet-button-removeBet-" + bet.id).click(function () {remove(find(bet.id))});

        $("#betslip-field-amount-" + bet.id).on("keyup", function () {simpleAmountChange.call(this, bet)});

        $("#betslip-multiBets-content").append(Template.apply('betslip_multi', bet));

        $("#betslip-multiBet-button-removeBet-" + bet.id).click(function () {remove(find(bet.id))});

    }

    function betData(bet)
    {
        dateAndTime(bet, 'gameDate');
    }

    function simpleAmountChange(bet)
    {
        $(this).val(parseAmount($(this).val()));

        bets[find(bet.id)].amount = $(this).val();

        $("#betslip-text-profit-" + bet.id).html("€ " + (bet.amount * bet.odds).toFixed(2));

        updateSimpleFooter();
    }

    function multiAmountChange()
    {
        var amount = parseAmount($(this).val());

        $(this).val(amount);

        multiAmount = amount;

        updateMultiFooter();
    }

    function updateFooters()
    {
        updateSimpleFooter();
        updateMultiFooter();
    }

    function updateSimpleFooter()
    {
        var total = 0;
        var profit = 0;

        for (var i in bets) {
            total += bets[i].amount*1;
            profit += bets[i].amount*bets[i].odds;
        }

        $("#betslip-simpleCount").html(bets.length);
        $("#betslip-simpleTotal").html("€ " + total.toFixed(2));
        $("#betslip-simpleProfit").html("€ " + profit.toFixed(2));
    }

    function updateMultiFooter()
    {
        var totalOdds = 1;

        for (var i = 0; i < bets.length; i++)
            totalOdds *= bets[i].odds;

        $("#betslip-multiOdds").html(totalOdds.toFixed(2));
        $("#betslip-multiProfit").html("€ " + (multiAmount*totalOdds).toFixed(2));
    }

    function find(value, fieldName)
    {
        var field = fieldName?fieldName:"id";

        for (var i = 0; i < bets.length; i++)
            if (bets[i][field] == value)
                return i;
        return -1;
    }

    function has(value, fieldName)
    {
        return find(value, fieldName) > -1;
    }

    function remove(index)
    {
        var bet = bets[index];

        $("button[data-event-id='" + bet.id + "']").removeClass("selected");

        $("#betslip-simpleBet-box-" + bet.id).remove();
        $("#betslip-multiBet-box-" + bet.id).remove();

        bets.splice(index, 1);

        selectBetMode();

        updateFooters();
    }

    function removeById(id)
    {
        remove(find(id));
    }

    function parseAmount(amount)
    {
        return amount.substr(0,3).replace(/\D/g,'')*1;
    }

    function canAdd(bet)
    {
        if (betMode == "simple")
            return true;

        return !has(bet.gameId, "gameId");
    }

    function selectBetMode(operation)
    {
        if (bets.length == 0)
            noBetsDefault();

        if (bets.length == 1)
            activate();

        var multiBetsTab = $("#betslip-multiTab");

        if (!hasRepeatedGames() && bets.length > 1) {
            multiBetsTab.prop("disabled", false);

            if (bets.length === 2 && operation == "add")
                multiBetsTab.click();

            return;
        }

        $("#betslip-simpleTab").click();
    }

    function activate()
    {
        $("#betslip-noBets").addClass("hidden");
        $("#betslip-simpleFooter").removeClass("hidden");
        $("#betslip-multiFooter").removeClass("hidden");

        $("#betslip-submit").prop("disabled", false);
    }

    function hasRepeatedGames()
    {
        var games = {};

        for (var i=0; i < bets.length; i++) {
            if (games[bets[i].gameId])
                return true;

            games[bets[i].gameId] = true;
        }

        return false;
    }

    function simpleClick()
    {
        $(this).addClass("selected");
        $("#betslip-multiTab").removeClass("selected");
        $("#betslip-simpleContainer").removeClass("hidden");
        $("#betslip-multiContainer").addClass("hidden");

        betMode = "simple";
    }

    function multiClick()
    {
        if (hasRepeatedGames() || bets.length < 2)
            return;

        $(this).addClass("selected");
        $("#betslip-simpleTab").removeClass("selected");
        $("#betslip-multiContainer").removeClass("hidden");
        $("#betslip-simpleContainer").addClass("hidden");

        betMode = "multi";
    }

    function clear()
    {
        bets = [];

        $("#betslip-simpleContent").html("");
        $("#betslip-multiBets-content").html("");
        $(".markets-button").removeClass("selected");

        noBetsDefault();
    }

    function noBetsDefault()
    {
        $("#betslip-noBets").removeClass("hidden");
        $("#betslip-simpleFooter").addClass("hidden");
        $("#betslip-multiFooter").addClass("hidden");
        $("#multiBet-text-error").html("");
        $("#betslip-simpleTab").click();
        $("#betslip-submit").prop("disabled", true);

        multiAmount = 0;

        $("#betslip-multiAmount").val(multiAmount);
    }

    function persistBets()
    {
        Cookies.set("bets", bets);
    }

     function restore()
     {
        var oldBets = Cookies.getJSON("bets");

        if (!oldBets)
            return;

        for (var i = 0; i < oldBets.length; i++)
            add(oldBets[i]);
    }

    function submit()
    {
        disableSubmit();

        $.post("/desporto/betslip", makeRequest())
            .done(submitDone)
            .fail(submitFail)
            .always(submitAlways);
    }

    function makeRequest()
    {
        var request = {bets: []};

        if (betMode === "multi")
            return makeMulti(request);

        return makeSimple(request);
    }

    function makeMulti(request)
    {
        request.bets.push({
            rid: "multi",
            type: betMode,
            amount: parseInt(multiAmount),
            events: bets
        });

        return request;
    }

    function makeSimple(request)
    {
        for (var i in bets)
            request.bets.push({
                rid: bets[i].id,
                type: "simple",
                amount: bets[i].amount,
                events: [bets[i]]
            });

        return request;
    }

    function submitDone(data)
    {
        var bets = data.bets;

        for (var i in bets)
        {
            var bet = bets[i];

            if (bet.rid === "multi") {
                multiResponse(bet);

                return;
            }

            simpleResponse(bet);
        }
    }

    function multiResponse(bet)
    {
        if (bet.code === 0)
            multiSuccess();
        else
            multiError(bet.errorMsg);
    }

    function multiSuccess()
    {
        var html = '<div id="betslip-multiBet-success" class="bets-box vmargin-small">' +
            '<p class="betSuccess">Aposta submetida com sucesso</p>' +
            '</div>';
        $("#betslip-multiBets-content").html(html);
        $("#betslip-multiFooter").addClass("hidden");
        setTimeout(function () {
            clear();
            $("#betslip-multiBet-success").remove();
        }, 2000);
    }

    function multiError(msg)
    {
        $("#multiBet-text-error").html(msg);
    }

    function simpleResponse(bet)
    {
        if (bet.code === 0)
            simpleSuccess(bet.rid);
        else
            simpleError(bet);
    }

    function simpleSuccess(rid)
    {
        $("#betslip-simpleBet-box-"+rid).html('<p class="betSuccess">Aposta submetida com sucesso</p>');
        setTimeout(function () {
            removeById(rid);
        }, 2000);
    }

    function simpleError(bet)
    {
        $("#simpleBet-text-error-"+bet.rid).html(bet.errorMsg);
    }

    function disableSubmit()
    {
        var submitBtn = $("#betslip-submit");

        submitBtn.prop("disabled", true);
        submitBtn.html("Aguarde...");
    }

    this.enableSubmit = function()
    {
        enableSubmit();
    };

    function enableSubmit()
    {
        setTimeout(function() {
            var submitBtn = $("#betslip-submit");

            submitBtn.prop("disabled", bets.length == 0);
            submitBtn.html("EFECTUAR APOSTA");
        }, 2100);
    }

    function submitFail()
    {
        alert("O serviço de apostas não está disponível.");
    }

    function submitAlways()
    {
        enableSubmit();
    }

    function login()
    {
        var username = $("#user-login");
        var password = $("#pass-login");

        if (!username.val())
            username.focus();
        else if (!password.val())
            password.focus();
        else
            $("#submit-login").click();
    }

})();
