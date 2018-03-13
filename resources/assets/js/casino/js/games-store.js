export default {
    categories: {},
    games: {},
    getGameById(id) {
        var self = this;
        return new Promise(function (resolve, reject){
            if (self.games.hasOwnProperty(id)) {
                resolve(self.games[id]);
                return;
            }
            $.get('/api/categories/games/' + id)
                .then(function (response) {
                    var game = response.data;
                    self.games[id] = game;
                    resolve(game);
                }, function (x) {
                    var data = JSON.parse(x.responseText);
                    reject(x.responseJSON);
                });
        });
    },
    setList(category, list) {
        this.categories[category].list = list;
        //list.forEach((cat, game) => {
        //    this.games[game.id] = game;
        //    this.categories[category].list.push(game);
        //});
    },
    getByType(type, device) {
        var self = this;
        return new Promise(function (resolve, reject) {
            if (self.categories[type] && self.categories[type].list) {
                resolve(self.categories[type].list);
                return;
            }
            if (isMobile.any * 1) {
                device = 'mobile'
            } else {
                device = 'desktop'
            }
            $.get('/api/categories/' + type + '/games?device=' + device)
                .then(function (response) {
                    if (!self.categories[type]) {
                        self.categories[type] = {};
                    }
                    self.setList(type, response.data);
                    resolve(response.data);
                }, function (x) {
                    var data = JSON.parse(x.responseText);
                    reject(x.responseJSON);
                });
        });
    },
    fetchCategory() {
        var self = this;
        return new Promise(function (resolve, reject) {
            $.get('/api/categories')
                .then(function (response) {
                    response.data.forEach(function (currentValue, index, arr) {
                        self.categories[currentValue.categoryId] = currentValue;
                    });
                    resolve(response.data);
                }, function (x) {
                    var data = JSON.parse(x.responseText);
                    reject(x.responseJSON);
                })
            ;
        });
    },
    init() {
        console.log('Init Called on Store');
    }
};