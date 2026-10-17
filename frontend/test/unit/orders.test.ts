import { describe, expect, it } from 'vitest'
import { statusColor, timeAgo } from '../../app/utils/orders'
import { dashboardHome } from '../../app/composables/useDashboardNav'

describe('timeAgo', () => {
	const now = new Date('2026-10-17T12:00:00Z')

	it('says how long ago something happened', () => {
		expect(timeAgo('2026-10-17T11:59:30Z', now)).toBe('just now')
		expect(timeAgo('2026-10-17T11:55:00Z', now)).toBe('5 minutes ago')
		expect(timeAgo('2026-10-17T09:00:00Z', now)).toBe('3 hours ago')
		expect(timeAgo('2026-10-16T12:00:00Z', now)).toBe('yesterday')
	})
})

describe('statusColor', () => {
	it('gives every status a colour', () => {
		expect(statusColor('pending')).toBe('info')
		expect(statusColor('completed')).toBe('success')
	})
})

describe('dashboardHome', () => {
	it('lands each role on its own screen', () => {
		expect(dashboardHome('admin')).toBe('/dashboard/overview')
		expect(dashboardHome('booster')).toBe('/dashboard/jobs')
		expect(dashboardHome('moderator')).toBe('/dashboard/orders')
		expect(dashboardHome('customer')).toBe('/dashboard/orders')
	})
})
