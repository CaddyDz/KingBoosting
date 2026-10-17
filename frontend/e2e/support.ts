import { expect, type Browser, type Page } from '@playwright/test'

/** The seeded accounts' password (DEMO_PASSWORD) */
export const PASSWORD = process.env.DEMO_PASSWORD ?? 'password'

export async function logIn(page: Page, email: string): Promise<void> {
	await page.goto('/login')
	await page.getByLabel('Email').fill(email)
	await page.getByLabel('Password', { exact: true }).fill(PASSWORD)
	await page.getByTestId('login-submit').click()
	await expect(page).toHaveURL(/\/dashboard\//)
}

/**
 * A signed-in page in its own browser context, so two people can use the app side by side.
 */
export async function personAt(browser: Browser, email: string): Promise<Page> {
	const page = await (await browser.newContext()).newPage()
	await logIn(page, email)

	return page
}

/**
 * GET from the API as the page's signed-in user. From inside the page: Sanctum only treats requests from the
 * app's own origin as signed in.
 */
export async function api<T>(page: Page, path: string): Promise<T> {
	const { status, body } = await page.evaluate(async (url) => {
		const response = await fetch(url, { headers: { Accept: 'application/json' } })

		return { status: response.status, body: await response.json() as unknown }
	}, path)
	expect(status, `GET ${path}`).toBe(200)

	return body as T
}
