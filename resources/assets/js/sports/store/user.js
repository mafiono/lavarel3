export default {
    namespaced: true,
    state: {
        username: "",
        authenticated: false,
        balance: "",
        userLoginTime: "",
        serverTime: "",
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
        }
    },
    mutations: {
        setAuthenticated(state, authenticated) {
            state.authenticated = authenticated;
        },
        setBalance(state, balance) {
            state.balance = balance;
        },
        setUsername(state, username) {
            state.username = username;
        }
    }
}
