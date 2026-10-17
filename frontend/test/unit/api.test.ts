import { describe, expect, it, vi } from 'vitest'
import { createApiClient, readCookie, validationErrors } from '../../app/utils/api'

function httpError(status: number, data: unknown = {}): Error & { status: number, data: unknown } {
	return Object.assign(new Error(`HTTP ${status}`), { status, data })
}

function client(fetch: ReturnType<typeof vi.fn>, token: string | null = 'token-1') {
	let xsrf = token
	const refreshCsrf = vi.fn(async () => {
		xsrf = 'token-2'
	})

	return { api: createApiClient({ fetch, xsrfToken: () => xsrf, refreshCsrf }), refreshCsrf }
}

describe('createApiClient', () => {
	it('sends the session cookie, JSON and the CSRF header', async () => {
		const fetch = vi.fn().mockResolvedValue({ data: 'ok' })
		const { api } = client(fetch)

		await api('/api/orders', { method: 'POST', body: { a: 1 } })

		const [path, options] = fetch.mock.calls[0]!
		expect(path).toBe('/api/orders')
		expect(options.credentials).toBe('include')
		expect(options.headers.get('X-XSRF-TOKEN')).toBe('token-1')
		expect(options.headers.get('Accept')).toBe('application/json')
	})

	it('fetches a CSRF cookie before the first write', async () => {
		const fetch = vi.fn().mockResolvedValue({})
		const { api, refreshCsrf } = client(fetch, null)

		await api('/api/login', { method: 'POST' })

		expect(refreshCsrf).toHaveBeenCalledOnce()
		expect(fetch.mock.calls[0]![1].headers.get('X-XSRF-TOKEN')).toBe('token-2')
	})

	it('refreshes the CSRF cookie and retries once after a 419', async () => {
		const fetch = vi.fn().mockRejectedValueOnce(httpError(419)).mockResolvedValueOnce({ data: 'saved' })
		const { api, refreshCsrf } = client(fetch)

		await expect(api('/api/orders/1/pause', { method: 'POST' })).resolves.toEqual({ data: 'saved' })

		expect(refreshCsrf).toHaveBeenCalledOnce()
		expect(fetch).toHaveBeenCalledTimes(2)
		expect(fetch.mock.calls[1]![1].headers.get('X-XSRF-TOKEN')).toBe('token-2')
	})

	it('gives up after the retry fails too', async () => {
		const fetch = vi.fn().mockRejectedValue(httpError(419))
		const { api } = client(fetch)

		await expect(api('/api/logout', { method: 'POST' })).rejects.toMatchObject({ status: 419 })
		expect(fetch).toHaveBeenCalledTimes(2)
	})

	it('doesn’t retry other errors', async () => {
		const fetch = vi.fn().mockRejectedValue(httpError(403))
		const { api, refreshCsrf } = client(fetch)

		await expect(api('/api/orders/1')).rejects.toMatchObject({ status: 403 })
		expect(refreshCsrf).not.toHaveBeenCalled()
		expect(fetch).toHaveBeenCalledOnce()
	})
})

describe('validationErrors', () => {
	it('keeps the first message per field', () => {
		expect(validationErrors(httpError(422, { errors: { email: ['Taken.', 'Other.'] } }))).toEqual({ email: 'Taken.' })
		expect(validationErrors(httpError(500))).toEqual({})
	})
})

describe('readCookie', () => {
	it('finds a cookie among others and decodes it, the way Laravel encodes XSRF-TOKEN', () => {
		expect(readCookie('theme=dark; XSRF-TOKEN=eyJpdiI6%3D%3D; other=1', 'XSRF-TOKEN')).toBe('eyJpdiI6==')
		expect(readCookie('XSRF-TOKEN=abc', 'XSRF-TOKEN')).toBe('abc')
	})

	it('is null when the cookie isn\'t set, even if another name contains it', () => {
		expect(readCookie('', 'XSRF-TOKEN')).toBeNull()
		expect(readCookie('OLD-XSRF-TOKEN=abc', 'XSRF-TOKEN')).toBeNull()
	})
})
