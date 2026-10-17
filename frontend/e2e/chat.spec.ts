import { expect, test } from '@playwright/test'
import { api, personAt } from './support'
import type { Order, Paginated } from '../app/types/api'

test('a customer and their booster chat live, with read receipts', async ({ browser }) => {
	const customer = await personAt(browser, 'customer@kingboosting.test')
	const booster = await personAt(browser, 'booster@kingboosting.test')

	// An order the two seeded accounts share, in progress
	const orders = await api<Paginated<Order>>(customer, '/api/orders?status=in_progress&per_page=50')
	const order = orders.data.find(candidate => candidate.booster?.username === 'booster')
	expect(order, 'a seeded in-progress order between customer and booster').toBeDefined()

	await customer.goto(`/dashboard/orders/${order!.id}`)
	await booster.goto(`/dashboard/orders/${order!.id}`)
	await expect(customer.getByTestId('chat')).toBeVisible()
	await expect(booster.getByTestId('chat')).toBeVisible()
	// Both pages listen on the order's channel before anyone writes
	await expect(customer.getByTestId('chat')).toHaveAttribute('data-live', 'true', { timeout: 30_000 })
	await expect(booster.getByTestId('chat')).toHaveAttribute('data-live', 'true', { timeout: 30_000 })

	const question = `Can you play tonight? (${Date.now()})`
	await customer.getByTestId('chat-input').locator('textarea').first().fill(question)
	await customer.getByTestId('chat-send').click()
	await expect(booster.getByTestId('chat-message').filter({ hasText: question })).toBeVisible()

	const answer = `Yes, from 8pm. (${Date.now()})`
	await booster.getByTestId('chat-input').locator('textarea').first().fill(answer)
	await booster.getByTestId('chat-send').click()
	await expect(customer.getByTestId('chat-message').filter({ hasText: answer })).toBeVisible()

	// Each side's message shows once, and the customer sees that the booster read theirs
	await expect(customer.getByTestId('chat-message').filter({ hasText: question })).toHaveCount(1)
	await expect(customer.getByTestId('chat-messages')).toContainText('Seen')
})
