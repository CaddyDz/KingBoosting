/**
 * Signed-in pages: send guests to the login page, then back here.
 */
export default defineNuxtRouteMiddleware(async (to) => {
	const user = await useAuthStore().ensureLoaded()

	if (!user) {
		return navigateTo({ path: '/login', query: { redirect: to.fullPath } })
	}
})
