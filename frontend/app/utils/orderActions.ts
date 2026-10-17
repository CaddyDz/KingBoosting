import { mdiAccountArrowRightOutline, mdiCashCheck, mdiCheckDecagramOutline, mdiHandBackRightOutline, mdiPause, mdiPlay, mdiSwordCross } from '@mdi/js'
import type { OrderAction } from '~/types/api'

export interface ActionInfo {
	label: string
	icon: string
	color: string
	/** What the confirmation dialog asks */
	confirm: string
	/** What the snackbar says afterwards */
	done: string
}

/**
 * The buttons on the order page. Which ones show comes from the API's `allowed_actions`.
 */
export const ORDER_ACTIONS: Partial<Record<OrderAction, ActionInfo>> = {
	'claim': { label: 'Claim this order', icon: mdiSwordCross, color: 'primary', confirm: 'Claim this order? You\'ll get the customer\'s login and should message them within 5 minutes.', done: 'The order is yours.' },
	'assign': { label: 'Assign to a booster', icon: mdiAccountArrowRightOutline, color: 'primary', confirm: 'Give this order to a booster.', done: 'The order was assigned.' },
	'pause': { label: 'Pause', icon: mdiPause, color: 'warning', confirm: 'Pause this order? Nobody plays on the account until it\'s resumed.', done: 'The order is paused.' },
	'resume': { label: 'Resume', icon: mdiPlay, color: 'success', confirm: 'Resume this order?', done: 'The order is back in progress.' },
	'release': { label: 'Release', icon: mdiHandBackRightOutline, color: 'error', confirm: 'Release this order? It goes back on the jobs board for another booster.', done: 'The order is back on the jobs board.' },
	'complete': { label: 'Mark as completed', icon: mdiCheckDecagramOutline, color: 'success', confirm: 'Is the order done? The customer will be told it\'s complete.', done: 'Marked as completed.' },
	'mark-paid-out': { label: 'Pay out this order', icon: mdiCashCheck, color: 'primary', confirm: 'Record the booster\'s share of this order as paid?', done: 'The booster\'s share was paid out.' },
}

/**
 * One line per timeline event.
 */
export function eventText(type: string, actor: string): string {
	const texts: Record<string, string> = {
		created: 'Order placed',
		payment_succeeded: 'Payment received',
		payment_failed: 'Payment declined',
		claimed: `Claimed by ${actor}`,
		assigned: `Assigned by ${actor}`,
		paused: `Paused by ${actor}`,
		resumed: `Resumed by ${actor}`,
		released: `Released by ${actor}`,
		completed: `Completed by ${actor}`,
		paid_out: 'Booster paid out',
		credentials_updated: 'Login details updated',
	}

	return texts[type] ?? type
}
