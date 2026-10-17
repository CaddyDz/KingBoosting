import { expect, test } from '@playwright/test'

// On a local stack without Stripe keys, payments run offline and the API stands in for Stripe's webhook
test('a guest builds an order, signs in at checkout, pays and lands on the order', async ({ page }) => {
	await page.goto('/unrated-matches')
	await expect(page.getByTestId('quote-total')).toHaveText('€19.00')
	await page.getByTestId('amount').locator('input').fill('15')
	await page.getByTestId('option-net-wins').locator('input').check()
	// The golden quote for 15 unrated matches with net wins
	await expect(page.getByTestId('quote-total')).toHaveText('€71.40')

	await page.getByTestId('checkout').click()
	await expect(page).toHaveURL('/checkout')
	await expect(page.getByTestId('checkout-total')).toHaveText('€71.40')
	await expect(page.getByTestId('place-order')).toBeDisabled()

	await page.getByLabel('Email').fill('customer@kingboosting.test')
	await page.getByLabel('Password', { exact: true }).fill(process.env.DEMO_PASSWORD ?? 'password')
	await page.getByTestId('login-submit').click()
	await expect(page.getByTestId('signed-in')).toContainText('customer')

	await page.getByTestId('account-name').locator('input').fill('E2E Buyer')
	await page.getByTestId('place-order').click()
	await expect(page).toHaveURL(/\/checkout\/\d+$/)
	const id = page.url().split('/').pop()

	await page.getByTestId('simulate-payment').click()
	await expect(page).toHaveURL(`/dashboard/orders/${id}`)
	await expect(page.locator('.v-snackbar__content')).toContainText('Payment received')
	await expect(page.getByTestId('order-status')).toHaveText('Waiting for a booster')
	await expect(page.getByTestId('order-total')).toContainText('€71.40')
})
