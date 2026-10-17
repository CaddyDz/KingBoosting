import { defineStore } from 'pinia'
import { statusOf } from '~/utils/api'
import type { Role, User, Wrapped } from '~/types/api'

/**
 * The signed-in user. Loaded once in the browser from /api/me; the session itself is Laravel's cookie.
 */
export const useAuthStore = defineStore('auth', () => {
	const user = ref<User | null>(null)
	const loaded = ref(false)

	const loggedIn = computed(() => user.value !== null)
	const role = computed<Role | null>(() => user.value?.role ?? null)
	const isStaff = computed(() => role.value === 'admin' || role.value === 'moderator')

	async function fetchUser(): Promise<User | null> {
		try {
			user.value = (await useApi()<Wrapped<User>>('/api/me')).data
		}
		catch (error) {
			if (statusOf(error) !== 401) {
				throw error
			}
			user.value = null
		}
		loaded.value = true

		return user.value
	}

	async function ensureLoaded(): Promise<User | null> {
		return loaded.value ? user.value : await fetchUser()
	}

	async function login(email: string, password: string, remember = false): Promise<User> {
		const response = await useApi()<Wrapped<User>>('/api/login', { method: 'POST', body: { email, password, remember } })
		user.value = response.data
		loaded.value = true

		return response.data
	}

	async function register(input: { username: string, email: string, password: string, password_confirmation: string }): Promise<User> {
		const response = await useApi()<Wrapped<User>>('/api/register', { method: 'POST', body: input })
		user.value = response.data
		loaded.value = true

		return response.data
	}

	async function logout(): Promise<void> {
		await useApi()('/api/logout', { method: 'POST' })
		useNuxtApp().$echo?.disconnect()
		user.value = null
	}

	return { user, loaded, loggedIn, role, isStaff, fetchUser, ensureLoaded, login, register, logout }
})
