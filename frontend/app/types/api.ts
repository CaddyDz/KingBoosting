export type Role = 'admin' | 'moderator' | 'booster' | 'customer'

export interface User {
	id: number
	username: string
	email: string
	role: Role
	visible?: boolean
	created_at: string
}

/** Laravel wraps single resources in `data` */
export interface Wrapped<T> {
	data: T
}

export interface Paginated<T> {
	data: T[]
	meta: {
		current_page: number
		last_page: number
		per_page: number
		total: number
	}
}

export type OrderStatus = 'awaiting_payment' | 'pending' | 'in_progress' | 'paused' | 'completed' | 'paid_out'

export type OrderAction = 'claim' | 'assign' | 'pause' | 'resume' | 'release' | 'complete' | 'mark-paid-out' | 'update-credentials'

export interface Labelled<T extends string = string> {
	value: T
	label: string
}

export interface OrderEvent {
	id: number
	type: string
	from_status: OrderStatus | null
	to_status: OrderStatus | null
	actor: string
	created_at: string
}

export interface QuoteLine {
	label: string
	cents: number
}

/**
 * An order as the API shows it to the signed-in user: fields they may not see are simply absent.
 */
export interface Order {
	id: number
	title: string
	game: Labelled
	service: string
	server: string
	status: Labelled<OrderStatus>
	account_name: string
	options: string[]
	comment: string | null
	customer?: { id: number, username: string }
	booster: { id: number, username: string } | null
	price_cents?: number
	booster_share_pct?: number
	booster_share_cents?: number
	quote?: { lines: QuoteLine[], options: { key: string, label: string, percent: number }[], total_cents: number }
	credentials?: { login: string | null, password: string | null }
	events?: OrderEvent[]
	allowed_actions: OrderAction[]
	can_chat: boolean
	created_at: string
	paid_at: string | null
	claimed_at: string | null
	completed_at: string | null
	paid_out_at?: string | null
}

export interface AppNotification {
	id: string
	title: string
	body: string
	order_id: number | null
	url: string | null
	read_at: string | null
	created_at: string
}

export interface CatalogGame {
	value: string
	label: string
	services: { key: string, label: string }[]
}
