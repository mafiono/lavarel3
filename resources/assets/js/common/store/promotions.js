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
    addPromo(promo) {
        this.promos.push(promo);
        Vue.component('promotion-body-' + promo.id, {
            template: `<div>${promo.body}</div>`,
        });
    },
    fetch: function() {
        $.getJSON("/promotions")
            .done(data => {
                data.forEach(promo => this.addPromo(promo));

                this.loaded = true;
        });
    }
}