function setValue(state, key, value) {
    state[key] = value;

    if (!value) {
        localStorage.removeItem(key);
        return;
    }

    localStorage.setItem(key, value);
}


function getValue(state, key) {
    if (!state[key]) {
        state[key] = localStorage.getItem(key);
    }
    return state[key];
}


export default {
    namespaced: true,
    state: {
        current: null,
        list: [],
        pagination: {
            total: 0,
            per_page: 20,
            data: [],
        }
    },
    getters: {
        current: state => state.current,
        list: state => state.list,
        pagination: state => state.pagination,
    },
    mutations: {
        current(state, value) {
            state.current = value;
        },
        list(state, value) {
            state.list = value;
        },
        pagination(state, value) {
            state.pagination = value;
        },
        addToList(state, value) {
            state.list.push(value);
        },
        prependToList(state, value) {
            state.list.unshift(value);
        },
        addToPagination(state, value) {
            state.pagination.data.push(value);
        },
        removeFromPagination(state, value) {
            const index = state.pagination.data.findIndex(item => item.id === value.id);
            state.pagination.data.splice(index, 1);
        },
        prependToPagination(state, value) {
            state.pagination.data.unshift(value);
        },
        updateInPagination(state, value) {
            const index = state.pagination.data.findIndex(item => item.id === value.id);
            state.pagination.data[index] = Object.assign(state.pagination.data[index], value);
        }
    },
    actions: {},
    setValue,
    setJsonValue(state, key, value) {
        setValue(state, key, value ? JSON.stringify(value) : value);
    },
    getValue,
    getJsonValue(state, key) {
        const value = getValue(state, key);
        if (typeof value !== 'string') {
            return value;
        }
        return JSON.parse(getValue(state, key));
    }
}
