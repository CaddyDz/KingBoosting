<script setup lang="ts">
import { errorMessage, statusOf, validationErrors } from '~/utils/api'

definePageMeta({ layout: 'auth', middleware: ['guest'] })
useHead({ title: 'Log in' })

const route = useRoute()
const auth = useAuthStore()
const form = reactive({ email: '', password: '', remember: false })
const errors = ref<Record<string, string>>({})
const message = ref('')
const submitting = ref(false)

async function submit(): Promise<void> {
	submitting.value = true
	errors.value = {}
	message.value = ''
	try {
		await auth.login(form.email, form.password, form.remember)
		const redirect = typeof route.query.redirect === 'string' && route.query.redirect.startsWith('/') ? route.query.redirect : '/dashboard'
		await navigateTo(redirect)
	}
	catch (error) {
		errors.value = validationErrors(error)
		if (statusOf(error) === 429) {
			message.value = 'Too many attempts. Wait a minute and try again.'
		}
		else if (Object.keys(errors.value).length === 0) {
			message.value = errorMessage(error)
		}
	}
	finally {
		submitting.value = false
	}
}
</script>

<template>
	<v-card class="pa-6">
		<h1 class="text-h5 font-weight-bold mb-1">
			Log in
		</h1>
		<p class="text-body-2 text-medium-emphasis mb-6">
			Demo accounts: admin@, moderator@, booster@ or customer@kingboosting.test, password <code>password</code>.
		</p>
		<v-alert v-if="message" type="error" variant="tonal" class="mb-4" :text="message" />
		<v-alert v-if="route.query.reset" type="success" variant="tonal" class="mb-4" text="Your password was changed. Log in with the new one." />
		<v-form @submit.prevent="submit">
			<v-text-field v-model="form.email" label="Email" type="email" autocomplete="username" :error-messages="errors.email" required />
			<v-text-field v-model="form.password" label="Password" type="password" autocomplete="current-password" :error-messages="errors.password" required />
			<div class="d-flex align-center justify-space-between mb-4">
				<v-checkbox v-model="form.remember" label="Remember me" density="compact" hide-details />
				<NuxtLink to="/forgot-password" class="text-body-2 text-primary">
					Forgot your password?
				</NuxtLink>
			</div>
			<v-btn type="submit" color="primary" size="large" block :loading="submitting">
				Log in
			</v-btn>
		</v-form>
		<p class="text-body-2 text-center mt-6">
			New here?
			<NuxtLink to="/register" class="text-primary">
				Create an account
			</NuxtLink>
		</p>
	</v-card>
</template>
