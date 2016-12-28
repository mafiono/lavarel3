Betslip = new (function () {

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

        $("#betslip-submit").click(preSubmit);

        $("#betslip-accept").click(clearOldOdds);

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
        $(this).val(parseAmount($(this).val(), $(this)));

        bets[find(bet.id)].amount = $(this).val();

        $("#betslip-text-profit-" + bet.id).html("€ " + (bet.amount * bet.odds).toFixed(2));

        updateSimpleFooter();
    }

    function multiAmountChange()
    {
        var amount = parseAmount($(this).val(), $(this));

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
        var totalOldOdds = 1;

        for (var i = 0; i < bets.length; i++){
            totalOdds *= bets[i].odds;

            totalOldOdds *= (bets[i].oldOdds ? bets[i].oldOdds : bets[i].odds);
        }

        if (oddsChanged())
            $("#betslip-multiOldOdds").html(totalOldOdds.toFixed(2));

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

        if (!oddsChanged())
            hideAcceptOdds();

        selectBetMode();

        updateFooters();
    }

    function removeById(id)
    {
        remove(find(id));
    }

    function parseAmount(amount, elem)
    {
        if ( !amount || /^([0-9]{1,4})((\.$)|(\.[0-9]{1,2}$))?$/.test(amount) ) {
            elem.data("old-amount", amount);
            return amount;
        }

        return elem.data("old-amount");
    }

    function canAdd(bet) {
        if (bets.length > 19) {
            alert("Não pode ultrapassar 20 apostas.");
            return false;
        }

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
        var simpleIcon = $(this).find("i");

        if (bets.length) {
            $(this).addClass("selected");
            simpleIcon.removeClass("fa-plus");
            simpleIcon.addClass("fa-caret-down");
            simpleIcon.removeClass("inactive");
            simpleIcon.addClass("active");
        } else {
            $(this).removeClass("selected");
            simpleIcon.removeClass("fa-caret-down");
            simpleIcon.addClass("fa-plus");
            simpleIcon.removeClass("active");
        }

        var multiTab = $("#betslip-multiTab");
        var multiIcon = multiTab.find("i");

        multiIcon.removeClass("fa-caret-down");
        multiIcon.addClass("fa-plus");
        multiIcon.removeClass("active");
        multiIcon.addClass("inactive");

        multiTab.removeClass("selected");
        $("#betslip-simpleContainer").removeClass("hidden");
        $("#betslip-multiContainer").addClass("hidden");

        betMode = "simple";
    }

    function multiClick()
    {
        if (hasRepeatedGames() || bets.length < 2)
            return;

        var simpleTab = $("#betslip-simpleTab");
        var simpleIcon = simpleTab.find("i");

        simpleIcon.removeClass("fa-caret-down");
        simpleIcon.addClass("fa-plus");
        simpleIcon.addClass("inactive");
        simpleIcon.removeClass("active");

        var multiIcon = $(this).find("i");

        multiIcon.removeClass("fa-plus");
        multiIcon.addClass("fa-caret-down");
        multiIcon.addClass("active");
        multiIcon.removeClass("inactive");

        $(this).addClass("selected");
        simpleTab.removeClass("selected");
        $("#betslip-multiContainer").removeClass("hidden");
        $("#betslip-simpleContainer").addClass("hidden");

        betMode = "multi";
    }

    function clear()
    {
        bets = [];

        $("#betslip-simpleContent").html("");
        $("#betslip-multiBets-content").html("");
        $(".selection-button").removeClass("selected");

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
        Cookies.set("bets", bets, {expires: 30});
    }

     function restore()
     {
        var oldBets = Cookies.getJSON("bets");

        if (!oldBets)
            return;

        for (var i = 0; i < oldBets.length; i++)
            add(oldBets[i]);

         $(function() {$("#betslip-simpleTab").click();});

         if (oddsChanged())
            showAcceptOdds();
    }

    function preSubmit()
    {
        SelectionsUpdater.update();

        fetchOdds();
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

            if (bet.rid === "multi")
                multiResponse(bet);
            else
                simpleResponse(bet);
        }

        enableSubmit();
    }

    function multiResponse(bet)
    {
        if (bet.code === 0)
            multiSuccess();
        else
            multiError(bet.errorMsg, bet.eventId);
    }

    function multiSuccess()
    {
        $("#betslip-multiBets-content").html('<div class="success">Aposta submetida com sucesso</div>');
        $("#betslip-multiFooter").addClass("hidden");
        setTimeout(function () {
            clear();
            $("#betslip-multiBet-success").remove();
        }, 2000);
    }

    function multiError(msg, eventId)
    {
        if (eventId)
            $("#betslip-multiBet-box-"+eventId).addClass("eventError");

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
        $("#betslip-simpleBet-box-"+rid).html('<div class="success">Aposta submetida com sucesso</div>');
        setTimeout(function () {
            removeById(rid);
        }, 2000);
    }

    function simpleError(bet)
    {
        $("#betslip-simpleBet-box-"+bet.rid).addClass("eventError");

        $("#simpleBet-text-error-"+bet.rid).html(bet.errorMsg);
    }

    function disableSubmit()
    {
        var submitBtn = $("#betslip-submit");

        submitBtn.prop("disabled", true);
        submitBtn.html("Aguarde...");

        $("#blocker-container").addClass("blocker");
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

            $("#blocker-container").removeClass("blocker");
        }, 2100);
    }

    function submitFail()
    {
        alert("O serviço de apostas não está disponível.");

        enableSubmit();
    }

    function submitAlways()
    {
        // enableSubmit();
    }

    function login()
    {
        var username = $("#user-login");
        var password = $("#pass-login");

        if (!username.val() || !password.val())
            page("/registar");


        $("#submit-login").click();
    }

    this.applySelected = function (container)
    {
        applySelected(container)
    };

    function applySelected (container)
    {
        for (var i in bets)
            container.find("[data-event-id='" + bets[i].id + "']").addClass("selected");
    }

    function fetchOdds()
    {
        var ids = [];

        for (var i in bets)
            ids.push(bets[i].id)

        if (ids.length)
            $.getJSON(ODDS_SERVER + 'selections?ids=' + ids.join(','))
                .done(updateOdds)
                .fail(submitFail)
                .always(submitAlways);
    }

    function updateOdds(data)
    {
        var selections = data.selections;

        for (var i in selections) {
            var selection = selections[i];

            var bet = bets[find(selection.id)];

            if (bet.odds != selection.decimal) {
                if (!bet.oldOdds)
                    bet.oldOdds = bet.odds;

                bet.odds = selection.decimal;
            }
        }

        applyOldOdds();

        if (oddsChanged()) {
            showAcceptOdds();

            return;
        }

        submit();
    }

    function oddsChanged()
    {
        for (var i in bets)
            if (bets[i].oldOdds)
                return true;

        return false;
    }

    function applyOldOdds()
    {
        for (var i in bets) {
            var bet = bets[i];

            if (bet.oldOdds) {
                var simple = $("#betslip-simpleBet-box-" + bet.id);

                simple.find("span.odds").html(bet.odds);
                simple.find("span.odds.old").html(bet.oldOdds);

                var multi = $("#betslip-multiBet-box-" + bet.id);

                multi.find("span.odds").html(bet.odds);
                multi.find("span.odds.old").html(bet.oldOdds);
            }
        }

        updateFooters();
    }

    function clearOldOdds()
    {
        for (var i in bets) {
            var bet = bets[i];

            if (bet.oldOdds) {
                delete bet.oldOdds;

                $("#betslip-simpleBet-box-" + bet.id).find("span.odds.old").html("");
                $("#betslip-multiBet-box-" + bet.id).find("span.odds.old").html("");
            }

        }
        $("#betslip-multiOldOdds").html("");

        hideAcceptOdds();
    }

    function showAcceptOdds()
    {
        $("#betslip-accept").removeClass("hidden");
        $("#betslip-submit").addClass("hidden");
    }

    function hideAcceptOdds()
    {
        $("#betslip-accept").addClass("hidden");
        $("#betslip-submit").removeClass("hidden");
    }

})();