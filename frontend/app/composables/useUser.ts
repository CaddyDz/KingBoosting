import { storeToRefs } from 'pinia'

/**
 * The signed-in user and their role, as refs.
 */
export function useUser() {
	const { user, loggedIn, role, isStaff } = storeToRefs(useAuthStore())

	return { user, loggedIn, role, isStaff }
}
