import store from './_default';
export default {
    namespaced: true,
    state: {
        ...store.state,
        statistic: [
            {
                name: 'events_count',
                count: 0
            },
            {
                name: 'processed_events_count',
                count: 0
            },
            {
                name: 'an_count',
                count: 0
            },
            {
                name: 'traffic_events_count',
                count: 0
            },
            {
                name: 'unprocessed_events_count',
                count: 0
            },
            {
                name: 'total',
                count: 0
            }
        ],
    },

    getters: {
        ...store.getters,
        statistic: state => state.statistic,
    },

    mutations: {
        ...store.mutations,
        statistic(state, data) {
            Object.keys(data).forEach((key) => {
                const index = state.statistic.findIndex(stat => stat.name === key)
                state.statistic[index].count = data[key];
            });
        }
    },

    actions: {
        ...store.actions,
    }
}
