import Vue from 'vue';

// Search the components directory for Vue files
// const files = require.context('../components', true, /\.vue$/i)
// Register those components globally
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Register global asset function to retreive assets from API
// Vue.prototype.$asset = file => `${process.env.STORAGE_URL}/${file}`;
Vue.prototype.$notify = (message, type) => this.$nuxt.$store.commit('notification/open', {
	text: message,
	mode: type
});
