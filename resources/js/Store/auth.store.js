export default {
    namespaced: true,
    state: {
        auth: false,
        user: null,
    },

    getters: {
        user: state => state.user,
        auth: state => state.auth,
    },

    mutations: {
        user(state, user) {
            state.user = user;
        },
        auth(state, auth) {
            state.auth = auth;
        },
    },

    actions: {
    }
}
