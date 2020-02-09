/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import '@mdi/font/css/materialdesignicons.css'
import Vuetify from 'vuetify';
import Vuex from 'vuex'
Vue.use(Vuex)
Vue.use(Vuetify);
Vue.config.productionTip = false;

const store = new Vuex.Store({
    state: {
        price: 1.9,
        currentlySelectedTier: {
            wins: []
        },
        currentlySelectedDivision: {},
        currentlySelectedNumberOfWins: 1,
        maximumNumberOfWins: 0,
        eta: '',
    },
    mutations: {
        updatePrice(state, newPrice) {
            state.price = newPrice;
        },
        updateCurrentlySelectedTier(state, payload) {
            state.currentlySelectedTier = payload;
        },
        updateCurrentlySelectedDivision(state, payload) {
            state.currentlySelectedDivision = payload;
        },
        updateMaximumNumberOfWins(state, payload) {
            state.maximumNumberOfWins = payload;
        },
        updateETA(payload) {
            state.eta = payload;
        }
    },
    actions: {
        increment(context) {
            context.commit('increment')
        }
    }
})
window.store = store;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: '#root',
    // provide the store using the "store" option.
    // this will inject the store instance to all child components.
    store,
    vuetify: new Vuetify({
        theme: {
            themes: {
                dark: {
                    primary: '#673ab7',
                    secondary: '#9c27b0',
                    accent: '#673ab7',
                    error: '#f44336',
                    warning: '#ffeb3b',
                    info: '#3f51b5',
                    success: '#4caf50'
                },
            },
        },
    }),

    created() {
        this.$vuetify.theme.dark = true
    }
});

window.app = app;
