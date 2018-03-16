export default {
    categories: {},
    games: {},
    featured: [],

    getGameById(id) {
        let self = this;
        return new Promise(function (resolve, reject) {
            if (self.games.hasOwnProperty(id)) {
                resolve(self.games[id]);
                return;
            }
            return self.getHttp('/api/categories/games/' + id)
                .then(game => resolve(self.games[id] = game));
        });
    },

    setList(category, list) {
        if (!this.categories[category]) {
            this.categories[category] = {};
        }
        this.categories[category].list = list;
        return list;
    },

    getByType(type) {
        let self = this;
        return new Promise(function (resolve, reject) {
            if (self.categories[type] && self.categories[type].list) {
                return resolve(self.categories[type].list);
            }
            return self.getHttp('/api/categories/' + type + '/games')
                .then(data => resolve(self.setList(type, data)));
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
                .then(response => resolve(response.data), x => {
                    //var data = JSON.parse(x.responseText);
                    reject(x.responseJSON);
                });
        });
    },
    init() {


    }
};