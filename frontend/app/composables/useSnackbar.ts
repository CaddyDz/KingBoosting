export interface Snack {
	text: string
	color: 'success' | 'error' | 'info' | 'warning'
}

/**
 * One app-wide snackbar for short confirmations and errors.
 */
export function useSnackbar() {
	const snack = useState<Snack | null>('snackbar', () => null)

	return {
		snack,
		success: (text: string) => (snack.value = { text, color: 'success' }),
		error: (text: string) => (snack.value = { text, color: 'error' }),
		info: (text: string) => (snack.value = { text, color: 'info' }),
	}
}
