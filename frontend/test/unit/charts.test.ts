import { describe, expect, it } from 'vitest'
import { niceTicks } from '../../app/utils/charts'

describe('niceTicks', () => {
	it('picks round steps that cover the maximum', () => {
		expect(niceTicks(176547)).toEqual([0, 50000, 100000, 150000, 200000])
		expect(niceTicks(9)).toEqual([0, 2.5, 5, 7.5, 10])
		expect(niceTicks(40)).toEqual([0, 10, 20, 30, 40])
	})

	it('has only the baseline when there is nothing to show', () => {
		expect(niceTicks(0)).toEqual([0])
	})
})
