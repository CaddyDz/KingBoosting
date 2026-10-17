import { describe, expect, it } from 'vitest'
import { RANK_BADGES, inkFor } from '../../app/utils/rankBadges'

// WCAG contrast between two hex colours
function contrast(a: string, b: string): number {
	const luminance = (hex: string) => {
		const [r, g, bl] = [1, 3, 5].map((start) => {
			const value = Number.parseInt(hex.slice(start, start + 2), 16) / 255

			return value <= 0.03928 ? value / 12.92 : ((value + 0.055) / 1.055) ** 2.4
		}) as [number, number, number]

		return 0.2126 * r + 0.7152 * g + 0.0722 * bl
	}
	const [light, dark] = [luminance(a), luminance(b)].sort((x, y) => y - x) as [number, number]

	return (light + 0.05) / (dark + 0.05)
}

describe('rank badges', () => {
	it('has a badge for every tier the pricing tables use', () => {
		for (const tier of ['unranked', 'iron', 'bronze', 'silver', 'gold', 'platinum', 'emerald', 'diamond', 'master', 'immortal']) {
			expect(RANK_BADGES[tier]).toBeDefined()
		}
	})

	it('picks initials that stay readable on every badge (at least 4.5:1)', () => {
		for (const { fill } of Object.values(RANK_BADGES)) {
			expect(contrast(fill, inkFor(fill))).toBeGreaterThanOrEqual(4.5)
		}
	})
})
