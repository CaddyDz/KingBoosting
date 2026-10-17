<script setup lang="ts">
import { mdiArrowLeft } from '@mdi/js'
import { statusOf } from '~/utils/api'
import type { Order, Wrapped } from '~/types/api'

definePageMeta({ layout: 'dashboard', middleware: ['auth'] })

const route = useRoute()
const id = Number(route.params.id)
const { data: order, error, refresh } = useAsyncData(`order-${id}`, async () => (await useApi()<Wrapped<Order>>(`/api/orders/${id}`)).data)
useHead(() => ({ title: order.value ? `Order #${order.value.id}` : 'Order' }))

function replace(updated: Order): void {
	order.value = updated
}

// Every action posts a system message in the order's chat: when one arrives, show the order as it is now
onMounted(() => {
	useEcho().private(`orders.${id}`).listen('.message.sent', (message: { system: boolean }) => {
		if (message.system) {
			refresh()
		}
	})
})
onBeforeUnmount(() => useEcho().leave(`orders.${id}`))
</script>

<template>
	<div>
		<v-btn to="/dashboard/orders" variant="text" :prepend-icon="mdiArrowLeft" class="mb-4 px-0">
			Orders
		</v-btn>

		<v-alert v-if="error" type="error" variant="tonal" :text="statusOf(error) === 403 ? 'This order isn\'t yours to see.' : statusOf(error) === 404 ? 'This order doesn\'t exist.' : 'The order couldn\'t be loaded.'" />

		<v-row v-else-if="order">
			<v-col cols="12" lg="8" class="d-flex flex-column ga-4">
				<OrdersOrderSummaryCard :order="order" />
				<OrdersOrderCredentialsCard :order="order" @updated="replace" />
				<OrdersOrderTimeline v-if="order.events" :events="order.events" />
			</v-col>
			<v-col cols="12" lg="4" class="d-flex flex-column ga-4">
				<OrdersOrderActionsCard :order="order" @updated="replace" @stale="refresh()" />
				<OrdersOrderPeopleCard :order="order" />
				<ChatOrderChat v-if="order.can_chat" :order="order" />
			</v-col>
		</v-row>

		<div v-else class="d-flex justify-center py-16">
			<v-progress-circular indeterminate color="primary" />
		</div>
	</div>
</template>
