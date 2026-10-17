import type { Role } from '~/types/api'

/**
 * Pages for some roles only: `definePageMeta({ middleware: ['auth', 'role'], roles: ['admin'] })`.
 * This only hides screens; the API's policies decide what anyone can actually read or do.
 */
export default defineNuxtRouteMiddleware((to) => {
	const roles = to.meta.roles as Role[] | undefined
	const role = useAuthStore().role

	if (roles && (!role || !roles.includes(role))) {
		return navigateTo('/dashboard')
	}
})

declare module '#app' {
	interface PageMeta {
		roles?: Role[]
	}
}
