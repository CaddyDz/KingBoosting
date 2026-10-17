<script setup lang="ts">
import { errorMessage, validationErrors } from '~/utils/api'

definePageMeta({ layout: 'auth' })
useHead({ title: 'Choose a new password' })

const route = useRoute()
const form = reactive({
	token: String(route.query.token ?? ''),
	email: String(route.query.email ?? ''),
	password: '',
	password_confirmation: '',
})
const errors = ref<Record<string, string>>({})
const failure = ref('')
const submitting = ref(false)

async function submit(): Promise<void> {
	submitting.value = true
	errors.value = {}
	failure.value = ''
	try {
		await useApi()('/api/reset-password', { method: 'POST', body: form })
		await navigateTo({ path: '/login', query: { reset: '1' } })
	}
	catch (error) {
		errors.value = validationErrors(error)
		failure.value = Object.keys(errors.value).length ? '' : errorMessage(error)
	}
	finally {
		submitting.value = false
	}
}
</script>

<template>
	<v-card class="pa-6">
		<h1 class="text-headline-small font-weight-bold mb-6">
			Choose a new password
		</h1>
		<v-alert v-if="failure" type="error" variant="tonal" class="mb-4" :text="failure" />
		<v-form @submit.prevent="submit">
			<v-text-field v-model="form.email" label="Email" type="email" autocomplete="username" :error-messages="errors.email" required />
			<v-text-field v-model="form.password" label="New password" type="password" autocomplete="new-password" :error-messages="errors.password" required />
			<v-text-field v-model="form.password_confirmation" label="Confirm the new password" type="password" autocomplete="new-password" required />
			<v-btn type="submit" color="primary" size="large" block :loading="submitting">
				Save the new password
			</v-btn>
		</v-form>
	</v-card>
</template>
