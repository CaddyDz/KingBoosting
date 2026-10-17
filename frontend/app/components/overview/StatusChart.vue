<script setup lang="ts">
import { ORDER_STATUSES } from '~/utils/orders'
import type { OrderStatus } from '~/types/api'

const props = defineProps<{ counts: Record<OrderStatus, number> }>()

const rows = computed(() => {
	const max = Math.max(1, ...Object.values(props.counts))

	return ORDER_STATUSES.map(status => ({ ...status, count: props.counts[status.value] ?? 0, share: (props.counts[status.value] ?? 0) / max }))
})
</script>

<template>
	<v-card class="h-100" title="Orders by status">
		<v-card-text>
			<p class="text-body-medium text-medium-emphasis mb-3">
				Every order, in the order of its life. Select a status to list its orders.
			</p>
			<NuxtLink v-for="row in rows" :key="row.value" :to="`/dashboard/orders?status=${row.value}`" class="status-row" :aria-label="`${row.title}: ${row.count} orders`" data-testid="status-row">
				<span class="status-label">{{ row.title }}</span>
				<span class="status-track">
					<span class="status-bar" :style="{ width: `calc((100% - 3em) * ${row.share})` }" />
					<span class="status-count">{{ row.count }}</span>
				</span>
			</NuxtLink>
		</v-card-text>
	</v-card>
</template>

<style scoped>
.status-row {
	display: grid;
	grid-template-columns: minmax(0, 11rem) 1fr;
	align-items: center;
	gap: 12px;
	padding: 6px 8px;
	border-radius: 6px;
	text-decoration: none;
}

.status-row:hover {
	background: rgba(var(--v-theme-on-surface), 0.05);
}

.status-row:focus-visible {
	outline: 2px solid rgb(var(--v-theme-primary));
}

.status-label {
	font-size: 0.875rem;
}

.status-track {
	display: flex;
	align-items: center;
	min-width: 0;
	min-height: 24px;
	border-left: 1px solid rgba(var(--v-theme-on-surface), 0.24);
}

.status-bar {
	flex: none;
	height: 16px;
	border-radius: 0 4px 4px 0;
	background: rgb(var(--v-theme-chart));
}

.status-row:hover .status-bar {
	filter: brightness(1.15);
}

.status-count {
	margin-left: 8px;
	font-size: 0.875rem;
	font-weight: 600;
	font-variant-numeric: tabular-nums;
}
</style>
