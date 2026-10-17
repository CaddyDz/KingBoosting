import { request, type FullConfig } from '@playwright/test'

/**
 * Every run starts from the seeded demo. POST /api/testing/reset only exists on a local stack (APP_ENV=local).
 */
export default async function globalSetup(config: FullConfig): Promise<void> {
	const context = await request.newContext({ baseURL: config.projects[0]?.use.baseURL })
	const response = await context.post('/api/testing/reset', { headers: { Accept: 'application/json' }, timeout: 120_000 })
	await context.dispose()

	if (!response.ok()) {
		throw new Error(`Resetting the demo failed (HTTP ${response.status()}). Is the stack up, with APP_ENV=local?`)
	}
}
