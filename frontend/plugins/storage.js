import Vue from 'vue';

Vue.prototype.$asset = file => `${process.env.STORAGE_URL}/${file}`;
