// @ts-check
import withNuxt from './.nuxt/eslint.config.mjs'

export default withNuxt({
	rules: {
		// Vuetify components take many props; one per line makes templates hard to read
		'vue/max-attributes-per-line': 'off',
		// Vuetify 4 uses the Material 3 type scale; the Material 2 classes no longer exist and fail silently
		'vue/no-restricted-class': ['error', '/^text-(h[1-6]|subtitle-[12]|body-[12]|caption|overline|button)$/'],
	},
})
