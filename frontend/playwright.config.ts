import { defineConfig, devices } from '@playwright/test'

/**
 * End-to-end tests against the running compose stack and its seeded demo:
 * `docker compose --profile e2e run --rm e2e`. One worker, because the flows share the demo's data.
 */
export default defineConfig({
	testDir: './e2e',
	globalSetup: './e2e/global-setup.ts',
	workers: 1,
	retries: process.env.CI ? 1 : 0,
	reporter: process.env.CI ? [['list'], ['html', { open: 'never', outputFolder: 'playwright-report' }]] : 'list',
	timeout: 60_000,
	expect: { timeout: 15_000 },
	use: {
		baseURL: process.env.E2E_BASE_URL ?? 'http://localhost:8000',
		trace: 'retain-on-failure',
	},
	projects: [
		{ name: 'chromium', use: { ...devices['Desktop Chrome'], viewport: { width: 1360, height: 900 } } },
	],
})
