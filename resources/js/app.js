import { createApp } from 'vue';
import App from './Components/App';
import Application from './application';
import routes from './routes';
import config from './config.json';
import constants from './constants.json';
import translations from './i18n.json';

createApp(App)
    .use(Application, {
        routes,
        name: 'app',
        config,
        constants,
        http: {
            host: 'http://ataskaita.pvv.lt/', // live
            // host: 'http://policija.test', // home
            // host: 'http://test.test', // work
        },
        moment: {
            timezone: 'Europe/Vilnius'
        },
        i18n: {
            locale: 'lt',
            legacy: false,
            globalInjection: true,
            translations,
        },
        formatter: {
            maximumSignificantDigits: 2,
            currencyDisplay: 'symbol',
            dateFormat: 'YYYY.MM.DD',
            dateTimeFormat: 'YYYY.MM.DD HH:mm',
        },
        permission: {
            adminRoleName: 'owner',
            userRoleName: 'user',
        }
    })
    .mount('#app');
