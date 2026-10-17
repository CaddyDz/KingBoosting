<script setup lang="ts">
import type { Order } from '~/types/api'

defineProps<{ order: Order }>()
const { role } = useUser()

function initials(name: string): string {
	return name.slice(0, 2).toUpperCase()
}
</script>

<template>
	<v-card>
		<v-list lines="two">
			<v-list-item v-if="order.booster" :title="order.booster.username" :subtitle="role === 'customer' ? 'Your booster' : 'Booster'">
				<template #prepend>
					<v-avatar color="primary">
						{{ initials(order.booster.username) }}
					</v-avatar>
				</template>
			</v-list-item>
			<v-list-item v-else title="No booster yet" subtitle="The order is on the jobs board">
				<template #prepend>
					<v-avatar color="surface-variant">
						?
					</v-avatar>
				</template>
			</v-list-item>
			<v-list-item v-if="order.customer && role !== 'customer'" :title="order.customer.username" subtitle="Customer">
				<template #prepend>
					<v-avatar color="secondary">
						{{ initials(order.customer.username) }}
					</v-avatar>
				</template>
			</v-list-item>
		</v-list>
	</v-card>
</template>
