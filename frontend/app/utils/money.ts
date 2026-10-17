const euros = new Intl.NumberFormat('en-IE', { style: 'currency', currency: 'EUR' })
const compactEuros = new Intl.NumberFormat('en-IE', { style: 'currency', currency: 'EUR', notation: 'compact', minimumFractionDigits: 0, maximumFractionDigits: 1 })

/**
 * Every amount the API returns is in euro cents; this is the one place they become text.
 */
export function formatCents(cents: number): string {
	return euros.format(cents / 100)
}

/**
 * Short amounts for chart axes and labels: €500, €1.5K.
 */
export function formatCompactCents(cents: number): string {
	return compactEuros.format(cents / 100)
}

/**
 * What an admin types into an amount field ("12", "12.5", "12,50") as cents, or null if it isn't an amount.
 */
export function parseEuros(input: string): number | null {
	const normalized = input.trim().replace(',', '.')
	if (!/^\d+(\.\d{1,2})?$/.test(normalized)) {
		return null
	}

	return Math.round(Number(normalized) * 100)
}
