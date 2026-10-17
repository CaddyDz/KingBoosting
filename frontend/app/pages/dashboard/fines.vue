<script setup lang="ts">
import { mdiDeleteOutline, mdiPencilOutline, mdiPlus } from '@mdi/js'
import { errorMessage, validationErrors } from '~/utils/api'
import { parseEuros } from '~/utils/money'
import { formatDate } from '~/utils/orders'
import type { Fine, Paginated } from '~/types/api'

definePageMeta({ layout: 'dashboard', middleware: ['auth', 'role'], roles: ['admin'] })
useHead({ title: 'Fines' })

const snackbar = useSnackbar()
const booster = ref<number | null>(null)
const page = ref(1)
watch(booster, () => (page.value = 1))

const { data, status, error, refresh } = useAsyncData('admin-fines', () => useApi()<Paginated<Fine>>('/api/fines', {
	query: { page: page.value, ...(booster.value ? { booster_id: booster.value } : {}) },
}), { watch: [booster, page] })

// One dialog issues new fines and edits unsettled ones; the booster can't change on an existing fine
const form = reactive({
	open: false,
	fine: null as Fine | null,
	boosterId: null as number | null,
	amount: '',
	reason: '',
	errors: {} as Record<string, string>,
	saving: false,
})

function openForm(fine: Fine | null = null): void {
	Object.assign(form, {
		open: true,
		fine,
		boosterId: fine?.booster?.id ?? null,
		amount: fine ? (fine.amount_cents / 100).toFixed(2) : '',
		reason: fine?.reason ?? '',
		errors: {},
	})
}

async function save(): Promise<void> {
	const amountCents = parseEuros(form.amount)
	form.errors = {
		...(amountCents === null || amountCents === 0 ? { amount_cents: 'Enter an amount in euros, like 5 or 7.50.' } : {}),
		...(!form.fine && !form.boosterId ? { booster_id: 'Choose a booster.' } : {}),
	}
	if (Object.keys(form.errors).length) {
		return
	}

	form.saving = true
	try {
		const body = { amount_cents: amountCents, reason: form.reason.trim() }
		await (form.fine
			? useApi()(`/api/fines/${form.fine.id}`, { method: 'PUT', body })
			: useApi()('/api/fines', { method: 'POST', body: { ...body, booster_id: form.boosterId } }))
		snackbar.success(form.fine ? 'The fine is updated.' : 'The fine is issued. It comes off the booster\'s next payout.')
		form.open = false
		await refresh()
	}
	catch (failure) {
		form.errors = validationErrors(failure)
		if (!Object.keys(form.errors).length) {
			snackbar.error(errorMessage(failure))
		}
	}
	finally {
		form.saving = false
	}
}

const withdrawing = ref<Fine | null>(null)
const confirmingWithdraw = ref(false)
const deleting = ref(false)

function askWithdraw(fine: Fine): void {
	withdrawing.value = fine
	confirmingWithdraw.value = true
}

async function withdraw(): Promise<void> {
	if (!withdrawing.value) {
		return
	}
	deleting.value = true
	try {
		await useApi()(`/api/fines/${withdrawing.value.id}`, { method: 'DELETE' })
		snackbar.success('The fine is withdrawn.')
		confirmingWithdraw.value = false
		await refresh()
	}
	catch (failure) {
		snackbar.error(errorMessage(failure))
	}
	finally {
		deleting.value = false
	}
}
</script>

