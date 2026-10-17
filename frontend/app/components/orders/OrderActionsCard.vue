<script setup lang="ts">
import { ORDER_ACTIONS } from '~/utils/orderActions'
import { errorMessage, statusOf } from '~/utils/api'
import type { Order, OrderAction, Wrapped } from '~/types/api'

const props = defineProps<{ order: Order }>()
const emit = defineEmits<{ updated: [order: Order], stale: [] }>()
const snackbar = useSnackbar()

// Credentials have their own card; everything else the API allows gets a button
const actions = computed(() => props.order.allowed_actions
	.filter(action => action !== 'update-credentials' && ORDER_ACTIONS[action])
	.map(action => ({ action, ...ORDER_ACTIONS[action]! })))

const pending = ref<OrderAction | null>(null)
const busy = ref(false)
const boosterId = ref<number | null>(null)
const { data: boosters } = useAsyncData('assignable-boosters', async () => (await useApi()<{ data: { id: number, username: string }[] }>('/api/boosters')).data, {
	immediate: false,
	default: () => [],
})

async function ask(action: OrderAction): Promise<void> {
	pending.value = action
	if (action === 'assign' && !boosters.value.length) {
		boosters.value = (await useApi()<{ data: { id: number, username: string }[] }>('/api/boosters')).data
	}
}

async function run(): Promise<void> {
	const action = pending.value
	if (!action) {
		return
	}
	busy.value = true
	try {
		const response = await useApi()<Wrapped<Order>>(`/api/orders/${props.order.id}/${action}`, {
			method: 'POST',
			body: action === 'assign' ? { booster_id: boosterId.value } : undefined,
		})
		emit('updated', response.data)
		snackbar.success(ORDER_ACTIONS[action]!.done)
	}
	catch (error) {
		// 409: someone else changed the order first. Say why, then show the order as it is now.
		const status = statusOf(error)
		snackbar.error(status === 409
			? errorMessage(error)
			: status === 403 ? 'You can\'t do that to this order any more.' : errorMessage(error))
		if (status === 409 || status === 403) {
			emit('stale')
		}
	}
	finally {
		busy.value = false
		pending.value = null
	}
}
</script>

<template>
	<v-card v-if="actions.length" title="What you can do" data-testid="actions">
		<v-card-text class="d-flex flex-column ga-2">
			<v-btn v-for="item in actions" :key="item.action" :color="item.color" :prepend-icon="item.icon" variant="tonal" block :data-testid="`action-${item.action}`" @click="ask(item.action)">
				{{ item.label }}
			</v-btn>
		</v-card-text>

		<v-dialog :model-value="pending !== null" max-width="460" @update:model-value="pending = null">
			<v-card v-if="pending" :title="ORDER_ACTIONS[pending]!.label">
				<v-card-text>
					<p>{{ ORDER_ACTIONS[pending]!.confirm }}</p>
					<v-select v-if="pending === 'assign'" v-model="boosterId" :items="boosters" item-title="username" item-value="id" label="Booster" class="mt-4" />
				</v-card-text>
				<v-card-actions>
					<v-spacer />
					<v-btn variant="text" @click="pending = null">
						Cancel
					</v-btn>
					<v-btn :color="ORDER_ACTIONS[pending]!.color" :loading="busy" :disabled="pending === 'assign' && !boosterId" data-testid="confirm" @click="run">
						{{ ORDER_ACTIONS[pending]!.label }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</v-card>
</template>
