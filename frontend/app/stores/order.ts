import { defineStore } from 'pinia'
import { errorMessage, statusOf, validationErrors } from '~/utils/api'
import { initialState, quoteInput, type BuilderState } from '~/utils/orderBuilder'
import type { CatalogEntry, Quote, Wrapped } from '~/types/api'

/**
 * The order being built: the service, the builder's choices, the server and the latest quote. It outlives the
 * builder page, so checkout can read it and coming back to the page keeps the choices.
 */
export const useOrderStore = defineStore('order', () => {
	const entry = ref<CatalogEntry | null>(null)
	const state = ref<BuilderState | null>(null)
	const server = ref<string | null>(null)
	const quote = ref<Quote | null>(null)
	const quoting = ref(false)
	const error = ref<string | null>(null)

	/**
	 * Open the builder for a service, keeping the choices if it's the one already in progress.
	 */
	function start(next: CatalogEntry): void {
		const resuming = entry.value?.key === next.key && state.value !== null
		entry.value = next
		if (resuming) {
			return
		}
		state.value = initialState(next)
		server.value = next.servers[0]?.key ?? null
		quote.value = null
		error.value = null
	}

	const input = computed(() => (entry.value && state.value ? quoteInput(entry.value, state.value) : null))

	// The choices survive a reload of checkout: kept in this tab's session storage, which may be unavailable
	const STORAGE_KEY = 'kingboosting.order'
	watch([entry, state, server], () => {
		if (!import.meta.client) {
			return
		}
		try {
			if (entry.value && state.value) {
				sessionStorage.setItem(STORAGE_KEY, JSON.stringify({ service: entry.value.key, state: state.value, server: server.value }))
			}
			else {
				sessionStorage.removeItem(STORAGE_KEY)
			}
		}
		catch {
			// Private windows can refuse storage; the order then lasts as long as the page
		}
	}, { deep: true })

	/**
	 * Bring back the order this tab was building after a reload. False when there's none.
	 */
	async function restore(): Promise<boolean> {
		if (entry.value && state.value) {
			return true
		}
		let saved: { service: string, state: BuilderState, server: string | null } | null
		try {
			saved = JSON.parse(sessionStorage.getItem(STORAGE_KEY) ?? 'null')
		}
		catch {
			return false
		}
		if (!saved) {
			return false
		}
		try {
			entry.value = (await useApi()<Wrapped<CatalogEntry>>(`/api/catalog/${saved.service}`)).data
		}
		catch {
			return false
		}
		state.value = saved.state
		server.value = saved.server

		return true
	}

	/**
	 * Forget the order once it's placed: the next visit to the builder starts fresh.
	 */
	function clear(): void {
		entry.value = null
		state.value = null
		server.value = null
		quote.value = null
		error.value = null
	}

	// Checkout takes the order only with a current, valid price and a server
	const canCheckout = computed(() => quote.value !== null && error.value === null && server.value !== null && !quoting.value)

	let timer: ReturnType<typeof setTimeout> | undefined
	let latest = 0

	/**
	 * Re-price after the choices settle. A slow answer to an older request never overwrites a newer one.
	 */
	function requestQuote(delay = 250): void {
		clearTimeout(timer)
		timer = setTimeout(fetchQuote, delay)
	}

	async function fetchQuote(): Promise<void> {
		if (!entry.value || !input.value) {
			return
		}
		const request = ++latest
		quoting.value = true
		try {
			const response = await useApi()<Wrapped<Quote>>('/api/quotes', { method: 'POST', body: { service: entry.value.key, ...input.value } })
			if (request === latest) {
				quote.value = response.data
				error.value = null
			}
		}
		catch (failure) {
			if (request === latest) {
				quote.value = null
				error.value = Object.values(validationErrors(failure))[0]
					?? (statusOf(failure) === 429 ? 'That was a lot of price checks. Give it a few seconds.' : errorMessage(failure, 'The price couldn\'t be worked out. Please try again.'))
			}
		}
		finally {
			if (request === latest) {
				quoting.value = false
			}
		}
	}

	return { entry, state, server, quote, quoting, error, input, canCheckout, start, requestQuote, restore, clear }
})
