import { defineStore } from 'pinia'
import type { AppNotification } from '~/types/api'

/**
 * The bell: the latest notifications, the unread count, and new ones pushed over the user's private channel.
 */
export const useNotificationsStore = defineStore('notifications', () => {
	const items = ref<AppNotification[]>([])
	const unread = ref(0)
	let listening = false

	async function load(): Promise<void> {
		const response = await useApi()<{ data: AppNotification[], meta: { unread_count: number } }>('/api/notifications')
		items.value = response.data
		unread.value = response.meta.unread_count
	}

	function listen(userId: number): void {
		if (listening) {
			return
		}
		listening = true
		useEcho().private(`App.Models.User.${userId}`).notification((notification: Omit<AppNotification, 'read_at' | 'created_at'>) => {
			items.value = [{ ...notification, read_at: null, created_at: new Date().toISOString() }, ...items.value].slice(0, 20)
			unread.value++
		})
	}

	async function markRead(notification: AppNotification): Promise<void> {
		if (notification.read_at) {
			return
		}
		notification.read_at = new Date().toISOString()
		unread.value = Math.max(0, unread.value - 1)
		await useApi()(`/api/notifications/${notification.id}/read`, { method: 'POST' })
	}

	async function markAllRead(): Promise<void> {
		items.value.forEach(item => (item.read_at ??= new Date().toISOString()))
		unread.value = 0
		await useApi()('/api/notifications/read', { method: 'POST' })
	}

	return { items, unread, load, listen, markRead, markAllRead }
})
