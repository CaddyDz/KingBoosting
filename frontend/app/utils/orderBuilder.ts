import type { CatalogEntry, CatalogRank } from '~/types/api'

export interface Rank {
	tier: string
	division: string | null
}

/**
 * Everything the builder can ask for. Each strategy reads only its own fields (see quoteInput).
 */
export interface BuilderState {
	current: Rank
	desired: Rank
	lp: string | null
	mmr: string | null
	amount: number
	champion: string
	level: { current: number, desired: number }
	tokens: number
	options: string[]
}

/**
 * The builder's starting point: the service's defaults from the catalog, with every other field neutral.
 */
export function initialState(entry: CatalogEntry): BuilderState {
	const defaults = entry.defaults as Partial<{
		current: Partial<Rank>
		desired: Partial<Rank>
		lp: string
		mmr: string
		amount: number
		champion: string
		level: { current: number, desired: number }
		tokens: number
	}>
	const firstTier = entry.ranks?.[0]?.key ?? ''

	return {
		current: validRank(entry.ranks, { tier: defaults.current?.tier ?? firstTier, division: defaults.current?.division ?? null }),
		desired: validRank(entry.ranks, { tier: defaults.desired?.tier ?? firstTier, division: defaults.desired?.division ?? null }),
		lp: defaults.lp ?? entry.lp?.[0] ?? null,
		mmr: defaults.mmr ?? entry.mmr?.[0] ?? null,
		amount: defaults.amount ?? entry.amount?.default ?? 1,
		champion: defaults.champion ?? entry.champions?.[0] ?? '',
		level: defaults.level ?? { current: 0, desired: 1 },
		tokens: defaults.tokens ?? 1,
		options: [],
	}
}

/**
 * Keeps a rank's division valid for its tier: tiers without divisions (Master, Unranked) take none, and a
 * division the tier doesn't have becomes the tier's lowest.
 */
export function validRank(ranks: CatalogRank[] | null, rank: Rank): Rank {
	const divisions = ranks?.find(tier => tier.key === rank.tier)?.divisions ?? []
	if (divisions.length === 0) {
		return { tier: rank.tier, division: null }
	}

	return { tier: rank.tier, division: rank.division && divisions.includes(rank.division) ? rank.division : divisions[0]! }
}

/**
 * The mastery tokens the chosen levels need, if any (from level 5 up).
 */
export function tokenRule(entry: CatalogEntry, level: { current: number, desired: number }): { min: number, max: number } | null {
	return entry.mastery?.tokens[`${level.current}-${level.desired}`] ?? null
}

/**
 * The body of POST /api/quotes (minus `service`) for the builder's state: only what the service's strategy reads.
 */
export function quoteInput(entry: CatalogEntry, state: BuilderState): Record<string, unknown> {
	const rank = (value: Rank) => (value.division ? { tier: value.tier, division: value.division } : { tier: value.tier })
	const options = state.options.filter(key => entry.options.some(option => option.key === key))

	switch (entry.strategy) {
		case 'division-ladder':
			return {
				current: rank(state.current),
				desired: rank(state.desired),
				...(entry.lp ? { lp: state.lp } : {}),
				...(entry.mmr ? { mmr: state.mmr } : {}),
				options,
			}
		case 'per-game':
		case 'per-win':
			return { current: rank(state.current), amount: state.amount, options }
		case 'mastery':
			return {
				champion: state.champion,
				level: { ...state.level },
				...(tokenRule(entry, state.level) ? { tokens: state.tokens } : {}),
				options,
			}
		case 'unrated':
			return { amount: state.amount, options }
	}
}
