import type { FetchOptions } from 'ofetch'

export type ApiFetch = <T>(path: string, options?: FetchOptions<'json'>) => Promise<T>

interface ApiClientOptions {
	/** The underlying fetch: Nuxt's $fetch in the app, a stub in tests */
	fetch: (path: string, options: FetchOptions<'json'>) => Promise<unknown>
	/** The XSRF-TOKEN cookie Laravel sets, decoded, or null when there's none yet */
	xsrfToken: () => string | null
	/** GET /sanctum/csrf-cookie */
	refreshCsrf: () => Promise<void>
	baseURL?: string
}

const SAFE_METHODS = ['GET', 'HEAD', 'OPTIONS']

/**
 * Calls the Laravel API with Sanctum's SPA cookies: the session cookie goes with every request, and
 * writes carry the X-XSRF-TOKEN header. If Laravel answers 419 (the CSRF token expired), the client
 * fetches a fresh token and retries the request once.
 */
export function createApiClient({ fetch, xsrfToken, refreshCsrf, baseURL = '' }: ApiClientOptions): ApiFetch {
	const send = <T>(path: string, options: FetchOptions<'json'>): Promise<T> => {
		const token = xsrfToken()
		const headers = new Headers(options.headers)
		headers.set('Accept', 'application/json')
		if (token) {
			headers.set('X-XSRF-TOKEN', token)
		}

		return fetch(path, { ...options, baseURL, credentials: 'include', headers }) as Promise<T>
	}

	return async <T>(path: string, options: FetchOptions<'json'> = {}): Promise<T> => {
		const method = (options.method ?? 'GET').toUpperCase()
		if (!SAFE_METHODS.includes(method) && !xsrfToken()) {
			await refreshCsrf()
		}

		try {
			return await send<T>(path, options)
		}
		catch (error) {
			if (statusOf(error) !== 419) {
				throw error
			}
			await refreshCsrf()

			return await send<T>(path, options)
		}
	}
}

/**
 * A cookie's value from `document.cookie`, decoded, or null. Laravel URL-encodes the XSRF-TOKEN cookie.
 */
export function readCookie(cookies: string, name: string): string | null {
	const cookie = cookies.split('; ').find(entry => entry.startsWith(`${name}=`))

	return cookie ? decodeURIComponent(cookie.slice(name.length + 1)) : null
}

/**
 * The HTTP status of a failed request, if it got an answer.
 */
export function statusOf(error: unknown): number | undefined {
	return (error as { status?: number, response?: { status?: number } })?.status
		?? (error as { response?: { status?: number } })?.response?.status
}

/**
 * Laravel's validation errors (422) as field => first message.
 */
export function validationErrors(error: unknown): Record<string, string> {
	if (statusOf(error) !== 422) {
		return {}
	}
	const errors = (error as { data?: { errors?: Record<string, string[]> } }).data?.errors ?? {}

	return Object.fromEntries(Object.entries(errors).map(([field, messages]) => [field, messages[0] ?? '']))
}

/**
 * The API's message for a failed request, or a generic one.
 */
export function errorMessage(error: unknown, fallback = 'Something went wrong. Please try again.'): string {
	return (error as { data?: { message?: string } })?.data?.message || fallback
}
