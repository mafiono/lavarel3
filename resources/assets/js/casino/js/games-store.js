export default {
    categories: {},
    games: {},
    featured: [],
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
    getByType(type) {
        var self = this;
        return new Promise(function (resolve, reject){
            if (self.categories[type] && self.categories[type].list) {
                resolve(self.categories[type].list);
                return;
            }
            $.get('/api/categories/' + type + '/games')
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

    getFeaturedGames() {
        var self = this;
        return new Promise(function (resolve, reject) {
            $.get('/api/categories/games/side')
                .then(function (response) {
                    response.data.forEach(featured => self.featured.push(featured));
                    resolve(self.featured);
                }, function (x) {
                    var data = JSON.parse(x.responseText);
                    reject(x.responseJSON);
                    return;
                });
        });
    },
   
    init() {
       
     
    }
};