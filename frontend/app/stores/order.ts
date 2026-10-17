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

	return { entry, state, server, quote, quoting, error, input, canCheckout, start, requestQuote }
})
