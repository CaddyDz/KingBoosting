<script setup lang="ts">
import { errorMessage, validationErrors } from '~/utils/api'
import type { User } from '~/types/api'

/**
 * Account creation. The register page and checkout both use it; they decide where to go next.
 */
const emit = defineEmits<{ registered: [user: User] }>()

const auth = useAuthStore()
const form = reactive({ username: '', email: '', password: '', password_confirmation: '' })
const errors = useFieldErrors(form)
const message = ref('')
const submitting = ref(false)

async function submit(): Promise<void> {
	submitting.value = true
	errors.value = {}
	message.value = ''
	try {
		emit('registered', await auth.register({ ...form }))
	}
	catch (error) {
		errors.value = validationErrors(error)
		if (Object.keys(errors.value).length === 0) {
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
		<v-text-field v-model="form.username" label="Username" autocomplete="username" :error-messages="errors.username" required />
		<v-text-field v-model="form.email" label="Email" type="email" autocomplete="email" :error-messages="errors.email" required />
		<v-text-field v-model="form.password" label="Password" type="password" autocomplete="new-password" hint="At least 8 characters" :error-messages="errors.password" required />
		<v-text-field v-model="form.password_confirmation" label="Confirm the password" type="password" autocomplete="new-password" required />
		<v-btn type="submit" color="primary" size="large" block :loading="submitting" data-testid="register-submit">
			Create my account
		</v-btn>
	</v-form>
</template>
