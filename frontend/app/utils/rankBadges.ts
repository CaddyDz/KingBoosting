/**
 * Our own rank badges (docs/adr/0004): one hexagon per tier, in a colour of its own, with the tier's initials.
 * They're drawn for this project and don't copy any game's emblems.
 */
export const RANK_BADGES: Record<string, { fill: string, initials: string }> = {
	unranked: { fill: '#6b6876', initials: '–' },
	iron: { fill: '#5d5a58', initials: 'I' },
	bronze: { fill: '#9a5b34', initials: 'B' },
	silver: { fill: '#8f9ba6', initials: 'S' },
	gold: { fill: '#d4a52a', initials: 'G' },
	platinum: { fill: '#2a9187', initials: 'P' },
	emerald: { fill: '#1b7d4f', initials: 'E' },
	diamond: { fill: '#4a68d9', initials: 'D' },
	master: { fill: '#8b46c9', initials: 'M' },
	immortal: { fill: '#b8324f', initials: 'Im' },
}

/**
 * Dark or light initials, whichever reads better on the badge's colour (WCAG relative luminance).
 */
export function inkFor(fill: string): string {
	const channels = [1, 3, 5].map((start) => {
		const value = Number.parseInt(fill.slice(start, start + 2), 16) / 255

		return value <= 0.03928 ? value / 12.92 : ((value + 0.055) / 1.055) ** 2.4
	})
	const luminance = 0.2126 * channels[0]! + 0.7152 * channels[1]! + 0.0722 * channels[2]!

	// White wins below this luminance; above it, dark ink has the higher contrast
	return luminance > 0.179 ? '#1c1429' : '#ffffff'
}
