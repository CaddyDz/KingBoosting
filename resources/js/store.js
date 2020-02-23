import Vuex from 'vuex';
import Vue from 'vue';
Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        user: null,
        price: 1.9,
        currentlySelectedTier: {
            wins: [],
        },
        currentlySelectedDivision: {},
        currentlySelectedNumberOfWins: 1,
        maximumNumberOfWins: undefined,
        eta: '',
        isNotificationOpen: false,
        notificationText: "",
        notificationMode: 'info',
    },
    getters: {
        isLoggedIn(state) {
            return state.user !== null;
        }
    },
    mutations: {
        setAuthUser(state, user) {
            state.user = user;
        },
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
        },
        openNotification(state, payload) {
            state.isNotificationOpen = true;
            state.notificationText = payload.text;
            state.notificationMode = payload.mode;
        },
        closeNotification(state) {
            state.isNotificationOpen = false;
        }
    }
});

window.store = store;
