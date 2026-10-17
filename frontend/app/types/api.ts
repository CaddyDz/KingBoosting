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

export type OrderAction = 'claim' | 'assign' | 'pause' | 'resume' | 'release' | 'complete' | 'mark-paid-out' | 'update-credentials' | 'pay'

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

export interface ChatMessage {
	id: number
	order_id: number
	body: string
	system: boolean
	sender: { id: number, name: string, role: Role | 'support' } | null
	read_at: string | null
	created_at: string
}

export interface Fine {
	id: number
	booster?: { id: number, username: string }
	amount_cents: number
	reason: string
	settled: boolean
	payout_id: number | null
	created_at: string
}

export interface Payout {
	id: number
	booster?: { id: number, username: string }
	gross_cents: number
	fines_cents: number
	amount_cents: number
	orders_count: number
	paid_at: string
}

export interface EarningsSummary {
	orders_count: number
	share_cents: number
	fines_cents: number
	owed_cents: number
}

export interface Earnings {
	summary: EarningsSummary
	orders: { id: number, title: string, game: string, completed_at: string, share_cents: number }[]
	fines: Fine[]
	payouts: Payout[]
}

export interface AdminUser {
	id: number
	username: string
	email: string
	role: Role
	visible: boolean
	orders_count: number
	jobs_count: number
	created_at: string
}

/**
 * A booster application from the storefront's /apply page.
 */
export interface BoosterApplication {
	id: number
	in_game_name: string
	email: string
	country: string
	discord_skype: string
	rank: string
	languages: string
	games: string
	servers: string
	hours: string
	motivation: string
	reviewed_at: string | null
	created_at: string
}

export interface OwedBooster extends EarningsSummary {
	booster: { id: number, username: string }
}

export interface Metrics {
	revenue_per_week: { week: string, revenue_cents: number, orders: number }[]
	orders_by_status: Record<OrderStatus, number>
	average_completion_hours: number | null
}

export type PricingStrategy = 'division-ladder' | 'per-game' | 'per-win' | 'mastery' | 'unrated'

export interface CatalogRank {
	key: string
	name: string
	divisions: string[]
}

export interface CatalogOption {
	key: string
	label: string
	description: string
	percent: number
}

/**
 * GET /api/catalog/{service}: what the order builder needs to draw a service. Prices only come from quotes.
 */
export interface CatalogEntry {
	key: string
	game: Labelled
	label: string
	strategy: PricingStrategy
	ranks: CatalogRank[] | null
	lp: string[] | null
	mmr: string[] | null
	amount: { label: string, min: number, max: number, default: number } | null
	mastery: { max_level: number, tokens: Record<string, { min: number, max: number }> } | null
	champions: string[] | null
	servers: { key: string, label: string }[]
	options: CatalogOption[]
	defaults: Record<string, unknown>
}

export interface Quote {
	version: number
	game: string
	service: string
	title: string
	inputs: Record<string, unknown>
	lines: QuoteLine[]
	options: { key: string, label: string, percent: number }[]
	total_cents: number
	currency: string
}