<template>
	<div>
		<div class="d-flex flex-wrap align-center ga-4 mb-4">
			<div>
				<h1 class="text-headline-small font-weight-bold">
					Fines
				</h1>
				<p class="text-body-medium text-medium-emphasis">
					Fines come off a booster's next payout. Once deducted, they can't change.
				</p>
			</div>
			<v-spacer />
			<AdminBoosterPicker v-model="booster" label="Booster" clearable density="compact" hide-details style="min-width: 220px; max-width: 260px" />
			<v-btn color="primary" variant="flat" :prepend-icon="mdiPlus" data-testid="issue-fine" @click="openForm()">
				Issue a fine
			</v-btn>
		</div>

		<v-alert v-if="error" type="error" variant="tonal" class="mb-4" text="The fines couldn't be loaded." />

		<v-card :loading="status === 'pending'">
			<v-table v-if="data?.data.length" data-testid="fines-table">
				<thead>
					<tr>
						<th>Issued</th>
						<th>Booster</th>
						<th>Reason</th>
						<th class="text-end">
							Amount
						</th>
						<th>Status</th>
						<th />
					</tr>
				</thead>
				<tbody>
					<tr v-for="fine in data.data" :key="fine.id" :data-testid="`fine-${fine.id}`">
						<td class="text-no-wrap">
							{{ formatDate(fine.created_at) }}
						</td>
						<td>{{ fine.booster?.username }}</td>
						<td>{{ fine.reason }}</td>
						<td class="text-end text-no-wrap numeric">
							{{ formatCents(fine.amount_cents) }}
						</td>
						<td>
							<v-chip :color="fine.settled ? undefined : 'error'" size="small" variant="tonal">
								{{ fine.settled ? 'Deducted' : 'Unpaid' }}
							</v-chip>
						</td>
						<td class="text-end text-no-wrap">
							<template v-if="!fine.settled">
								<v-btn :icon="mdiPencilOutline" variant="text" size="small" aria-label="Edit" data-testid="edit-fine" @click="openForm(fine)" />
								<v-btn :icon="mdiDeleteOutline" variant="text" size="small" aria-label="Withdraw" data-testid="withdraw-fine" @click="askWithdraw(fine)" />
							</template>
						</td>
					</tr>
				</tbody>
			</v-table>
			<v-card-text v-else-if="status !== 'pending'" class="py-8 text-center text-medium-emphasis">
				No fines{{ booster ? ' for this booster' : '' }}.
			</v-card-text>
		</v-card>
		<v-pagination v-if="(data?.meta.last_page ?? 1) > 1" v-model="page" :length="data?.meta.last_page" class="mt-4" density="comfortable" />

		<v-dialog v-model="form.open" max-width="480" :persistent="form.saving">
			<v-card :title="form.fine ? `Edit ${form.fine.booster?.username}'s fine` : 'Issue a fine'">
				<v-form @submit.prevent="save">
					<v-card-text>
						<AdminBoosterPicker v-if="!form.fine" v-model="form.boosterId" :error-messages="form.errors.booster_id" class="mb-2" data-testid="fine-booster" />
						<v-text-field v-model="form.amount" label="Amount" prefix="€" inputmode="decimal" :error-messages="form.errors.amount_cents" class="mb-2" data-testid="fine-amount" />
						<v-textarea v-model="form.reason" label="Reason" hint="The booster sees this on their earnings page." persistent-hint rows="2" counter="255" maxlength="255" :error-messages="form.errors.reason" data-testid="fine-reason" />
					</v-card-text>
					<v-card-actions>
						<v-spacer />
						<v-btn variant="text" :disabled="form.saving" @click="form.open = false">
							Cancel
						</v-btn>
						<v-btn type="submit" color="primary" variant="flat" :loading="form.saving" data-testid="save-fine">
							{{ form.fine ? 'Save' : 'Issue fine' }}
						</v-btn>
					</v-card-actions>
				</v-form>
			</v-card>
		</v-dialog>

		<ConfirmDialog v-model="confirmingWithdraw" title="Withdraw this fine?" confirm-text="Withdraw" color="error" :loading="deleting" @confirm="withdraw">
			{{ withdrawing?.booster?.username }} won't be charged {{ withdrawing ? formatCents(withdrawing.amount_cents) : '' }} for “{{ withdrawing?.reason }}”.
		</ConfirmDialog>
	</div>
</template>

<style scoped>
.numeric {
	font-variant-numeric: tabular-nums;
}
</style>
