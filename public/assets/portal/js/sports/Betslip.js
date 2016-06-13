var Betslip = new (function () {

    var bets = [];

    var totalOdds = 0;

    var betMode = "simple";

    var multiAmount = 0;

    init();

    function init()
    {
        restore();

        $("#betSlip-button-clear").click(clear);

        $("#betSlip-simpleBets-tab").click(simpleClick);

        $("#betSlip-multiBets-tab").click(multiClick);

        $("#betSlip-multiBet-field-amount").on("keyup", multiAmountChange);

        $(window).unload(persistBets);

        $("#betSlip-button-submit").click(submit);

        $("#betSlip-button-login").click(login);

    }

    this.toggle = function (bet) {
        var index = find(bet.id);

        if (index > -1) {
            remove(index);

            return;
        }

        add.call(this, bet);
    };

    this.bets = function()
    {
        return bets;
    };

    function add(bet)
    {
        if (!canAdd(bet))
            return;

        $(this).addClass("markets-button-selected");

        bets.push(bet);

        renderBet(bet);

        selectBetMode("add");
    }

    function renderBet(bet)
    {
        $("#betSlip-simpleBets-container").append(Template.apply("betslip_simple", bet));

        $("#betSlip-simpleBet-button-removeBet-" + bet.id).click(function () {remove(find(bet.id))});

        $("#betSlip-field-amount-" + bet.id).on("keyup", function () {simpleAmountChange.call(this, bet)});

        $("#betSlip-multiBets-content").append(Template.apply('betslip_multi', bet));

        $("#betSlip-multiBet-button-removeBet-" + bet.id).click(function () {remove(find(bet.id))});

    }

    function simpleAmountChange(bet)
    {
        $(this).val(parseAmount($(this).val()));

        bets[find(bet.id)].amount = $(this).val();

        $("#betSlip-text-profit-" + bet.id).html((bet.amount * bet.odds).toFixed(2)*1 + "€");

        updateTotalOdds();
    }

    function multiAmountChange(bet)
    {
        var amount = parseAmount($(this).val());

        $(this).val(amount);

        multiAmount = amount;
    }

    function updateTotalOdds() {
        totalOdds = 0;

        for (var i = 0; i < bets.length; i++)
            totalOdds += bets[i].odds;
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

        $("button[data-event-id='" + bet.id + "']").removeClass("markets-button-selected");

        $("#betSlip-simpleBet-box-" + bet.id).remove();
        $("#betSlip-multiBet-box-" + bet.id).remove();

        bets.splice(index, 1);

        selectBetMode();
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

        var multiBetsTab = $("#betSlip-multiBets-tab");

        if (!hasRepeatedGames() && bets.length > 1) {
            multiBetsTab.prop("disabled", false);

            if (bets.length === 2 && operation == "add")
                multiBetsTab.click();

            return;
        }

        multiBetsTab.prop("disabled", true);

        $("#betSlip-simpleBets-tab").click();
    }

    function activate() {
        $("#betSlip-text-noBets").addClass("hidden");
        $("#betSlip-multiBets-footer").removeClass("hidden");
        $("#betSlip-button-submit").prop("disabled", false);
        $("#betSlip-button-clear").prop("disabled", false);
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
        var selectedCss = $(this).data("selected-css");

        $(this).addClass(selectedCss);
        $("#betSlip-multiBets-tab").removeClass(selectedCss);
        $("#betSlip-simpleBets-container").removeClass("hidden");
        $("#betSlip-multiBets-container").addClass("hidden");

        betMode = "simple";
    }

    function multiClick()
    {
        var selectedCss = $(this).data("selected-css");

        $(this).addClass(selectedCss);
        $("#betSlip-simpleBets-tab").removeClass(selectedCss);
        $("#betSlip-multiBets-container").removeClass("hidden");
        $("#betSlip-simpleBets-container").addClass("hidden");

        betMode = "multi";
    }

    function clear()
    {
        bets = [];

        $("#betSlip-simpleBets-container").html("");
        $("#betSlip-multiBets-content").html("");
        $(".markets-button").removeClass("markets-button-selected"); //TODO: remove style dependence

        noBetsDefault();
    }

    function noBetsDefault()
    {
        $("#betSlip-text-noBets").removeClass("hidden");
        $("#betSlip-multiBets-footer").addClass("hidden");
        $("#multiBet-text-error").html("");
        $("#betSlip-simpleBets-tab").click();
        $("#betSlip-multiBets-tab").prop("disabled",true);
        $("#betSlip-button-submit").prop("disabled", true);
        $("#betSlip-button-clear").prop("disabled", true);

        multiAmount = 0;
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
        return request.bets.push({
            rid: "multi",
            type: "betType",
            amount: parseInt(multiAmount),
            events: bets
        });
    }

    function makeSimple(request)
    {
        for (var i in bets)
            request.bets.push({
                rid: bets[i].id,
                type: "simple",
                amount: bets[i].amount,
                events: bets[i]
            });
    }

    function submitDone(data)
    {
        for (var i in data)
        {
            var bet = data[i];

            if (bet.rid === "multi") {
                multiResponse(bet);

                return;
            }

            simpleResponse();
        }
    }

    function multiResponse(bet)
    {
        if (bet.code === 0)
            multiSuccess();
        else
            multiError(bet.msg);
    }

    function multiSuccess()
    {
        var html = '<div id="betSlip-multiBet-success" class="bets-box vmargin-small">' +
            '<p class="betSuccess">Aposta submetida com sucesso</p>' +
            '</div>';
        $("#betSlip-multiBets-content").html(html);
        $("#betSlip-multiBets-footer").addClass("hidden");
        setTimeout(function () {
            Betslip.clear();
            $("#betSlip-multiBet-success").remove();
        }, 2000);
    }

    function multiError(msg)
    {
        $("#multiBet-text-error").html(msg);
    }

    function simpleResponse(bet)
    {
        if (bet.code === 0)
            simpleSuccess();
        else
            simpleError(bet.msg);
    }

    function simpleSuccess(rid)
    {
        $("#betSlip-simpleBet-box-"+rid).html('<p class="betSuccess">Aposta submetida com sucesso</p>');
        setTimeout(function () {
            Betslip.remove(rid);
        }, 2000);
    }

    function simpleError(msg)
    {
        $("#simpleBet-text-error-"+rid).html(msg);
    }

    function disableSubmit()
    {
        var submitBtn = $("#betSlip-button-submit");

        submitBtn.prop("disabled", true);
        submitBtn.html("Aguarde...");

        $("#betSlip-button-clear").prop("disabled", true);
    }

    function enableSubmit()
    {
        setTimeout(function() {
            if (bets.length) {
                $("#betSlip-button-submit").prop("disabled", false);
                $("#betSlip-button-clear").prop("disabled", false);
            }

            $("#betSlip-button-submit").html("EFECTUAR APOSTA");
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
