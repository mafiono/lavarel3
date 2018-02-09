export default {
    promos: [],
    visible: false,
    selected: null,
    loaded: false,
    getPromosByType(type) {
        return this.promos.filter(promo => promo.type === type);
    },
    getPromoById(id) {
        return this.promos.find(promo => promo.id === id);
    },
    fetch: function() {
        $.getJSON("/promotions")
            .done(data => {
                data.forEach(promo => this.promos.push(promo));

                this.loaded = true;
        });
    }
}