import { describe, expect, it } from 'vitest'
import { initialState, quoteInput, tokenRule, validRank } from '../../app/utils/orderBuilder'
import type { CatalogEntry } from '../../app/types/api'

const lolRanks = [
	{ key: 'gold', name: 'Gold', divisions: ['IV', 'III', 'II', 'I'] },
	{ key: 'platinum', name: 'Platinum', divisions: ['IV', 'III', 'II', 'I'] },
	{ key: 'master', name: 'Master', divisions: [] },
]

function entry(overrides: Partial<CatalogEntry>): CatalogEntry {
	return {
		key: 'division-boosting',
		game: { value: 'lol', label: 'League of Legends' },
		label: 'Division boosting',
		strategy: 'division-ladder',
		ranks: lolRanks,
		lp: ['0-20', '21-40'],
		mmr: ['21+', '15-17'],
		amount: null,
		mastery: null,
		champions: null,
		servers: [{ key: 'euw', label: 'EU West' }],
		options: [{ key: 'priority', label: 'Priority order', description: '', percent: 20 }],
		defaults: { current: { tier: 'gold', division: 'II' }, desired: { tier: 'platinum', division: 'IV' }, lp: '21-40', mmr: '15-17' },
		...overrides,
	}
}

describe('validRank', () => {
	it('drops the division for tiers without one and fixes divisions the tier lacks', () => {
		expect(validRank(lolRanks, { tier: 'master', division: 'II' })).toEqual({ tier: 'master', division: null })
		expect(validRank(lolRanks, { tier: 'gold', division: null })).toEqual({ tier: 'gold', division: 'IV' })
		expect(validRank(lolRanks, { tier: 'gold', division: 'II' })).toEqual({ tier: 'gold', division: 'II' })
	})
})

describe('quoteInput', () => {
	it('sends a ladder climb with LP, MMR and known options only', () => {
		const ladder = entry({})
		const state = { ...initialState(ladder), options: ['priority', 'made-up'] }

		expect(quoteInput(ladder, state)).toEqual({
			current: { tier: 'gold', division: 'II' },
			desired: { tier: 'platinum', division: 'IV' },
			lp: '21-40',
			mmr: '15-17',
			options: ['priority'],
		})
	})

	it('leaves out LP and MMR where the service has none, and the division of a tier without divisions', () => {
		const ladder = entry({ lp: null, mmr: null })
		const state = { ...initialState(ladder), desired: { tier: 'master', division: null } }

		expect(quoteInput(ladder, state)).toEqual({ current: { tier: 'gold', division: 'II' }, desired: { tier: 'master' }, options: [] })
	})

	it('sends a rank and an amount for wins and placements', () => {
		const wins = entry({ strategy: 'per-win', amount: { label: 'wins', min: 1, max: 10, default: 4 }, defaults: { current: { tier: 'gold', division: 'I' }, amount: 4 } })

		expect(quoteInput(wins, { ...initialState(wins), amount: 7 })).toEqual({ current: { tier: 'gold', division: 'I' }, amount: 7, options: [] })
	})

	it('sends tokens only for the mastery levels that need them', () => {
		const mastery = entry({
			strategy: 'mastery',
			ranks: null,
			lp: null,
			mmr: null,
			mastery: { max_level: 7, tokens: { '5-7': { min: 4, max: 5 } } },
			champions: ['Ahri', 'Aatrox'],
			defaults: { champion: 'Aatrox', level: { current: 5, desired: 7 }, tokens: 5 },
		})
		const state = initialState(mastery)

		expect(tokenRule(mastery, state.level)).toEqual({ min: 4, max: 5 })
		expect(quoteInput(mastery, state)).toEqual({ champion: 'Aatrox', level: { current: 5, desired: 7 }, tokens: 5, options: [] })
		expect(quoteInput(mastery, { ...state, level: { current: 2, desired: 4 } })).toEqual({ champion: 'Aatrox', level: { current: 2, desired: 4 }, options: [] })
	})

	it('sends only the number of games for unrated matches', () => {
		const unrated = entry({ strategy: 'unrated', ranks: null, lp: null, mmr: null, amount: { label: 'games', min: 1, max: 30, default: 5 }, defaults: { amount: 5 } })

		expect(quoteInput(unrated, initialState(unrated))).toEqual({ amount: 5, options: [] })
	})
})
