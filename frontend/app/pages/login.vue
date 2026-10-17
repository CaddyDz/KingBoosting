<script setup lang="ts">
definePageMeta({ layout: 'auth', middleware: ['guest'] })
useHead({ title: 'Log in' })

const route = useRoute()

async function signedIn(): Promise<void> {
	const redirect = typeof route.query.redirect === 'string' && route.query.redirect.startsWith('/') ? route.query.redirect : '/dashboard'
	await navigateTo(redirect)
}
</script>

<template>
	<v-card class="pa-6">
		<h1 class="text-headline-small font-weight-bold mb-1">
			Log in
		</h1>
		<p class="text-body-medium text-medium-emphasis mb-6">
			Demo accounts: admin@, moderator@, booster@ or customer@kingboosting.test, password <code>password</code>.
		</p>
		<v-alert v-if="route.query.reset" type="success" variant="tonal" class="mb-4" text="Your password was changed. Log in with the new one." />
		<AuthLoginForm @signed-in="signedIn" />
		<p class="text-body-medium text-center mt-6">
			New here?
			<NuxtLink to="/register" class="text-primary">
				Create an account
			</NuxtLink>
		</p>
	</v-card>
</template>
