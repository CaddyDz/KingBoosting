<script setup lang="ts">
import { errorMessage, validationErrors } from '~/utils/api'

definePageMeta({ layout: 'auth', middleware: ['guest'] })
useHead({ title: 'Reset your password' })

const email = ref('')
const errors = ref<Record<string, string>>({})
const sent = ref('')
const failure = ref('')
const submitting = ref(false)

async function submit(): Promise<void> {
	submitting.value = true
	errors.value = {}
	failure.value = ''
	try {
		sent.value = (await useApi()<{ message: string }>('/api/forgot-password', { method: 'POST', body: { email: email.value } })).message
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
		<h1 class="text-headline-small font-weight-bold mb-1">
			Reset your password
		</h1>
		<p class="text-body-medium text-medium-emphasis mb-6">
			We'll email you a link to choose a new one.
		</p>
		<v-alert v-if="sent" type="success" variant="tonal" class="mb-4" :text="sent" />
		<v-alert v-if="failure" type="error" variant="tonal" class="mb-4" :text="failure" />
		<v-form v-if="!sent" @submit.prevent="submit">
			<v-text-field v-model="email" label="Email" type="email" autocomplete="email" :error-messages="errors.email" required />
			<v-btn type="submit" color="primary" size="large" block :loading="submitting">
				Send the link
			</v-btn>
		</v-form>
		<p class="text-body-medium text-center mt-6">
			<NuxtLink to="/login" class="text-primary">
				Back to log in
			</NuxtLink>
		</p>
	</v-card>
</template>
