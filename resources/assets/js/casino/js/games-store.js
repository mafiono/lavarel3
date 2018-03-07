export default {
    categories: {},
    getByType(type) {
        var self = this;
        return new Promise(function (resolve, reject){
            console.log('Selected Cat', type);
            if (self.categories[type] && self.categories[type].list) {
                resolve(self.categories[type].list);
                return;
            }
            $.get('/api/categories/' + type + '/games')
                .then(function (response) {
                    if (!self.categories[type]) {
                        self.categories[type] = {};
                    }
                    self.categories[type].list = response.data;
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