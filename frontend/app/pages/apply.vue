<script setup lang="ts">
import { errorMessage, statusOf, validationErrors } from '~/utils/api'

useSeoMeta({ title: 'Become a booster', description: 'Apply to boost on King Boosting.' })

const HOURS = ['Under 10 hours a week', '10 to 20 hours a week', '20 to 40 hours a week', 'More than 40 hours a week']

const { games } = useCatalog()

const form = reactive({
	in_game_name: '',
	email: '',
	country: '',
	discord_skype: '',
	rank: '',
	languages: '',
	games: [] as string[],
	servers: '',
	hours: null as string | null,
	motivation: '',
})
const errors = useFieldErrors(form)
const sending = ref(false)
const sent = ref<string | null>(null)
const failure = ref<string | null>(null)

async function submit(): Promise<void> {
	sending.value = true
	errors.value = {}
	failure.value = null
	try {
		const response = await useApi()<{ message: string }>('/api/applications', {
			method: 'POST',
			body: { ...form, games: form.games.join(', ') },
		})
		sent.value = response.message
	}
	catch (error) {
		errors.value = validationErrors(error)
		if (!Object.keys(errors.value).length) {
			failure.value = statusOf(error) === 429 ? 'You\'ve sent a few applications already. Please try again in an hour.' : errorMessage(error)
		}
	}
	finally {
		sending.value = false
	}
}
</script>

<template>
	<v-container class="py-12" style="max-width: 760px">
		<h1 class="text-headline-large font-weight-bold mb-2">
			Become a booster
		</h1>
		<p class="text-body-large text-medium-emphasis mb-8">
			Boosters claim paid orders from the jobs board, play them, and get a share of each one. Tell us about your games and your rank; the team reads every application and replies by email.
		</p>

		<v-card v-if="sent" class="pa-8 text-center" data-testid="application-sent">
			<h2 class="text-title-large mb-2">
				Application sent
			</h2>
			<p class="text-body-medium text-medium-emphasis mb-6">
				{{ sent }}
			</p>
			<v-btn to="/" variant="tonal">
				Back to the home page
			</v-btn>
		</v-card>

		<v-card v-else class="pa-6">
			<v-form @submit.prevent="submit">
				<v-alert v-if="failure" type="error" variant="tonal" class="mb-4" :text="failure" />
				<v-row density="compact">
					<v-col cols="12" sm="6">
						<v-text-field v-model="form.in_game_name" label="In-game name" :error-messages="errors.in_game_name" maxlength="64" />
					</v-col>
					<v-col cols="12" sm="6">
						<v-text-field v-model="form.email" label="Email" type="email" autocomplete="email" :error-messages="errors.email" />
					</v-col>
					<v-col cols="12" sm="6">
						<v-text-field v-model="form.country" label="Country" autocomplete="country-name" :error-messages="errors.country" maxlength="64" />
					</v-col>
					<v-col cols="12" sm="6">
						<v-text-field v-model="form.discord_skype" label="Discord or Skype" :error-messages="errors.discord_skype" maxlength="64" />
					</v-col>
					<v-col cols="12" sm="6">
						<v-select v-model="form.games" :items="games" item-title="label" item-value="label" label="Games" multiple chips :error-messages="errors.games" />
					</v-col>
					<v-col cols="12" sm="6">
						<v-text-field v-model="form.rank" label="Current rank" hint="Your best account, e.g. Master or Immortal 2" :error-messages="errors.rank" maxlength="64" />
					</v-col>
					<v-col cols="12" sm="6">
						<v-text-field v-model="form.servers" label="Servers" hint="e.g. EUW, EUNE" :error-messages="errors.servers" />
					</v-col>
					<v-col cols="12" sm="6">
						<v-text-field v-model="form.languages" label="Languages you speak" hint="e.g. English, French" :error-messages="errors.languages" />
					</v-col>
					<v-col cols="12">
						<v-select v-model="form.hours" :items="HOURS" label="Time you can give" :error-messages="errors.hours" />
					</v-col>
					<v-col cols="12">
						<v-textarea v-model="form.motivation" label="Why do you want to boost?" rows="4" counter="5000" maxlength="5000" hint="At least 25 characters. Experience, references, the roles you play…" :error-messages="errors.motivation" />
					</v-col>
				</v-row>
				<div class="d-flex flex-wrap align-center ga-4 mt-2">
					<v-btn type="submit" color="primary" variant="flat" size="large" :loading="sending" data-testid="send-application">
						Send application
					</v-btn>
					<span class="text-body-small text-medium-emphasis">This is a demo: use made-up details.</span>
				</div>
			</v-form>
		</v-card>
	</v-container>
</template>
