import store from './_default';
export default {
    namespaced: true,
    state: {
        loading: true,
    },

    getters: {
        loading: state => state.loading,
    },

    mutations: {
        loading(state, status) {
            state.loading = status;
        }
    },

    actions: {
    }
}
