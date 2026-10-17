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
