<script setup lang="ts">
import type { Metrics, OwedBooster, Wrapped } from '~/types/api'

definePageMeta({ layout: 'dashboard', middleware: ['auth', 'role'], roles: ['admin'] })
useHead({ title: 'Overview' })

const { data, error } = useAsyncData('overview', async () => {
	const [metrics, owed] = await Promise.all([
		useApi()<Wrapped<Metrics>>('/api/metrics'),
		useApi()<Wrapped<OwedBooster[]>>('/api/payouts/owed'),
	])

	return { metrics: metrics.data, owed: owed.data }
})

const plural = (count: number, word: string) => `${count} ${word}${count === 1 ? '' : 's'}`

const tiles = computed(() => {
	if (!data.value) {
		return []
	}
	const { metrics, owed } = data.value
	const counts = metrics.orders_by_status
	const revenue = metrics.revenue_per_week.reduce((sum, week) => sum + week.revenue_cents, 0)
	const paid = metrics.revenue_per_week.reduce((sum, week) => sum + week.orders, 0)
	// A booster whose fines outweigh their shares is owed nothing yet
	const owedCents = owed.reduce((sum, row) => sum + Math.max(0, row.owed_cents), 0)
	const hours = metrics.average_completion_hours

	return [
		{ label: 'Revenue, last 12 weeks', value: formatCents(revenue), caption: `From ${plural(paid, 'paid order')}` },
		{ label: 'Active orders', value: String(counts.pending + counts.in_progress + counts.paused), caption: `${counts.pending} waiting for a booster`, to: '/dashboard/orders?status=pending,in_progress,paused' },
		{ label: 'Average completion', value: hours === null ? '—' : `${hours} h`, caption: 'From claim to completion, last 90 days' },
		{ label: 'Owed to boosters', value: formatCents(owedCents), caption: owed.length ? `${plural(owed.length, 'booster')} waiting for a payout` : 'Everyone is paid up', to: '/dashboard/payouts' },
	]
})
</script>

<template>
	<div>
		<h1 class="text-headline-small font-weight-bold mb-1">
			Overview
		</h1>
		<p class="text-body-medium text-medium-emphasis mb-6">
			How the store is doing. Select a number to open the screen behind it.
		</p>
		<v-alert v-if="error" type="error" variant="tonal" text="The numbers couldn't be loaded." />

		<template v-else-if="data">
			<v-row class="mb-2">
				<v-col v-for="tile in tiles" :key="tile.label" cols="12" sm="6" lg="3">
					<OverviewStatTile v-bind="tile" />
				</v-col>
			</v-row>
			<v-row>
				<v-col cols="12" lg="7">
					<OverviewRevenueChart :weeks="data.metrics.revenue_per_week" />
				</v-col>
				<v-col cols="12" lg="5">
					<OverviewStatusChart :counts="data.metrics.orders_by_status" />
				</v-col>
			</v-row>
		</template>
	</div>
</template>
