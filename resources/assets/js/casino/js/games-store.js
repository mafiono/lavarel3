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
    getByType2(type) {
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
    getByType(type) {
        if (this.categories[type] && this.categories[type].list) {
            return this.categories[type].list;
        }
        return this.getHttp('/api/categories/' + type + '/games')
            .then(data => {
                if (!this.categories[type]) {
                    this.categories[type] = {};
                }
                return this.setList(type, data);
            });
    },

    fetchCategory() {
        return this.getHttp('/api/categories')
            .then(data => {
                data.forEach(currentValue => this.categories[currentValue.categoryId] = currentValue)
                    return this.categories;
                });
    },

    getFeaturedGames() {
        return this.getHttp('/api/categories/games/side')
                .then(data => {
                    data.forEach(featured => this.featured.push(featured));
                    return this.featured;
                });
    },
    getHttp(url) {
        return new Promise(function (resolve, reject) {
            $.get(url)
                .then(function (response) {
                    resolve(response.data);
                }, function (x) {
                    // var data = JSON.parse(x.responseText);
                    reject(x.responseJSON);
                });
        });
    },
    init() {
       
     
    }
};