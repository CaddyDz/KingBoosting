import seo from "./config/seo";
import auth from "./config/auth";
import build from "./config/build";
import router from "./config/router";
import plugins from "./config/plugins";

export default {
	vue: {
		config: {
			productionTip: false,
			devtools: true
		}
	},
	// Global page headers (https://nuxtjs.org/docs/2.x/features/meta-tags-seo)
	/*
	 ** Head elements
	 */
	head: seo,

	// Global CSS (https://nuxtjs.org/docs/2.x/configuration-glossary/configuration-css)
	css: [
		'~/static/css/main',
		'~/static/css/stripe',
	],

	// Plugins to run before rendering page (https://nuxtjs.org/docs/2.x/configuration-glossary/configuration-plugins)
	plugins: plugins,

	// Auto import components (https://nuxtjs.org/docs/2.x/configuration-glossary/configuration-components)
	components: {
		dirs: [
			'~/components',
			'~/components/auth',
			'~/components/order',
			'~/components/layout',
			'~/components/order/checkout'
		]
	},

	// Modules for dev and build (recommended) (https://nuxtjs.org/docs/2.x/directory-structure/modules)
	buildModules: [
		// https://github.com/nuxt-community/vuetify-module
		'@nuxtjs/vuetify',
		'@nuxtjs/dotenv',
	],

	// Modules (https://nuxtjs.org/docs/2.x/directory-structure/modules)
	modules: [
		// https://axios.nuxtjs.org/
		'@nuxtjs/axios',
		'@nuxtjs/auth-next',
		"@nuxtjs/sentry",
		'nuxt-stripe-module'
	],

	sentry: {
		dsn: process.env.SENTRY_DSN,
	},

	publicRuntimeConfig: {
		axios: {
			browserBaseURL: process.env.HOST_URL,
		}
	},

	privateRuntimeConfig: {
		axios: {
			baseURL: process.env.HOST_URL,
		}
	},

	axios: {
		proxy: true,
		credentials: true
	},

	proxy: {
		'/laravel': {
			target: 'https://laravel-auth.nuxtjs.app',
			pathRewrite: {
				'^/laravel': '/'
			}
		}
	},

	auth: auth,

	stripe: {
		publishableKey: process.env.STRIPE_KEY,
	},

	// Vuetify module configuration (https://github.com/nuxt-community/vuetify-module#options)
	vuetify: {
		treeShake: true,
		customVariables: ['@/assets/variables.scss'],
		optionsPath: './vuetify.options.js',
		defaultAssets: {
			font: false
		}
	},

	router: router,

	// Build Configuration (https://nuxtjs.org/docs/2.x/configuration-glossary/configuration-build)
	build: build,
}
