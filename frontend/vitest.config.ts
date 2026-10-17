import { defineConfig } from 'vitest/config'
import { defineVitestProject } from '@nuxt/test-utils/config'

export default defineConfig({
	test: {
		projects: [
			// Plain functions and stores, in Node
			{ test: { name: 'unit', include: ['test/unit/**/*.test.ts'], environment: 'node' } },
			// Components and composables that need Nuxt
			await defineVitestProject({ test: { name: 'nuxt', include: ['test/nuxt/**/*.test.ts'], environment: 'nuxt' } }),
		],
	},
})
