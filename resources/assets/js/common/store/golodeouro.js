export default {
    namespaced: true,
    state: {
        inactives: [],
        active:[],
        visible: false,
        selected: null,
        loaded: false
    },
    getters: {
    },
    mutations: {
        setVisible(state, visible) {
            state.visible = visible;
        },
        setLoaded(state, loaded) {
            state.loaded = loaded;
        }
    },
    actions: {

    }
}