const euros = new Intl.NumberFormat('en-IE', { style: 'currency', currency: 'EUR' })

/**
 * Every amount the API returns is in euro cents; this is the one place they become text.
 */
export function formatCents(cents: number): string {
	return euros.format(cents / 100)
}
