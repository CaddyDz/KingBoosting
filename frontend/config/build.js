import webpack from "webpack";

export default {
	transpile: [/vuetify/],
	extractCSS: {
		ignoreOrder: true
	},
	cssSourceMap: true,
	plugins: [
		// Expose lodash globally
		new webpack.ProvidePlugin({
			// global modules
			_: "lodash"
		})
	],

	/*
	 ** You can extend webpack config here
	 */
	extend(config, ctx) {
		if (ctx.isClient && ctx.isDev) {
			config.devtool = 'source-map'
		}
	},

	loaders: {
		vue: {
			prettify: false
		}
	}
}
