import { describe, expect, it } from 'vitest'
import { formatCents } from '../../app/utils/money'

describe('formatCents', () => {
	it('formats euro cents', () => {
		expect(formatCents(13425)).toBe('€134.25')
		expect(formatCents(0)).toBe('€0.00')
		expect(formatCents(-125)).toBe('-€1.25')
	})
})
