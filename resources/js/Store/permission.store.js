import store from './_default';
export default {
    namespaced: true,
    state: {
        ...store.state,
        actions: [],
    },

    getters: {
        ...store.getters,
        actions: state => state.actions,
    },

    mutations: {
        ...store.mutations,
        actions(state, actions) {
            state.actions = actions;
        }
    },

    actions: {
        ...store.actions,
    }
}
