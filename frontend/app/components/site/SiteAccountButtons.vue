<script setup lang="ts">
/**
 * Log in and Sign up, or a way back to the dashboard. Storefront pages are rendered on the server and cached
 * for everyone, so this renders in the browser only; the layout asks the API who's signed in.
 */
const auth = useAuthStore()
</script>

<template>
	<ClientOnly>
		<v-btn v-if="auth.loggedIn" :to="dashboardHome(auth.role)" color="primary" variant="flat" data-testid="dashboard-link">
			Dashboard
		</v-btn>
		<div v-else class="d-flex ga-2">
			<v-btn to="/login" variant="text">
				Log in
			</v-btn>
			<v-btn to="/register" color="primary" variant="flat">
				Sign up
			</v-btn>
		</div>
		<template #fallback>
			<div class="d-flex ga-2">
				<v-btn to="/login" variant="text">
					Log in
				</v-btn>
				<v-btn to="/register" color="primary" variant="flat">
					Sign up
				</v-btn>
			</div>
		</template>
	</ClientOnly>
</template>
