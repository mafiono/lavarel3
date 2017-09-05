export default {
    namespaced: true,
    state: {
        promos: [],
        visible: false,
        selected: null,
        loaded: false
    },
    getters: {
        getPromosByType(state) {
            return (type) => state.promos.filter(promo => promo.type === type);
        },
        getPromoById(state, id) {
            return (id) => state.promos.find(promo => promo.id === id);
        }
    },
    mutations: {
        setVisible(state, visible) {
            state.visible = visible;
        },
        setSelected(state, promo) {
             state.selected = promo;
        },
        addPromo(state, promo) {
            state.promos.push(promo);
        },
        setLoaded(state, loaded) {
            state.loaded = loaded;
        }
    },
    actions: {
        fetch({commit}) {
            $.getJSON("/promotions")
                .done(data => {
                    data.forEach(promo => commit('addPromo', promo));
                    commit('setLoaded', true);
                });
        }
    }
}