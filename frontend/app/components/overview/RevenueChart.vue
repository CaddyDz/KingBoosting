<script setup lang="ts">
import { niceTicks } from '~/utils/charts'
import { formatCompactCents } from '~/utils/money'
import type { Metrics } from '~/types/api'

const props = defineProps<{ weeks: Metrics['revenue_per_week'] }>()

const view = ref<'chart' | 'table'>('chart')
const active = ref<number | null>(null)

const shortDate = new Intl.DateTimeFormat('en-GB', { day: 'numeric', month: 'short' })

const max = computed(() => Math.max(0, ...props.weeks.map(week => week.revenue_cents)))
const ticks = computed(() => niceTicks(max.value))
const top = computed(() => ticks.value.at(-1) || 1)

// Values ride on the best week and the latest one only; the axis, the tooltip and the table carry the rest
const labelled = computed(() => new Set([props.weeks.findIndex(week => week.revenue_cents === max.value), props.weeks.length - 1]
	.filter(index => (props.weeks[index]?.revenue_cents ?? 0) > 0)))

const columns = computed(() => props.weeks.map((week, index) => ({
	...week,
	// The API sends the Monday as a date: read it as local midnight so the label never slips a day
	label: shortDate.format(new Date(`${week.week}T00:00:00`)),
	height: (week.revenue_cents / top.value) * 100,
	// The tooltip sits beside the column, on whichever side has more room
	tooltip: index < props.weeks.length / 2
		? { left: `${((index + 1) / props.weeks.length) * 100}%` }
		: { right: `${(1 - index / props.weeks.length) * 100}%` },
	// Axis labels thin out on narrow cards (every other week, then every third), counting back from the latest
	minor: (props.weeks.length - 1 - index) % 2 === 1,
	sparse: (props.weeks.length - 1 - index) % 3 !== 0,
})))
const activeColumn = computed(() => (active.value === null ? null : columns.value[active.value] ?? null))
</script>

<template>
	<v-card class="h-100">
		<v-card-item title="Revenue per week">
			<template #append>
				<v-btn-toggle v-model="view" mandatory density="compact" variant="outlined" divided>
					<v-btn value="chart" size="small">
						Chart
					</v-btn>
					<v-btn value="table" size="small" data-testid="revenue-table-toggle">
						Table
					</v-btn>
				</v-btn-toggle>
			</template>
		</v-card-item>
		<v-card-text>
			<p class="text-body-medium text-medium-emphasis mb-2">
				Paid orders over the last 12 weeks, by the week they were paid.
			</p>
			<div v-if="max === 0" class="py-8 text-center text-medium-emphasis">
				No paid orders in the last 12 weeks.
			</div>
			<figure v-else-if="view === 'chart'" class="revenue-chart" :style="{ '--columns': weeks.length }" data-testid="revenue-chart" @pointerleave="active = null">
				<div class="plot">
					<div v-for="tick in ticks" :key="tick" class="gridline" :class="{ baseline: tick === 0 }" :style="{ bottom: `${(tick / top) * 100}%` }">
						<span class="tick">{{ formatCompactCents(tick) }}</span>
					</div>
					<div class="columns" role="group" aria-label="Revenue per week">
						<button
							v-for="(column, index) in columns"
							:key="column.week"
							type="button"
							class="column"
							:class="{ active: active === index }"
							:aria-label="`Week of ${column.label}: ${formatCents(column.revenue_cents)} from ${column.orders} paid orders`"
							@pointerenter="active = index"
							@focus="active = index"
							@blur="active = null"
						>
							<span class="bar" :style="{ height: `${column.height}%` }" />
							<span v-if="labelled.has(index)" class="value" :style="{ bottom: `calc(${column.height}% + 4px)` }">{{ formatCompactCents(column.revenue_cents) }}</span>
						</button>
					</div>
					<div v-if="activeColumn" class="chart-tooltip" :style="activeColumn.tooltip" aria-hidden="true" data-testid="revenue-tooltip">
						<strong>{{ formatCents(activeColumn.revenue_cents) }}</strong>
						<span>Week of {{ activeColumn.label }} · {{ activeColumn.orders }} paid {{ activeColumn.orders === 1 ? 'order' : 'orders' }}</span>
					</div>
				</div>
				<div class="x-axis" aria-hidden="true">
					<span v-for="column in columns" :key="column.week" :class="{ minor: column.minor, sparse: column.sparse }">{{ column.label }}</span>
				</div>
			</figure>
			<v-table v-else density="compact" data-testid="revenue-table">
				<thead>
					<tr>
						<th>Week of</th>
						<th class="text-end">
							Paid orders
						</th>
						<th class="text-end">
							Revenue
						</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="column in columns" :key="column.week">
						<td>{{ column.label }}</td>
						<td class="text-end numeric">
							{{ column.orders }}
						</td>
						<td class="text-end numeric">
							{{ formatCents(column.revenue_cents) }}
						</td>
					</tr>
				</tbody>
			</v-table>
		</v-card-text>
	</v-card>
