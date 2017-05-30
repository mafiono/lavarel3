export default {
    namespaced: true,
    state: {
        view: "",
    },
    getters: {
        getView(state) {
            return state.view;
        }
    },
    mutations: {
        setView(state, view) {
            state.view = view;
        }
    }
}
