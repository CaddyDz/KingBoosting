<script setup lang="ts">
import { errorMessage, statusOf, validationErrors } from '~/utils/api'
import type { User } from '~/types/api'

/**
 * Email and password sign-in. The login page and checkout both use it; they decide where to go next.
 */
const emit = defineEmits<{ signedIn: [user: User] }>()

const auth = useAuthStore()
const form = reactive({ email: '', password: '', remember: false })
const errors = useFieldErrors(form)
const message = ref('')
const submitting = ref(false)

async function submit(): Promise<void> {
	submitting.value = true
	errors.value = {}
	message.value = ''
	try {
		emit('signedIn', await auth.login(form.email, form.password, form.remember))
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
	<v-form @submit.prevent="submit">
		<v-alert v-if="message" type="error" variant="tonal" class="mb-4" :text="message" />
		<v-text-field v-model="form.email" label="Email" type="email" autocomplete="username" :error-messages="errors.email" required />
		<v-text-field v-model="form.password" label="Password" type="password" autocomplete="current-password" :error-messages="errors.password" required />
		<div class="d-flex align-center justify-space-between mb-4">
			<v-checkbox v-model="form.remember" label="Remember me" density="compact" hide-details />
			<NuxtLink to="/forgot-password" class="text-body-medium text-primary">
				Forgot your password?
			</NuxtLink>
		</div>
		<v-btn type="submit" color="primary" size="large" block :loading="submitting" data-testid="login-submit">
			Log in
		</v-btn>
	</v-form>
</template>
