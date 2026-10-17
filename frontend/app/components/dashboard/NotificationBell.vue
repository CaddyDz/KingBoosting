<script setup lang="ts">
import { mdiBellOutline } from '@mdi/js'
import { timeAgo } from '~/utils/orders'
import type { AppNotification } from '~/types/api'

const notifications = useNotificationsStore()
const { user } = useUser()

onMounted(async () => {
	await notifications.load()
	if (user.value) {
		notifications.listen(user.value.id)
	}
})

async function open(notification: AppNotification): Promise<void> {
	await notifications.markRead(notification)
	if (notification.order_id) {
		await navigateTo(`/dashboard/orders/${notification.order_id}`)
	}
	else if (notification.url) {
		await navigateTo(new URL(notification.url, window.location.origin).pathname)
	}
}
</script>

<template>
	<v-menu location="bottom end" :close-on-content-click="false" max-width="380">
		<template #activator="{ props }">
			<v-btn v-bind="props" icon aria-label="Notifications">
				<v-badge :model-value="notifications.unread > 0" :content="notifications.unread" color="error">
					<v-icon :icon="mdiBellOutline" />
				</v-badge>
			</v-btn>
		</template>
		<v-card min-width="320">
			<v-card-title class="d-flex align-center">
				Notifications
				<v-spacer />
				<v-btn v-if="notifications.unread" variant="text" size="small" @click="notifications.markAllRead()">
					Mark all read
				</v-btn>
			</v-card-title>
			<v-divider />
			<v-list v-if="notifications.items.length" lines="three" density="compact" max-height="420" class="overflow-y-auto">
				<v-list-item v-for="item in notifications.items" :key="item.id" :class="{ 'bg-surface-variant': !item.read_at }" @click="open(item)">
					<v-list-item-title class="font-weight-medium">
						{{ item.title }}
					</v-list-item-title>
					<v-list-item-subtitle>{{ item.body }}</v-list-item-subtitle>
					<template #append>
						<span class="text-body-small text-medium-emphasis">{{ timeAgo(item.created_at) }}</span>
					</template>
				</v-list-item>
			</v-list>
			<v-card-text v-else class="text-medium-emphasis">
				Nothing new.
			</v-card-text>
		</v-card>
	</v-menu>
</template>
