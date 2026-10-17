import { beforeEach, describe, expect, it } from 'vitest'
import { registerEndpoint } from '@nuxt/test-utils/runtime'
import { requestOf, respond, type MockEvent } from './endpoints'
import type { CatalogEntry } from '~/types/api'

const unrated: CatalogEntry = {
	key: 'unrated-matches',
	game: { value: 'valorant', label: 'Valorant' },
	label: 'Unrated matches',
	strategy: 'unrated',
	ranks: null,
	lp: null,
	mmr: null,
	amount: { label: 'games', min: 1, max: 30, default: 5 },
	mastery: null,
	champions: null,
	servers: [{ key: 'eu', label: 'Europe' }, { key: 'na', label: 'North America' }],
	options: [{ key: 'net-wins', label: 'Net wins', description: '', percent: 40 }],
	defaults: { amount: 5 },
}

const wins: CatalogEntry = {
	...unrated,
	key: 'ranked-wins-boosting',
	strategy: 'per-win',
	ranks: [{ key: 'gold', name: 'Gold', divisions: ['IV', 'III', 'II', 'I'] }],
	amount: { label: 'wins', min: 1, max: 10, default: 4 },
	defaults: { current: { tier: 'gold', division: 'IV' }, amount: 4 },
}

// A quote the way POST /api/quotes answers: one euro per game, so the total shows which request it answers
function quoteFor(amount: number) {
	return { data: { title: `${amount} unrated matches`, lines: [], options: [], total_cents: amount * 100 } }
}

const sleep = (ms: number) => new Promise(resolve => setTimeout(resolve, ms))

describe('the order store', () => {
	beforeEach(() => {
		useOrderStore().clear()
		sessionStorage.clear()
		// Quotes are POSTs: with a CSRF cookie in place, they go straight out
		document.cookie = 'XSRF-TOKEN=test; path=/'
	})

	it('starts from the catalog defaults and keeps the choices when the same service opens again', () => {
		const order = useOrderStore()
		order.start(unrated)
		expect(order.state?.amount).toBe(5)
		expect(order.server).toBe('eu')
		expect(order.input).toEqual({ amount: 5, options: [] })

		order.state!.amount = 12
		order.start(unrated)
		expect(order.state?.amount).toBe(12)

		order.start(wins)
		expect(order.input).toEqual({ current: { tier: 'gold', division: 'IV' }, amount: 4, options: [] })
	})

	it('asks for one quote once the choices settle', async () => {
		const bodies: unknown[] = []
		registerEndpoint('/api/quotes', {
			method: 'POST',
			handler: async (event: MockEvent) => {
				const body = await requestOf(event).json() as { amount: number }
				bodies.push(body)

				return quoteFor(body.amount)
			},
		})
		const order = useOrderStore()
		order.start(unrated)

		for (const amount of [6, 7, 8]) {
			order.state!.amount = amount
			order.requestQuote(30)
		}
		await sleep(150)

		expect(bodies).toEqual([{ service: 'unrated-matches', amount: 8, options: [] }])
		expect(order.quote?.total_cents).toBe(800)
		expect(order.canCheckout).toBe(true)
	})

	it('never lets a slow answer to an older request overwrite a newer price', async () => {
		registerEndpoint('/api/quotes', {
			method: 'POST',
			handler: async (event: MockEvent) => {
				const { amount } = await requestOf(event).json() as { amount: number }
				// The first request (1 game) answers last
				await sleep(amount === 1 ? 150 : 0)

				return quoteFor(amount)
			},
		})
		const order = useOrderStore()
		order.start(unrated)

		order.state!.amount = 1
		order.requestQuote(0)
		await sleep(30)
		order.state!.amount = 2
		order.requestQuote(0)
		await sleep(300)

		expect(order.quote?.total_cents).toBe(200)
		expect(order.quoting).toBe(false)
	})

	it('shows the API\'s reason when an order can\'t be priced, and blocks checkout', async () => {
		registerEndpoint('/api/quotes', {
			method: 'POST',
			handler: (event: MockEvent) => respond(event, 422, {
				message: 'The desired rank has to be above the current one.',
				errors: { desired: ['The desired rank has to be above the current one.'] },
			}),
		})
		const order = useOrderStore()
		order.start(unrated)
		order.requestQuote(0)
		await sleep(100)

		expect(order.error).toBe('The desired rank has to be above the current one.')
		expect(order.quote).toBeNull()
		expect(order.canCheckout).toBe(false)
	})

	it('brings the order back after a reload, from this tab\'s session storage', async () => {
		registerEndpoint('/api/catalog/unrated-matches', () => ({ data: unrated }))
		const order = useOrderStore()
		order.start(unrated)
		order.state!.amount = 17
		order.server = 'na'
		await nextTick()
		const saved = sessionStorage.getItem('kingboosting.order')

		// What a reload leaves: an empty store and the saved choices
		order.clear()
		await nextTick()
		sessionStorage.setItem('kingboosting.order', saved!)

		expect(await order.restore()).toBe(true)
		expect(order.entry?.key).toBe('unrated-matches')
		expect(order.state?.amount).toBe(17)
		expect(order.server).toBe('na')
	})

	it('has nothing to restore in a fresh tab', async () => {
		expect(await useOrderStore().restore()).toBe(false)
	})
})
