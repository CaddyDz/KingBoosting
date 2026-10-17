import type Echo from 'laravel-echo'

/**
 * The Reverb connection, opened on first use. Browser only: call it from onMounted or a client-only page.
 */
export function useEcho(): Echo<'reverb'> {
	return useNuxtApp().$echo.connect()
}
