import { createApiClient, type ApiFetch } from '~/utils/api'

/**
 * The Laravel API client. In the browser it talks to /api on the same origin (docs/adr/0001); during
 * server-side rendering it reaches Laravel directly and only ever calls public endpoints.
 */
export function useApi(): ApiFetch {
	const nuxtApp = useNuxtApp()
	if (nuxtApp._api) {
		return nuxtApp._api
	}

	const config = useRuntimeConfig()
	const client = createApiClient({
		fetch: (path, options) => $fetch(path, options as Parameters<typeof $fetch>[1]),
		xsrfToken: () => {
			if (import.meta.server) {
				return null
			}
			const cookie = document.cookie.split('; ').find(entry => entry.startsWith('XSRF-TOKEN='))

			return cookie ? decodeURIComponent(cookie.slice('XSRF-TOKEN='.length)) : null
		},
		refreshCsrf: async () => {
			await $fetch('/sanctum/csrf-cookie', { credentials: 'include' })
		},
		baseURL: import.meta.server ? config.apiBase : '',
	})
	nuxtApp._api = client

	return client
}

declare module '#app' {
	interface NuxtApp {
		_api?: ApiFetch
	}
}
