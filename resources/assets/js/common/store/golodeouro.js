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
        getactive(state) {
            return state.active[0];
        },
        getinactive(state){
            return state.inactives;
        }
    },
    mutations: {
        setVisible(state, visible) {
            state.visible = visible;
        },
        addGoals(state, goals) {
            state.inactives.push(goals);
        },
        addGoal(state, goal) {
            state.active.push(goal);
        },
        setLoaded(state, loaded) {
            state.loaded = loaded;
        }
    },
    actions: {
        fetchinactive({commit}) {
            $.getJSON("/inactivegoals")
                .done(data => {
                    data.forEach(goal => commit('addGoals', goal));
                    commit('setLoaded', true);
                });
        },
        fetchactive({commit}) {
            $.getJSON("/activegoals")
                .done(data => {
                data.forEach(goal => commit('addGoal', goal));
            commit('setLoaded', true);
        });
        }
    }
}