<script setup lang="ts">
import { formatDate } from '~/utils/orders'
import type { Earnings, Wrapped } from '~/types/api'

definePageMeta({ layout: 'dashboard', middleware: ['auth', 'role'], roles: ['booster'] })
useHead({ title: 'Earnings' })

const { data, error } = useAsyncData('earnings', async () => (await useApi()<Wrapped<Earnings>>('/api/earnings')).data)

const cards = computed(() => data.value
	? [
			{ label: 'Owed to you', value: data.value.summary.owed_cents, color: data.value.summary.owed_cents >= 0 ? 'success' : 'error', testid: 'owed' },
			{ label: `Shares from ${data.value.summary.orders_count} completed order${data.value.summary.orders_count === 1 ? '' : 's'}`, value: data.value.summary.share_cents, color: undefined, testid: 'shares' },
			{ label: 'Unpaid fines', value: -data.value.summary.fines_cents, color: data.value.summary.fines_cents ? 'error' : undefined, testid: 'fines' },
		]
	: [])
</script>

<template>
	<div>
		<h1 class="text-h5 font-weight-bold mb-1">
			Earnings
		</h1>
		<p class="text-body-2 text-medium-emphasis mb-6">
			Your share of every completed order, minus fines. Admins pay out in batches.
		</p>
		<v-alert v-if="error" type="error" variant="tonal" text="Your earnings couldn't be loaded." />

		<template v-else-if="data">
			<v-row class="mb-2">
				<v-col v-for="card in cards" :key="card.label" cols="12" md="4">
					<v-card class="pa-4">
						<div class="text-body-2 text-medium-emphasis">
							{{ card.label }}
						</div>
						<div class="text-h4 font-weight-bold mt-1" :class="card.color ? `text-${card.color}` : ''" :data-testid="card.testid">
							{{ formatCents(card.value) }}
						</div>
					</v-card>
				</v-col>
			</v-row>

			<v-row>
				<v-col cols="12" lg="7">
					<v-card title="Waiting for a payout">
						<v-table v-if="data.orders.length" density="comfortable">
							<thead>
								<tr>
									<th>Order</th>
									<th>Completed</th>
									<th class="text-end">
										Your share
									</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="order in data.orders" :key="order.id">
									<td>
										<NuxtLink :to="`/dashboard/orders/${order.id}`" class="text-decoration-none">
											#{{ order.id }} {{ order.title }}
										</NuxtLink>
										<div class="text-caption text-medium-emphasis">
											{{ order.game }}
										</div>
									</td>
									<td>{{ formatDate(order.completed_at) }}</td>
									<td class="text-end">
										{{ formatCents(order.share_cents) }}
									</td>
								</tr>
							</tbody>
						</v-table>
						<v-card-text v-else class="text-medium-emphasis">
							Nothing yet: complete an order and its share shows up here.
						</v-card-text>
					</v-card>
				</v-col>
				<v-col cols="12" lg="5">
					<v-card title="Fines" class="mb-4">
						<v-list v-if="data.fines.length" density="compact" lines="two">
							<v-list-item v-for="fine in data.fines" :key="fine.id" :title="fine.reason" :subtitle="formatDate(fine.created_at)">
								<template #append>
									<div class="text-end">
										<div class="text-error font-weight-medium">
											−{{ formatCents(fine.amount_cents) }}
										</div>
										<v-chip :color="fine.settled ? 'grey' : 'error'" size="x-small" variant="tonal">
											{{ fine.settled ? 'Deducted' : 'Unpaid' }}
										</v-chip>
									</div>
								</template>
							</v-list-item>
						</v-list>
						<v-card-text v-else class="text-medium-emphasis">
							No fines. Keep it that way.
						</v-card-text>
					</v-card>
					<v-card title="Payout history">
						<v-list v-if="data.payouts.length" density="compact" lines="two">
							<v-list-item v-for="payout in data.payouts" :key="payout.id" :title="formatCents(payout.amount_cents)" :subtitle="`${formatDate(payout.paid_at)} · ${payout.orders_count} order${payout.orders_count === 1 ? '' : 's'}${payout.fines_cents ? ` · ${formatCents(payout.fines_cents)} in fines` : ''}`" />
						</v-list>
						<v-card-text v-else class="text-medium-emphasis">
							No payouts yet.
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</template>
	</div>
</template>
