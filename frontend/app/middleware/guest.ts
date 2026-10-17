/**
 * Login and registration: someone already signed in goes straight to the dashboard.
 */
export default defineNuxtRouteMiddleware(async () => {
	if (await useAuthStore().ensureLoaded()) {
		return navigateTo('/dashboard')
	}
})
