import store from './_default';
export default {
    namespaced: true,
    state: {
        ...store.state,
        loading: false,
    },

    getters: {
        ...store.getters,
        loading: state => state.loading,
    },

    mutations: {
        ...store.mutations,
        loading(state, status) {
            state.loading = status;
        }
    },

    actions: {
        ...store.actions,
    }
}
