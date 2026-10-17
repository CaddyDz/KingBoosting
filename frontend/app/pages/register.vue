<script setup lang="ts">
import { errorMessage, validationErrors } from '~/utils/api'

definePageMeta({ layout: 'auth', middleware: ['guest'] })
useHead({ title: 'Create an account' })

const auth = useAuthStore()
const form = reactive({ username: '', email: '', password: '', password_confirmation: '' })
const errors = ref<Record<string, string>>({})
const message = ref('')
const submitting = ref(false)

async function submit(): Promise<void> {
	submitting.value = true
	errors.value = {}
	message.value = ''
	try {
		await auth.register({ ...form })
		await navigateTo('/dashboard')
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
	<v-card class="pa-6">
		<h1 class="text-headline-small font-weight-bold mb-6">
			Create an account
		</h1>
		<v-alert v-if="message" type="error" variant="tonal" class="mb-4" :text="message" />
		<v-form @submit.prevent="submit">
			<v-text-field v-model="form.username" label="Username" autocomplete="username" :error-messages="errors.username" required />
			<v-text-field v-model="form.email" label="Email" type="email" autocomplete="email" :error-messages="errors.email" required />
			<v-text-field v-model="form.password" label="Password" type="password" autocomplete="new-password" hint="At least 8 characters" :error-messages="errors.password" required />
			<v-text-field v-model="form.password_confirmation" label="Confirm the password" type="password" autocomplete="new-password" required />
			<v-btn type="submit" color="primary" size="large" block :loading="submitting">
				Create my account
			</v-btn>
		</v-form>
		<p class="text-body-medium text-center mt-6">
			Already have an account?
			<NuxtLink to="/login" class="text-primary">
				Log in
			</NuxtLink>
		</p>
	</v-card>
</template>
