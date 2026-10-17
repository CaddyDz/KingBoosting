import { mdiAccountGroupOutline, mdiBriefcaseOutline, mdiCashMultiple, mdiChartBoxOutline, mdiClipboardTextOutline, mdiEmailOutline, mdiGavel, mdiOrderBoolDescending, mdiShoppingOutline } from '@mdi/js'
import type { Role } from '~/types/api'

export interface NavItem {
	title: string
	to: string
	icon: string
}

/**
 * The dashboard's menu for each role. The API decides what each screen may load; this only decides
 * which screens are worth showing.
 */
const NAV: Record<Role, NavItem[]> = {
	customer: [
		{ title: 'My orders', to: '/dashboard/orders', icon: mdiOrderBoolDescending },
		{ title: 'Buy a boost', to: '/', icon: mdiShoppingOutline },
	],
	booster: [
		{ title: 'Jobs', to: '/dashboard/jobs', icon: mdiBriefcaseOutline },
		{ title: 'My orders', to: '/dashboard/orders', icon: mdiOrderBoolDescending },
		{ title: 'Earnings', to: '/dashboard/earnings', icon: mdiCashMultiple },
		{ title: 'Rules', to: '/dashboard/rules', icon: mdiClipboardTextOutline },
	],
	moderator: [
		{ title: 'Orders', to: '/dashboard/orders', icon: mdiOrderBoolDescending },
		{ title: 'Jobs board', to: '/dashboard/jobs', icon: mdiBriefcaseOutline },
		{ title: 'Rules', to: '/dashboard/rules', icon: mdiClipboardTextOutline },
	],
	admin: [
		{ title: 'Overview', to: '/dashboard/overview', icon: mdiChartBoxOutline },
		{ title: 'Orders', to: '/dashboard/orders', icon: mdiOrderBoolDescending },
		{ title: 'Jobs board', to: '/dashboard/jobs', icon: mdiBriefcaseOutline },
		{ title: 'Payouts', to: '/dashboard/payouts', icon: mdiCashMultiple },
		{ title: 'Fines', to: '/dashboard/fines', icon: mdiGavel },
		{ title: 'Users', to: '/dashboard/users', icon: mdiAccountGroupOutline },
		{ title: 'Applications', to: '/dashboard/applications', icon: mdiEmailOutline },
	],
}

export function useDashboardNav() {
	const { role } = useUser()

	return computed<NavItem[]>(() => (role.value ? NAV[role.value] : []))
}

/**
 * Where each role lands after logging in.
 */
export function dashboardHome(role: Role | null): string {
	return role === 'admin' ? '/dashboard/overview' : role === 'booster' ? '/dashboard/jobs' : '/dashboard/orders'
}
