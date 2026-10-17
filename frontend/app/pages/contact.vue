<script setup lang="ts">
import { errorMessage, statusOf, validationErrors } from '~/utils/api'

useSeoMeta({ title: 'Contact', description: 'Questions about an order, a bug or the demo itself.' })

const CATEGORIES = [
	{ title: 'General question', value: 'question' },
	{ title: 'Order issue', value: 'order' },
	{ title: 'Bug report', value: 'bug' },
	{ title: 'Other', value: 'other' },
]

const form = reactive({ category: 'question', name: '', email: '', message: '' })
const errors = useFieldErrors(form)
const sending = ref(false)
const sent = ref<string | null>(null)
const failure = ref<string | null>(null)

// Signed-in visitors don't have to type who they are
onMounted(async () => {
	const user = await useAuthStore().loadIfSignedIn()
	if (user) {
		form.name ||= user.username
		form.email ||= user.email
	}
})

async function submit(): Promise<void> {
	sending.value = true
	errors.value = {}
	failure.value = null
	try {
		sent.value = (await useApi()<{ message: string }>('/api/contact', { method: 'POST', body: form })).message
	}
	catch (error) {
		errors.value = validationErrors(error)
		if (!Object.keys(errors.value).length) {
			failure.value = statusOf(error) === 429 ? 'You\'ve sent a few messages already. Please try again in an hour.' : errorMessage(error)
		}
	}
	finally {
		sending.value = false
	}
}
</script>

<template>
	<v-container class="py-12" style="max-width: 1000px">
		<h1 class="text-headline-large font-weight-bold mb-2">
			Contact
		</h1>
		<p class="text-body-large text-medium-emphasis mb-8" style="max-width: 640px">
			Questions about an order, a bug you found or the demo itself: write to the team here and the reply comes by email.
		</p>
		<v-row>
			<v-col cols="12" md="7">
				<v-card v-if="sent" class="pa-8 text-center" data-testid="contact-sent">
					<h2 class="text-title-large mb-2">
						Message sent
					</h2>
					<p class="text-body-medium text-medium-emphasis">
						{{ sent }}
					</p>
				</v-card>
				<v-card v-else class="pa-6">
					<v-form @submit.prevent="submit">
						<v-alert v-if="failure" type="error" variant="tonal" class="mb-4" :text="failure" />
						<v-select v-model="form.category" :items="CATEGORIES" label="What's it about?" :error-messages="errors.category" class="mb-2" />
						<v-row density="compact">
							<v-col cols="12" sm="6">
								<v-text-field v-model="form.name" label="Name" autocomplete="name" maxlength="64" :error-messages="errors.name" />
							</v-col>
							<v-col cols="12" sm="6">
								<v-text-field v-model="form.email" label="Email" type="email" autocomplete="email" :error-messages="errors.email" />
							</v-col>
						</v-row>
						<v-textarea v-model="form.message" label="Message" rows="5" counter="5000" maxlength="5000" :error-messages="errors.message" class="mt-2" />
						<v-btn type="submit" color="primary" variant="flat" size="large" class="mt-2" :loading="sending" data-testid="send-contact">
							Send message
						</v-btn>
					</v-form>
				</v-card>
			</v-col>
			<v-col cols="12" md="5">
				<v-card class="pa-5 mb-4" variant="flat">
					<h2 class="text-title-medium mb-1">
						Already have an order?
					</h2>
					<p class="text-body-medium text-medium-emphasis mb-3">
						The fastest answer is on the order page: its chat reaches your booster, and support reads along.
					</p>
					<v-btn to="/dashboard/orders" variant="text" class="px-0">
						Go to your orders
					</v-btn>
				</v-card>
				<v-card class="pa-5" variant="flat">
					<h2 class="text-title-medium mb-1">
						Want to boost?
					</h2>
					<p class="text-body-medium text-medium-emphasis mb-3">
						Applications have their own form, with the questions the team needs.
					</p>
					<v-btn to="/apply" variant="text" class="px-0">
						Become a booster
					</v-btn>
				</v-card>
			</v-col>
		</v-row>
	</v-container>
</template>
