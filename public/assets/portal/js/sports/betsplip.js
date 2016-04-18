// TODO: optimize find to O(log(n))
var BetSlip = new (function () {
    var oldBets = Cookies.getJSON("bets");
    var bets = oldBets?oldBets:[];
    var betMode = "simple";
    var addHandler = doNothing;
    var clearHandler = doNothing;
    var removeHandler = doNothing;
    var updateHandler = doNothing;

    $(window).unload(function() {
        Cookies.set("bets", bets);
    });

    var multiBet = new (function() {
        var totalOdds = 0;
        var amount = 0;
        var updateHandler = doNothing;

        this.totalOdds = function() {
            return totalOdds;
        };

        this.amount = function(newAmount) {
            if (arguments.length) {
                amount = newAmount;
                updateHandler(multiBet);
            } else
                return amount;
        };

        this.totalProfit = function() {
            return (amount*totalOdds).toFixed(2)*1;
        };

        this.onUpdate = function(callback) {
            updateHandler = callback;
        };

        this.update = function() {
            totalOdds = bets.length?1:0;
            for (var i=0; i<bets.length; i++)
                totalOdds *= bets[i].odds;
            totalOdds = totalOdds.toFixed(2)*1;
            updateHandler(multiBet);
        }


    })();


    this.add = function(newBet) {
        bets.push(newBet);
        addHandler(newBet);
        multiBet.update();
    };

    this.update = function(id, newBet) {
        var index = find(id);
        if (find(id) >= 0)
            for (var key in newBet)
                bets[index][key] = newBet[key];
        updateHandler(bets[index]);
    };

    this.bets = function() {
        return bets;
    };

    this.has = function(id, fieldName) {
        return (find(id, fieldName) >= 0);
    };

    this.remove = function(id) {
        var index = find(id);
        var removedBet = bets[index];
        if (index >= 0)
            bets.splice(index, 1);
        removeHandler(removedBet);
        multiBet.update();
    };

    this.clear = function() {
        bets = [];
        clearHandler();
        multiBet.update();
    };

    this.count = function(keyValue, fieldName) {
        if (keyValue) {
            var count = 0;
            var fieldName = fieldName?fieldName:"id";
            for (var i = 0; i < bets.length; i++)
                if (bets[i][fieldName] == keyValue)
                    count++;
            return count;
        }
        return bets.length;
    };

    this.betMode = function(mode) {
        if (mode)
            betMode = mode;
        return betMode;
    };

    this.onAdd = function (callback) {
        addHandler = callback;
    };

    this.onUpdate = function (callback) {
        updateHandler = callback;
    };

    this.onRemove = function (callback) {
        removeHandler = callback;
    };

    this.onClear = function (callback) {
        clearHandler = callback;
    };

    this.multiBet = function() {
        return multiBet;
    };

    this.isMultiBetValid = function() {
        if (this.count() < 2)
            return false;
        for (var i=0; i<bets.length; i++)
            if (this.count(bets[i].gameId, 'gameId') > 1)
                return false;
        return true;
    };

    this.restore = function() {
        if (oldBets) {
            bets = [];
            for (var i = 0; i < oldBets.length; i++)
                this.add(oldBets[i]);
        }
    };

    function find(keyValue, fieldName) {
        var fieldName = fieldName?fieldName:"id";
        for (var i = 0; i < bets.length; i++)
            if (bets[i][fieldName] == keyValue)
                return i;
        return -1;
    };

    function doNothing() {}

})();

function Bet(id, name, odds, amount, marketId, marketName, gameId, gameName) {
    this.id = id;
    this.name = name;
    this.odds = odds;
    this.amount = amount;
    this.marketId = marketId;
    this.marketName = marketName;
    this.gameId = gameId;
    this.gameName = gameName;
}
