// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
	modules: ['@nuxt/eslint', '@pinia/nuxt', '@nuxt/test-utils/module', 'vuetify-nuxt-module'],

	$development: {
		// Nitro keeps cached pages on disk across restarts, so in development they'd hide code changes
		routeRules: { '/**': { swr: false } },
	},

	devtools: { enabled: true },

	app: {
		head: {
			titleTemplate: '%s · King Boosting',
			htmlAttrs: { lang: 'en' },
			meta: [
				{ name: 'description', content: 'King Boosting: a portfolio demo of a multi-role order marketplace. Test payments only.' },
				// It's a demo store: keep it out of search results
				{ name: 'robots', content: 'noindex' },
			],
			link: [{ rel: 'icon', type: 'image/svg+xml', href: '/favicon.svg' }],
		},
	},

	css: ['~/assets/css/main.css'],

	// Read at runtime from NUXT_* variables, so one image runs anywhere (the legacy app baked them in at build time)
	runtimeConfig: {
		// Where the Nuxt server reaches Laravel during server-side rendering (the browser uses /api on the same origin)
		apiBase: 'http://web:8080',
		public: {
			stripeKey: '',
			reverb: {
				key: '',
				// Empty means the page's own host and port: Reverb is on the same origin (docs/adr/0001)
				host: '',
				port: 0,
				scheme: '',
			},
		},
	},

	routeRules: {
		// The storefront is the same for everyone, so its HTML can be cached and refreshed in the background
		'/**': { swr: 600 },
		// Signed-in pages render in the browser only, so server-side rendering never handles a session cookie
		'/dashboard/**': { ssr: false, swr: false },
		'/checkout/**': { ssr: false, swr: false },
		'/login': { ssr: false, swr: false },
		'/register': { ssr: false, swr: false },
		'/reset-password': { ssr: false, swr: false },
		'/forgot-password': { ssr: false, swr: false },
		// The legacy footer's link
		'/terms-of-use': { redirect: { to: '/terms', statusCode: 301 } },
	},

	compatibilityDate: '2026-10-01',

	vite: {
		server: {
			// The dev proxy serves Nuxt on :8000, so hot reload connects there too
			ws: { clientPort: Number(process.env.APP_PORT ?? 8000) },
		},
	},

	typescript: {
		strict: true,
	},

	eslint: {
		config: {
			stylistic: {
				indent: 'tab',
				quotes: 'single',
				semi: false,
			},
		},
	},

	vuetify: {
		moduleOptions: {
			styles: { configFile: 'assets/css/vuetify-settings.scss' },
			// Vuetify's useLayout collides with Nuxt's: its composables are auto-imported as useVDisplay, useVTheme...
			prefixComposables: true,
		},
		vuetifyOptions: {
			icons: { defaultSet: 'mdi-svg' },
			theme: {
				defaultTheme: 'dark',
				themes: {
					dark: {
						dark: true,
						colors: {
							'primary': '#8f5bd6',
							'secondary': '#472783',
							'background': '#120c1c',
							'surface': '#1c1429',
							'surface-variant': '#2a1f3b',
							'error': '#ef5c6c',
							'success': '#4cc38a',
							'warning': '#f0a43a',
							'info': '#5aa9f0',
							// Chart marks: one hue, checked for contrast and lightness against the surface
							'chart': '#8f5bd6',
						},
					},
					light: {
						dark: false,
						colors: {
							'primary': '#472783',
							'secondary': '#8f5bd6',
							'background': '#f6f3fb',
							'surface': '#ffffff',
							'surface-variant': '#ece6f6',
							'error': '#c62f42',
							'success': '#1f8a5b',
							'warning': '#b8650b',
							'info': '#1f6fbf',
							'chart': '#6a3fb5',
						},
					},
				},
			},
			defaults: {
				VBtn: { rounded: 'lg' },
				VCard: { rounded: 'lg' },
				VTextField: { variant: 'outlined', density: 'comfortable' },
				VSelect: { variant: 'outlined', density: 'comfortable' },
				VAutocomplete: { variant: 'outlined', density: 'comfortable' },
				VTextarea: { variant: 'outlined', density: 'comfortable' },
			},
		},
	},
})
