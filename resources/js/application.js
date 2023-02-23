import Router from '@/Utilities/Router';
import Http from '@/Utilities/Http';
import Store from '@/Utilities/Store';
import Locale from '@/Utilities/Locale';
import Formatter from '@/Utilities/Formatter';
import Config from '@/Utilities/Config';
import Constants from '@/Utilities/Constants';
import Bus from '@/Utilities/Bus';
import 'moment/locale/lt';
import moment from 'moment';
import Permission from '@/Utilities/Permission';
require('moment-timezone');

class App
{
    app; // Application
    options; // App options
    router; // Router
    http; // Http request handler
    lang; // i18b
    config; // Application config
    constant; // Application constants
    bus; // Event listener
    permission; // Permissions

    constructor(app, options) {
        this.app = app;
        this.setup(options);
    }

    /**
     * Setup application
     * @param options
     */
    setup(options) {
        this.options = Object.assign({
            routes: [],
            http: {},
            i18n: {},
            formatter: {},
            config: {},
            constants: {},
            moment: {},
        }, options);

        const store = (new Store()).getStore();
        const i18n = (new Locale(this.options.i18n)).getI18n();


        moment.tz.setDefault(this.options.moment.timezone);
        moment.locale(this.options.i18n.locale);

        this.bus = new Bus();
        this.formatter = new Formatter(this.options.i18n.locale, this.options.formatter);
        this.http = new Http(this.options.http, store);
        this.config = new Config(this.options.config);
        this.constant = new Constants(this.options.constants, i18n.global.t);
        this.permission = new Permission(this.options.permission, store);
        this.app.use((new Router(this.options.routes, store, this.bus)).getRouter());
        this.app.use(store);
        this.app.use(i18n);
    }

    /**
     * Register custom object to main application
     * @param name
     * @param object
     */
    register(name, object) {
        return new Promise((resolve, reject) => {
            this[name] = object;
            resolve(this.options);
        });
    }
}

export default {
    install(app, options) {
        const application = new App(app, options);
        app.config.globalProperties.$app = application;
        app.provide(options.name, application);
    }
}
