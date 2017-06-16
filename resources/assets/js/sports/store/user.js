export default {
    namespaced: true,
    state: {
        username: "",
        authenticated: false,
        balance: "",
        bonus: "",
        userLoginTime: "",
        serverTime: "",
        unreads: "",
    },
    getters: {
        getUsername(state) {
            return state.username;
        },
        isAuthenticated(state) {
            return state.authenticated;
        },
        getBalance(state) {
            return state.balance;
        },
        getBonus(state) {
            return state.bonus;
        },
        getUnreads(state) {
            return state.unreads;
        }
    },
    mutations: {
        setAuthenticated(state, authenticated) {
            state.authenticated = authenticated;
        },
        setBalance(state, balance) {
            state.balance = balance;
        },
        setBonus(state, bonus) {
            state.bonus = bonus;
        },
        setUnreads(state, unreads) {
            state.unreads = unreads;
        },
        setUsername(state, username) {
            state.username = username;
        }
    }
}
