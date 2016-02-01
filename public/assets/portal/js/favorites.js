var Favorites = new (function () {
    var games = Cookies.getJSON("favorites") || [];
    var page = 0;
    var pageStep = 3;
    var doNothing = function(){};
    var onChangeHandler = doNothing;
    var onPageChangeHandler = doNothing;

    $(window).unload(function() {
        Cookies.set("favorites", games);
    });

    this.remove = function (gameId) {
        var index = find(gameId);
        if (index>=0) {
            games.splice(index,1);
            onChangeHandler(games);
        }
    };

    this.toggleGame = function (id, name, date, sport) {
        if (find(id)>=0) {
            this.remove(id);
        } else {
            games.push({"id": id, "name": name, "date": date, "sport" :sport});
            onChangeHandler(games);
        }
    };

    this.games = function() {
        return games;
    };

    this.has = function (gameId) {
        return games[gameId];
    };

    this.onChange = function (callback) {
        onChangeHandler = callback;
    };

    this.count = function() {
        return games.length;
    };

    this.page = function(pageIndex) {
        if (pageIndex != null) {
            page = pageIndex;
            onPageChangeHandler(page);
        } else {
            var lastPage = Math.floor((this.count()-1)/this.pageStep());
            if (page>lastPage) {
                page = lastPage;
                onPageChangeHandler(page);
            }
            return page;
        }
    };

    this.hasPrevPage = function() {
        return (page>0);
    };

    this.hasNextPage = function() {
        return page<Math.floor((this.count()-1)/this.pageStep());
    };

    this.pageStep = function(step) {
        if (step != null)
            pageStep = step;
        else
            return pageStep;
    };

    this.onPageChange = function(callback) {
        onPageChangeHandler = callback;
    };

    function find(id) {
        for (var i in games)
            if (games[i]["id"]==id)
                return i;
        return -1;
    }
})();
