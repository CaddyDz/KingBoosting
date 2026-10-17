// @ts-check
import withNuxt from './.nuxt/eslint.config.mjs'

export default withNuxt({
	rules: {
		// Vuetify components take many props; one per line makes templates hard to read
		'vue/max-attributes-per-line': 'off',
	},
})
