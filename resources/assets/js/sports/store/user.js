export default {
    namespaced: true,
    state: {
        username: "",
        authenticated: false,
        balance: "",
        totalBalance: "",
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
        getTotalBalance(state) {
            return state.totalBalance;
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
        setTotalBalance(state, totalBalance) {
            state.totalBalance = totalBalance;
        },
        setUnreads(state, unreads) {
            state.unreads = unreads;
        },
        setUsername(state, username) {
            state.username = username;
        }
    }
}
