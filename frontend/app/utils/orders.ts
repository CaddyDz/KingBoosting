import type { OrderStatus } from '~/types/api'

export const ORDER_STATUSES: { value: OrderStatus, title: string, color: string }[] = [
	{ value: 'awaiting_payment', title: 'Awaiting payment', color: 'grey' },
	{ value: 'pending', title: 'Waiting for a booster', color: 'info' },
	{ value: 'in_progress', title: 'In progress', color: 'warning' },
	{ value: 'paused', title: 'Paused', color: 'blue-grey-lighten-2' },
	{ value: 'completed', title: 'Completed', color: 'success' },
	{ value: 'paid_out', title: 'Paid out', color: 'primary' },
]

export function statusColor(status: OrderStatus): string {
	return ORDER_STATUSES.find(item => item.value === status)?.color ?? 'grey'
}

const dateTime = new Intl.DateTimeFormat('en-GB', { dateStyle: 'medium', timeStyle: 'short' })
const date = new Intl.DateTimeFormat('en-GB', { dateStyle: 'medium' })

export function formatDateTime(value: string | null | undefined): string {
	return value ? dateTime.format(new Date(value)) : '—'
}

export function formatDate(value: string | null | undefined): string {
	return value ? date.format(new Date(value)) : '—'
}

/**
 * "3 hours ago", for timelines and notifications.
 */
export function timeAgo(value: string, now: Date = new Date()): string {
	const seconds = Math.round((now.getTime() - new Date(value).getTime()) / 1000)
	const units: [Intl.RelativeTimeFormatUnit, number][] = [['day', 86400], ['hour', 3600], ['minute', 60]]
	const format = new Intl.RelativeTimeFormat('en', { numeric: 'auto' })

	for (const [unit, size] of units) {
		if (Math.abs(seconds) >= size) {
			return format.format(-Math.round(seconds / size), unit)
		}
	}

	return 'just now'
}
