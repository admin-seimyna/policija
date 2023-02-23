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
            this.store.commit('app/loading', true);
            const auth = this.store.getters['auth/auth'];
            if (!auth) {
                axios.post('/auth').then(() => {
                    this._auth(to, next);
                })
            } else {
                this._auth(to, next);
            }
        });
    }

    _auth(to, next) {
        const user = this.store.getters['auth/user'];
        if (!user) {
            if (!to.meta.public) {
                return next({ name: 'login' });
            }
        } else {
            if (to.meta.public) {
                return next({ name: 'dashboard' });
            }
        }
        if(to.meta && to.meta.payload) {
            axios.get(to.meta.payload).then(() => {
                this.store.commit('app/loading', false);
            });
        } else {
            this.store.commit('app/loading', false);
        }
        return next();
    }

    /**
     * Handle after each router
     * @private
     */
    _afterEach() {
        this.router.afterEach((to, from, next) => {
            //
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
