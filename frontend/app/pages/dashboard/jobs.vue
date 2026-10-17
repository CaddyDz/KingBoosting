<script setup lang="ts">
import { mdiRefresh } from '@mdi/js'
import { errorMessage, statusOf } from '~/utils/api'
import { timeAgo } from '~/utils/orders'
import type { Order, Paginated, Wrapped } from '~/types/api'

definePageMeta({ layout: 'dashboard', middleware: ['auth', 'role'], roles: ['booster', 'moderator', 'admin'] })
useHead({ title: 'Jobs board' })

const { role } = useUser()
const { games, serviceLabel } = useCatalog()
const snackbar = useSnackbar()
const game = ref<string | null>(null)
const claiming = ref<number | null>(null)

const { data, status, refresh } = useAsyncData('jobs', () => useApi()<Paginated<Order>>('/api/jobs', {
	query: game.value ? { game: game.value } : {},
}), { watch: [game] })

// The board changes as other boosters claim: refresh whenever the tab comes back into view
useEventListener('visibilitychange', () => {
	if (document.visibilityState === 'visible') {
		refresh()
	}
})

async function claim(order: Order): Promise<void> {
	claiming.value = order.id
	try {
		const response = await useApi()<Wrapped<Order>>(`/api/orders/${order.id}/claim`, { method: 'POST' })
		snackbar.success('The order is yours. Message the customer within 5 minutes.')
		await navigateTo(`/dashboard/orders/${response.data.id}`)
	}
	catch (error) {
		snackbar.error(statusOf(error) === 409 ? 'Another booster claimed this order first.' : errorMessage(error))
		await refresh()
	}
	finally {
		claiming.value = null
	}
}
</script>

<template>
	<div>
		<div class="d-flex flex-wrap align-center ga-4 mb-6">
			<div>
				<h1 class="text-headline-small font-weight-bold">
					Jobs board
				</h1>
				<p class="text-body-medium text-medium-emphasis">
					{{ role === 'booster' ? 'Paid orders waiting for a booster, oldest first. Claim one you can start today.' : 'Paid orders waiting for a booster. Open one to assign it.' }}
				</p>
			</div>
			<v-spacer />
			<v-select v-model="game" :items="games" item-title="label" item-value="value" label="Game" clearable density="compact" hide-details style="max-width: 220px" />
			<v-btn :icon="mdiRefresh" variant="text" aria-label="Refresh" :loading="status === 'pending'" @click="refresh()" />
		</div>

		<v-row v-if="data?.data.length">
			<v-col v-for="job in data.data" :key="job.id" cols="12" md="6" xl="4">
				<v-card class="h-100 d-flex flex-column" data-testid="job">
					<v-card-item :title="job.title" :subtitle="`${job.game.label} · ${serviceLabel(job.service)} · ${job.server.toUpperCase()}`">
						<template #append>
							<span class="text-title-large font-weight-bold text-success">{{ formatCents(job.booster_share_cents ?? 0) }}</span>
						</template>
					</v-card-item>
					<v-card-text class="flex-grow-1">
						<div v-if="job.options.length" class="d-flex flex-wrap ga-1 mb-2">
							<v-chip v-for="option in job.options" :key="option" size="x-small" variant="outlined">
								{{ option }}
							</v-chip>
						</div>
						<div class="text-body-small text-medium-emphasis">
							Paid {{ job.paid_at ? timeAgo(job.paid_at) : '' }} · order #{{ job.id }}
						</div>
					</v-card-text>
					<v-card-actions>
						<v-btn :to="`/dashboard/orders/${job.id}`" variant="text">
							Details
						</v-btn>
						<v-spacer />
						<v-btn v-if="job.allowed_actions.includes('claim')" color="primary" variant="flat" :loading="claiming === job.id" data-testid="claim" @click="claim(job)">
							Claim
						</v-btn>
					</v-card-actions>
				</v-card>
			</v-col>
		</v-row>
		<v-card v-else-if="status !== 'pending'" class="pa-8 text-center text-medium-emphasis">
			No orders are waiting right now. New ones show up in your notifications.
		</v-card>
	</div>
</template>
