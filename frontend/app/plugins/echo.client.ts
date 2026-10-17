import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

/**
 * Laravel Echo on Reverb, created the first time a signed-in page asks for it.
 * The socket goes to /app on the page's own origin (wss on 443 in production), and private channels
 * are authorised through the API client, so they carry the session cookie and the CSRF header.
 */
export default defineNuxtPlugin(() => {
	let echo: Echo<'reverb'> | null = null

	function connect(): Echo<'reverb'> {
		if (echo) {
			return echo
		}

		const config = useRuntimeConfig().public.reverb
		const api = useApi()
		const secure = (config.scheme || window.location.protocol.replace(':', '')) === 'https'
		const port = Number(config.port) || Number(window.location.port) || (secure ? 443 : 80)

		echo = new Echo({
			broadcaster: 'reverb',
			Pusher,
			key: config.key,
			wsHost: config.host || window.location.hostname,
			wsPort: port,
			wssPort: port,
			forceTLS: secure,
			enabledTransports: ['ws', 'wss'],
			authorizer: (channel: { name: string }) => ({
				authorize: (socketId: string, callback: (error: Error | null, data: { auth: string } | null) => void) => {
					api<{ auth: string }>('/broadcasting/auth', { method: 'POST', body: { socket_id: socketId, channel_name: channel.name } })
						.then(data => callback(null, data))
						.catch((error: Error) => callback(error, null))
				},
			}),
		})

		return echo
	}

	function disconnect(): void {
		echo?.disconnect()
		echo = null
	}

	return { provide: { echo: { connect, disconnect } } }
})