</template>

<style scoped>
.revenue-chart {
	margin: 0;
	container-type: inline-size;
}

/* Room on the left for the tick labels, and above for the top tick and the tallest column's value */
.plot {
	position: relative;
	height: 200px;
	margin: 20px 0 0 48px;
}

.gridline {
	position: absolute;
	left: 0;
	right: 0;
	border-top: 1px solid rgba(var(--v-theme-on-surface), 0.08);
}

.gridline.baseline {
	border-top-color: rgba(var(--v-theme-on-surface), 0.24);
}

.tick,
.x-axis {
	font-size: 0.75rem;
	color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
	font-variant-numeric: tabular-nums;
}

.tick {
	position: absolute;
	right: calc(100% + 8px);
	transform: translateY(-50%);
	white-space: nowrap;
}

.columns {
	position: absolute;
	inset: 0;
	display: grid;
	grid-template-columns: repeat(var(--columns), minmax(0, 1fr));
}

.column {
	position: relative;
	height: 100%;
	padding: 0;
	border: 0;
	border-radius: 4px;
	background: none;
	cursor: default;
}

.column.active {
	background: rgba(var(--v-theme-on-surface), 0.05);
}

.column:focus-visible {
	outline: 2px solid rgb(var(--v-theme-primary));
	outline-offset: 2px;
}

.bar {
	position: absolute;
	bottom: 0;
	left: 50%;
	width: min(24px, 60%);
	transform: translateX(-50%);
	border-radius: 4px 4px 0 0;
	background: rgb(var(--v-theme-chart));
}

.column.active .bar {
	filter: brightness(1.15);
}

.value {
	position: absolute;
	left: 50%;
	transform: translateX(-50%);
	font-size: 0.75rem;
	font-weight: 600;
	white-space: nowrap;
	color: rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity));
}

.chart-tooltip {
	position: absolute;
	top: 0;
	z-index: 1;
	display: flex;
	flex-direction: column;
	margin: 0 6px;
	padding: 6px 10px;
	border-radius: 8px;
	background: rgb(var(--v-theme-surface-variant));
	color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
	font-size: 0.75rem;
	white-space: nowrap;
	pointer-events: none;
}

.chart-tooltip strong {
	font-size: 0.875rem;
	color: rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity));
}

.x-axis {
	display: grid;
	/* minmax(0, ...) keeps the cells even: a label wider than its week spills into the hidden neighbour */
	grid-template-columns: repeat(var(--columns), minmax(0, 1fr));
	margin-left: 48px;
	padding-top: 6px;
	text-align: center;
	white-space: nowrap;
}

@container (max-width: 760px) {
	.x-axis .minor {
		visibility: hidden;
	}
}

@container (max-width: 440px) {
	.x-axis .minor:not(.sparse) {
		visibility: visible;
	}

	.x-axis .sparse {
		visibility: hidden;
	}
}

.numeric {
	font-variant-numeric: tabular-nums;
}
</style>
