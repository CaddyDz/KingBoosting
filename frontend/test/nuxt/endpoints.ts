/**
 * Helpers for registerEndpoint handlers. @nuxt/test-utils runs them on Nitro's h3, which hands over a v1 or a v2
 * event depending on the version installed; these read and answer either.
 */
export interface MockEvent {
	req?: unknown
	web?: { request?: Request }
	node?: { res?: { statusCode: number, setHeader: (name: string, value: string) => void } }
}

/**
 * The web Request: `event.web.request` on h3 v1 (where `event.req` is Node's), `event.req` on h3 v2.
 */
export function requestOf(event: MockEvent): Request {
	const request = event.web?.request ?? event.req
	if (!(request instanceof Request)) {
		throw new TypeError('The mock endpoint got no web request')
	}

	return request
}

/**
 * A JSON answer with an HTTP status other than 200.
 */
export function respond(event: MockEvent, status: number, body: unknown = null): unknown {
	if (event.node?.res) {
		event.node.res.statusCode = status
		event.node.res.setHeader('content-type', 'application/json')

		return body
	}

	return new Response(body === null ? null : JSON.stringify(body), { status, headers: { 'content-type': 'application/json' } })
}
