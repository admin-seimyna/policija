import {createRouter, createWebHistory} from 'vue-router';
import axios from 'axios';

export default class Router {
    router; // Vue router
    store; // Vuex store
    bus; // App Bus

    /**
     * Constructor
     * @param routes
     * @param store
     * @param bus
     */
    constructor(routes = [], store, bus) {
        this.store = store;
        this.bus = bus;
        this.router = createRouter({
            history: createWebHistory(),
            routes
        });

        this._beforeEach();
        this._afterEach();
    }

    /**
     * Handle before each router
     * @private
     */
    _beforeEach() {
        this.router.beforeEach((to, from, next) => {
            this.bus.emit('modal:closeAll');
            if(to.meta && to.meta.payload) {
                axios.get(to.meta.payload).then(() => {
                    //todo: do something
                });
            }
            return next();
        });
    }

    /**
     * Handle after each router
     * @private
     */
    _afterEach() {
        this.router.afterEach((to, from, next) => {
            this.store.commit('app/loading', false);
        });
    }

    /**
     * Get vue router
     * @return {*}
     */
    getRouter() {
        return this.router;
    }
}
