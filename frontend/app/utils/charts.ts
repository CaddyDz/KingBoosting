/**
 * Evenly spaced axis ticks from zero that cover `max`, on round steps (1, 2, 2.5 or 5 times a power of ten).
 */
export function niceTicks(max: number, target = 4): number[] {
	if (max <= 0) {
		return [0]
	}
	const rough = max / target
	const magnitude = 10 ** Math.floor(Math.log10(rough))
	const step = [1, 2, 2.5, 5, 10].map(factor => factor * magnitude).find(candidate => candidate >= rough) ?? 10 * magnitude
	const count = Math.ceil(max / step)

	return Array.from({ length: count + 1 }, (_, index) => index * step)
}
