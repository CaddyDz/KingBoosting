import { expect, test } from '@playwright/test'
import { logIn, personAt } from './support'

test('a booster claims an order from the jobs board, sees the login and completes it', async ({ page, browser }) => {
	await logIn(page, 'booster@kingboosting.test')
	await page.goto('/dashboard/jobs')
	const job = page.getByTestId('job').first()
	const title = (await job.locator('.v-card-item__content .v-card-title').textContent())!.trim()

	await job.getByTestId('claim').click()
	await expect(page).toHaveURL(/\/dashboard\/orders\/\d+$/)
	const id = page.url().split('/').pop()
	await expect(page.locator('.v-snackbar__content')).toContainText('The order is yours')
	await expect(page.getByTestId('order-status')).toHaveText('In progress')
	await expect(page.getByTestId('credentials')).toBeVisible()

	// Gone from the board for everyone else
	const other = await personAt(browser, 'nightowl@kingboosting.test')
	await other.goto('/dashboard/jobs')
	await expect(other.getByTestId('job').first()).toBeVisible()
	await expect(other.locator(`a[href="/dashboard/orders/${id}"]`)).toHaveCount(0)

	await page.getByTestId('action-complete').click()
	await page.getByTestId('confirm').click()
	await expect(page.getByTestId('order-status')).toHaveText('Completed')
	await expect(page.getByText(title).first()).toBeVisible()
})
