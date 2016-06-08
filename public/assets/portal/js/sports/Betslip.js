var Betslip = new (function () {

    var bets = [];

    var totalOdds = 0;

    var betMode = "simple";

    var multiAmount = 0;

    init();

    this.toggle = function (bet) {
        var index = find(bet.id);

        if (index > -1) {
            remove(index);

            return;
        }

        add.call(this, bet);
    };

    function init()
    {
        restore();

        $("#betSlip-button-clear").click(clear);

        $("#betSlip-simpleBets-tab").click(simpleClick);

        $("#betSlip-multiBets-tab").click(multiClick);

        $("#betSlip-multiBet-field-amount").on("keyup", multiAmountChange);

        $(window).unload(persistBets);

        //$("#betSlip-button-submit").click(submitBets);
    }

    function add(bet)
    {
        if (!canAdd(bet))
            return;

        $(this).addClass("markets-button-selected");

        bets.push(bet);

        renderBet(bet);

        selectBetMode();
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

    function selectBetMode()
    {
        if (bets.length == 1) {
            $("#betSlip-text-noBets").addClass("hidden");
            $("#betSlip-multiBets-footer").removeClass("hidden");
            $("#betSlip-button-submit").prop("disabled", false);
            $("#betSlip-button-clear").prop("disabled", false);

            return;
        }

        var multiBetsTab = $("#betSlip-multiBets-tab");
        if (!hasRepeatedGames()) {
            multiBetsTab.prop("disabled", false);
            if (bets.length === 2)
                multiBetsTab.click();

            return;
        }

        multiBetsTab.prop("disabled", true);

        $("#betSlip-simpleBets-tab").click();
    }

    function hasRepeatedGames()
    {
        var games = {}

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

     function restore() {
        var oldBets = Cookies.getJSON("bets");

        if (!oldBets)
            return;

        for (var i = 0; i < oldBets.length; i++)
            add(oldBets[i]);
    }

})();

//    Template.get("/assets/portal/templates/bets_simpleBet.html", function (template) {
//        var simpleBetsContainer = $("#betSlip-simpleBets-container");
//        simpleBetsContainer.append(template(newBet));
//
//        $("#betSlip-field-amount-"+newBet.id).on("keyup", function (id) {
//            return function() {
//                $(this).val(parseBetAmount($(this).val()));
//                Betslip.update(id, {amount: $(this).val()});
//            }
//        }(newBet.id));
//    });
//
//
//
//    function has(value)
//    {
//
//
//        return find(value) > -1;
//    }
//
//
//    var multiBet = new (function() {
//        var totalOdds = 0;
//        var amount = 0;
//        var updateHandler = doNothing;
//
//        this.totalOdds = function() {
//            return totalOdds;
//        };
//
//        this.amount = function(newAmount) {
//            if (arguments.length) {
//                amount = newAmount;
//                updateHandler(multiBet);
//            } else
//                return amount;
//        };
//
//        this.totalProfit = function() {
//            return (amount*totalOdds).toFixed(2)*1;
//        };
//
//        this.onUpdate = function(callback) {
//            updateHandler = callback;
//        };
//
//        this.update = function() {
//            totalOdds = bets.length?1:0;
//            for (var i=0; i<bets.length; i++)
//                totalOdds *= bets[i].odds;
//            totalOdds = totalOdds.toFixed(2)*1;
//            updateHandler(multiBet);
//        }
//
//
//    })();
//
//
//
//    this.update = function(id, newBet) {
//        var index = find(id);
//        if (find(id) >= 0)
//            for (var key in newBet)
//                bets[index][key] = newBet[key];
//        updateHandler(bets[index]);
//    };
//
//    this.bets = function() {
//        return bets;
//    };
//
//
//
//    this.remove = function(id) {
//        var index = find(id);
//        var removedBet = bets[index];
//        if (index >= 0)
//            bets.splice(index, 1);
//        removeHandler(removedBet);
//        multiBet.update();
//    };
//
//    this.clear = function() {
//        bets = [];
//        clearHandler();
//        multiBet.update();
//    };
//
//    this.count = function(keyValue, fieldName) {
//        if (keyValue) {
//            var count = 0;
//            var fieldName = fieldName?fieldName:"id";
//            for (var i = 0; i < bets.length; i++)
//                if (bets[i][fieldName] == keyValue)
//                    count++;
//            return count;
//        }
//        return bets.length;
//    };
//
//    this.betMode = function(mode) {
//        if (mode)
//            betMode = mode;
//        return betMode;
//    };
//
//    this.onAdd = function (callback) {
//        addHandler = callback;
//    };
//
//    this.onUpdate = function (callback) {
//        updateHandler = callback;
//    };
//
//    this.onRemove = function (callback) {
//        removeHandler = callback;
//    };
//
//    this.onClear = function (callback) {
//        clearHandler = callback;
//    };
//
//    this.multiBet = function() {
//        return multiBet;
//    };
//
//    this.isMultiBetValid = function() {
//        if (this.count() < 2)
//            return false;
//        for (var i=0; i<bets.length; i++)
//            if (this.count(bets[i].gameId, 'gameId') > 1)
//                return false;
//        return true;
//    };
//
//    this.restore = function() {
//        if (oldBets) {
//            bets = [];
//            for (var i = 0; i < oldBets.length; i++)
//                this.add(oldBets[i]);
//        }
//    };
//
//
//
//})();
//
//function Bet(id, name, odds, amount, marketId, marketName, gameId, gameName, gameDate) {
//    this.id = id;
//    this.name = name;
//    this.odds = odds;
//    this.amount = amount;
//    this.marketId = marketId;
//    this.marketName = marketName;
//    this.gameId = gameId;
//    this.gameName = gameName;
//    this.gameDate = gameDate;
//}
//
//

//var gameId = $(this).data("game-id");
//var gameDate =
//var eventId= ;
//var eventName = $(this).data("event-name");
//var eventOdds = $(this).data("event-price");
//var gameName =
//
//if (Betslip.has(eventId)) {
//    $(this).removeClass("markets-button-selected");
//    Betslip.remove(eventId);
//} else if (Betslip.betMode() != "multi" || !Betslip.has(gameId,"gameId")) {
//    Betslip.add(new Bet(eventId, eventName, eventOdds, 0, marketId, marketName, gameId, gameName, gameDate));
//    $(this).addClass("markets-button-selected");
//}