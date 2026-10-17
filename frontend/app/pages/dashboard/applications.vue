<script setup lang="ts">
import { mdiEmailOutline } from '@mdi/js'
import { errorMessage } from '~/utils/api'
import { formatDateTime, timeAgo } from '~/utils/orders'
import type { BoosterApplication, Paginated, Wrapped } from '~/types/api'

definePageMeta({ layout: 'dashboard', middleware: ['auth', 'role'], roles: ['admin'] })
useHead({ title: 'Applications' })

const snackbar = useSnackbar()
const page = ref(1)
const reviewing = ref<number | null>(null)

const { data, status, error } = useAsyncData('admin-applications', () => useApi()<Paginated<BoosterApplication>>('/api/applications', {
	query: { page: page.value },
}), { watch: [page] })

const unread = computed(() => data.value?.data.filter(application => !application.reviewed_at).length ?? 0)

const details = (application: BoosterApplication) => [
	{ label: 'Email', value: application.email },
	{ label: 'Discord / Skype', value: application.discord_skype },
	{ label: 'Country', value: application.country },
	{ label: 'Current rank', value: application.rank },
	{ label: 'Games', value: application.games },
	{ label: 'Servers', value: application.servers },
	{ label: 'Languages', value: application.languages },
	{ label: 'Availability', value: application.hours },
]

function replyLink(application: BoosterApplication): string {
	const subject = encodeURIComponent('Your King Boosting booster application')

	return `mailto:${application.email}?subject=${subject}`
}

// Reviewed applications stay where they are until the next load, so the list doesn't jump under the cursor
async function markReviewed(application: BoosterApplication): Promise<void> {
	reviewing.value = application.id
	try {
		const response = await useApi()<Wrapped<BoosterApplication>>(`/api/applications/${application.id}/review`, { method: 'POST' })
		if (data.value) {
			data.value = { ...data.value, data: data.value.data.map(row => (row.id === application.id ? response.data : row)) }
		}
		snackbar.success(`${application.in_game_name}'s application is marked as reviewed.`)
	}
	catch (failure) {
		snackbar.error(errorMessage(failure))
	}
	finally {
		reviewing.value = null
	}
}
</script>

<template>
	<div>
		<h1 class="text-headline-small font-weight-bold mb-1">
			Applications
		</h1>
		<p class="text-body-medium text-medium-emphasis mb-6">
			Booster applications from the storefront, new ones first. Reply by email, then mark them as reviewed.
			<template v-if="unread">
				<strong>{{ unread }} new</strong> on this page.
			</template>
		</p>

		<v-alert v-if="error" type="error" variant="tonal" class="mb-4" text="The applications couldn't be loaded." />

		<v-expansion-panels v-if="data?.data.length" variant="accordion" data-testid="applications">
			<v-expansion-panel v-for="application in data.data" :key="application.id" :data-testid="`application-${application.id}`">
				<v-expansion-panel-title>
					<div class="d-flex flex-wrap align-center ga-2 w-100 pe-4">
						<span class="font-weight-medium">{{ application.in_game_name }}</span>
						<v-chip v-if="!application.reviewed_at" color="primary" size="x-small" variant="flat" data-testid="new">
							New
						</v-chip>
						<span class="text-body-medium text-medium-emphasis">{{ application.rank }} · {{ application.country }}</span>
						<v-spacer />
						<span class="text-body-small text-medium-emphasis">{{ timeAgo(application.created_at) }}</span>
					</div>
				</v-expansion-panel-title>
				<v-expansion-panel-text>
					<v-row dense class="mb-2">
						<v-col v-for="detail in details(application)" :key="detail.label" cols="12" sm="6" md="3">
							<div class="text-body-small text-medium-emphasis">
								{{ detail.label }}
							</div>
							<div class="text-body-medium text-break">
								{{ detail.value }}
							</div>
						</v-col>
					</v-row>
					<div class="text-body-small text-medium-emphasis">
						Why they want to boost
					</div>
					<p class="text-body-medium mb-4 motivation">
						{{ application.motivation }}
					</p>
					<div class="d-flex flex-wrap align-center ga-2">
						<v-btn :href="replyLink(application)" :prepend-icon="mdiEmailOutline" variant="tonal">
							Reply by email
						</v-btn>
						<v-btn v-if="!application.reviewed_at" variant="text" :loading="reviewing === application.id" data-testid="mark-reviewed" @click="markReviewed(application)">
							Mark as reviewed
						</v-btn>
						<span v-else class="text-body-small text-medium-emphasis">Reviewed {{ formatDateTime(application.reviewed_at) }}</span>
						<v-spacer />
						<span class="text-body-small text-medium-emphasis">Sent {{ formatDateTime(application.created_at) }}</span>
					</div>
				</v-expansion-panel-text>
			</v-expansion-panel>
		</v-expansion-panels>
		<v-card v-else-if="status !== 'pending' && !error" class="pa-8 text-center text-medium-emphasis">
			No applications yet. They arrive from the storefront's Become a booster page.
		</v-card>
		<v-pagination v-if="(data?.meta.last_page ?? 1) > 1" v-model="page" :length="data?.meta.last_page" class="mt-4" density="comfortable" />
	</div>
</template>

<style scoped>
.motivation {
	white-space: pre-line;
}
</style>
