export default {
    categories: {},
    getByType(type) {
        var self = this;
        return new Promise(function (resolve, reject){
            if (self.categories[type]) {
                resolve(self.categories[type]);
                return;
            }
            $.get('/api/categories/' + type + '/games')
                .then(function (response) {
                    self.categories[type] = response.data;
                    resolve(response.data);
                }, function (x) {
                    var data = JSON.parse(x.responseText);
                    console.log('Error is ', x);
                    reject(x.responseJSON);
                })
                ;
        });
    },
    init() {
        console.log('Init Called on Store');
    }
};