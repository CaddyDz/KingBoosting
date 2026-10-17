<script setup lang="ts">
import { formatDateTime } from '~/utils/orders'
import type { Order } from '~/types/api'

const props = defineProps<{ order: Order }>()
const { serviceLabel } = useCatalog()

const dates = computed(() => [
	['Placed', props.order.created_at],
	['Paid', props.order.paid_at],
	['Claimed', props.order.claimed_at],
	['Completed', props.order.completed_at],
	['Booster paid', props.order.paid_out_at],
].filter(([, value]) => value) as [string, string][])
</script>

<template>
	<v-card>
		<v-card-item>
			<template #prepend>
				<v-avatar color="primary" variant="tonal" rounded="lg">
					#{{ order.id }}
				</v-avatar>
			</template>
			<v-card-title class="text-title-large text-wrap">
				{{ order.title }}
			</v-card-title>
			<v-card-subtitle>
				{{ order.game.label }} · {{ serviceLabel(order.service) }} · server {{ order.server.toUpperCase() }} · in-game name {{ order.account_name }}
			</v-card-subtitle>
			<template #append>
				<OrdersOrderStatusChip :status="order.status" size="default" data-testid="order-status" />
			</template>
		</v-card-item>
		<v-card-text>
			<div v-if="order.options.length" class="d-flex flex-wrap ga-2 mb-4">
				<v-chip v-for="option in order.options" :key="option" size="small" variant="outlined">
					{{ option }}
				</v-chip>
			</div>
			<v-alert v-if="order.comment" variant="tonal" density="compact" class="mb-4">
				<strong>Note from the customer:</strong> {{ order.comment }}
			</v-alert>

			<v-table density="compact" class="bg-transparent">
				<tbody>
					<tr v-for="[label, value] in dates" :key="label">
						<td class="text-medium-emphasis" style="width: 140px">
							{{ label }}
						</td>
						<td>{{ formatDateTime(value) }}</td>
					</tr>
				</tbody>
			</v-table>

			<template v-if="order.quote">
				<v-divider class="my-4" />
				<div class="text-title-small mb-2">
					Price
				</div>
				<div v-for="line in order.quote.lines" :key="line.label" class="d-flex justify-space-between text-body-medium py-1">
					<span>{{ line.label }}</span>
					<span>{{ formatCents(line.cents) }}</span>
				</div>
				<div class="d-flex justify-space-between font-weight-bold mt-2" data-testid="order-total">
					<span>Total</span>
					<span>{{ formatCents(order.quote.total_cents) }}</span>
				</div>
			</template>
			<div v-else-if="order.booster_share_cents !== undefined" class="d-flex justify-space-between font-weight-bold mt-4">
				<span>Your share</span>
				<span>{{ formatCents(order.booster_share_cents) }}</span>
			</div>
		</v-card-text>
	</v-card>
</template>
