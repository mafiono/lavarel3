export default {
    namespaced: true,
    state: {
        view: "",
        isMobile: false,
    },
    getters: {
        getView(state) {
            return state.view;
        },
        getIsMobile(state) {
            return state.isMobile;
        }
    },
    mutations: {
        setView(state, view) {
            state.view = view;
        },
        setIsMobile(state, isMobile) {
            state.isMobile = isMobile;
        }
    },
}
