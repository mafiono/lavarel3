Betslip = new (function () {

    var bets = [];

    var betMode = "simple";

    var multiAmount = 0;

    var status = 'none';

    this.init = function() {
        init();
    };

    this.bets = bets;

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

    function addOrToggle(bet, toggle)
    {
        $("#betslip-bulletinTab").click();

        var index = find(bet.id);

        if (index > -1) {
            if (toggle) {
                remove(index);
            }

            return;
        }

        add(bet);
    }

    this.toggle = function (bet) {
        addOrToggle(bet, true);
    };

    this.add = function (bet) {
        addOrToggle(bet)
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

        let simpleContent = $("#betslip-simpleContent");
        var newBet = $(Template.apply("betslip_simple", bet));
        simpleContent.append(newBet);
        newBet.css({'transition': 'none'}).hide().fadeIn(500).show();
        simpleContent.animate({ scrollTop: simpleContent.prop('scrollHeight') }, 1000);

        $("#betslip-simpleBet-button-removeBet-" + bet.id).click(function () {remove(find(bet.id))});

        $("#betslip-field-amount-" + bet.id).on("keyup", function () {simpleAmountChange.call(this, bet)});

        let multiContent = $("#betslip-multiBets-content");
        newBet = $(Template.apply('betslip_multi', bet));
        multiContent.append(newBet);
        newBet.css({'transition': 'none'}).hide().fadeIn(500).show();
        multiContent.animate({ scrollTop: multiContent.prop('scrollHeight') }, 1000);

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

        $("#betslip-text-profit-" + bet.id).html("€ " + number_format(bet.amount * bet.odds, 2, '.', ' '));

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
        $("#betslip-simpleTotal").html("€ " + number_format(total, 2, '.', ' '));
        $("#betslip-simpleProfit").html("€ " + number_format(profit, 2, '.', ' '));
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
            $("#betslip-multiOldOdds").html(number_format(totalOldOdds, 2, '.', ' '));

        multiAmount = $("#betslip-multiAmount").val()*1;

        $("#betslip-multiOdds").html(number_format(totalOdds, 2, '.', ' '));
        $("#betslip-multiProfit").html("€ " + number_format(multiAmount*totalOdds, 2, '.', ' '));
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
            $.fn.popup({
                type: 'warning',
                title: 'Atenção',
                text: "Uma aposta multipla não pode conter mais de 20 apostas."
            });
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
            simpleIcon.removeClass("cp-plus");
            simpleIcon.addClass("cp-caret-down");
            simpleIcon.removeClass("inactive");
            simpleIcon.addClass("active");
        } else {
            $(this).removeClass("selected");
            simpleIcon.removeClass("cp-caret-down");
            simpleIcon.addClass("cp-plus");
            simpleIcon.removeClass("active");
        }

        var multiTab = $("#betslip-multiTab");
        var multiIcon = multiTab.find("i");

        multiIcon.removeClass("cp-caret-down");
        multiIcon.addClass("cp-plus");
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

        simpleIcon.removeClass("cp-caret-down");
        simpleIcon.addClass("cp-plus");
        simpleIcon.addClass("inactive");
        simpleIcon.removeClass("active");

        var multiIcon = $(this).find("i");

        multiIcon.removeClass("cp-plus");
        multiIcon.addClass("cp-caret-down");
        multiIcon.addClass("active");
        multiIcon.removeClass("inactive");

        $(this).addClass("selected");
        simpleTab.removeClass("selected");
        $("#betslip-multiContainer").removeClass("hidden");
        $("#betslip-simpleContainer").addClass("hidden");

        betMode = "multi";
    }

    this.clear = function () {
        clear();
    };

    function clear()
    {
        while (bets.length > 0) {
            bets.pop();
        }

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
        localStorage.setItem("bets", JSON.stringify(bets));
        // Cookies.set("bets", bets, {expires: 30});
    }

    function restore() {
        var _old = localStorage.getItem('bets') || "[]";
        var oldBets = JSON.parse(_old);
        // var oldBets = Cookies.getJSON("bets");

        if (!oldBets)
            return;

        for (var i = 0; i < oldBets.length; i++)
            if (moment().diff(moment(oldBets[i].gameDate), 'minutes') < 180) {
                oldBets[i]['origin'] = "storage";
                add(oldBets[i]);
            }


        $(function () {
            $("#betslip-simpleTab").click();
        });

        if (oddsChanged())
            showAcceptOdds();
    }

    function preSubmit()
    {
        $("#betslip-submit").prop("disabled", true);

        SelectionsUpdater.update();

        fetchOdds();
    }

    function submit()
    {
        disableSubmit();

        if (status === 'none') {
            status = 'submitting';

            $.post("/desporto/betslip", makeRequest())
                .done(submitDone)
                .fail(submitFail)
                .always(submitAlways);
        }
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
            amount: multiAmount.toFixed(2),
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
        // console.log(arguments);
        $.fn.popup({
            type: 'error',
            title: 'Erro',
            text: "O serviço de apostas não está disponível."
        });

        enableSubmit();
    }

    function submitAlways()
    {
        // enableSubmit();
        setTimeout(function () {
            status = 'none';
        }, 1000);
    }

    function login()
    {
        if (MobileHelper.isMobile()) {
            page("/mobile/login");

            return;
        }

        var username = $("#user-login");
        var password = $("#pass-login");

        if (!username.val() || !password.val()) {
            page("/registar");

            return;
        }

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

            if (bet.odds != selection.decimal && selection.decimal > 1) {
                if (!bet.oldOdds)
                    bet.oldOdds = bet.odds;

                bet.odds = selection.decimal;
            }
        }

        applyOldOdds();

        if (oddsChanged()) {
            $("#betslip-submit").prop("disabled", false);

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
