import { describe, expect, it } from 'vitest'
import { formatCents, formatCompactCents, parseEuros } from '../../app/utils/money'

describe('formatCents', () => {
	it('formats euro cents', () => {
		expect(formatCents(13425)).toBe('€134.25')
		expect(formatCents(0)).toBe('€0.00')
		expect(formatCents(-125)).toBe('-€1.25')
	})
})

describe('formatCompactCents', () => {
	it('shortens thousands', () => {
		expect(formatCompactCents(0)).toBe('€0')
		expect(formatCompactCents(50000)).toBe('€500')
		expect(formatCompactCents(150000)).toBe('€1.5K')
		expect(formatCompactCents(200000)).toBe('€2K')
	})
})

describe('parseEuros', () => {
	it('reads whole and decimal amounts as cents', () => {
		expect(parseEuros('12')).toBe(1200)
		expect(parseEuros(' 12.5 ')).toBe(1250)
		expect(parseEuros('12,05')).toBe(1205)
		expect(parseEuros('0.07')).toBe(7)
	})

	it('rejects anything else', () => {
		expect(parseEuros('')).toBeNull()
		expect(parseEuros('-5')).toBeNull()
		expect(parseEuros('1.234')).toBeNull()
		expect(parseEuros('ten')).toBeNull()
	})
})
