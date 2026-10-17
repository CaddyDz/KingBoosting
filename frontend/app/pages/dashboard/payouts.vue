<script setup lang="ts">
import { errorMessage, statusOf } from '~/utils/api'
import { formatDateTime } from '~/utils/orders'
import type { OwedBooster, Paginated, Payout, Wrapped } from '~/types/api'

definePageMeta({ layout: 'dashboard', middleware: ['auth', 'role'], roles: ['admin'] })
useHead({ title: 'Payouts' })

interface PayoutResult {
	data: Payout[]
	skipped: { booster_id: number, reason: string }[]
}

const snackbar = useSnackbar()
const page = ref(1)
const selected = ref<number[]>([])
const confirming = ref(false)
const paying = ref(false)

const { data: owed, error: owedError, refresh: refreshOwed } = useAsyncData('admin-payouts-owed', async () => (await useApi()<Wrapped<OwedBooster[]>>('/api/payouts/owed')).data)
const { data: history, status: historyStatus, refresh: refreshHistory } = useAsyncData('admin-payouts', () => useApi()<Paginated<Payout>>('/api/payouts', {
	query: { page: page.value },
}), { watch: [page] })

// A booster whose fines outweigh their shares can't be paid until they complete more orders
const payable = computed(() => owed.value?.filter(row => row.owed_cents > 0) ?? [])
const selectedRows = computed(() => payable.value.filter(row => selected.value.includes(row.booster.id)))
const selectedTotal = computed(() => selectedRows.value.reduce((sum, row) => sum + row.owed_cents, 0))
const allSelected = computed(() => payable.value.length > 0 && selectedRows.value.length === payable.value.length)

function toggleAll(): void {
	selected.value = allSelected.value ? [] : payable.value.map(row => row.booster.id)
}

const plural = (count: number, word: string) => `${count} ${word}${count === 1 ? '' : 's'}`

async function pay(): Promise<void> {
	paying.value = true
	try {
		const result = await useApi()<PayoutResult>('/api/payouts', { method: 'POST', body: { booster_ids: selectedRows.value.map(row => row.booster.id) } })
		const total = result.data.reduce((sum, payout) => sum + payout.amount_cents, 0)
		snackbar.success(`Recorded ${formatCents(total)} paid to ${plural(result.data.length, 'booster')}.${result.skipped.length ? ` ${plural(result.skipped.length, 'booster')} skipped: ${result.skipped[0]?.reason}` : ''}`)
	}
	catch (failure) {
		// Nobody could be paid: say why for the first one
		const skipped = (failure as { data?: Partial<PayoutResult> }).data?.skipped
		snackbar.error(statusOf(failure) === 409 && skipped?.length ? skipped[0]!.reason : errorMessage(failure))
	}
	finally {
		paying.value = false
		confirming.value = false
		selected.value = []
		await Promise.all([refreshOwed(), refreshHistory()])
	}
}
</script>

<template>
	<div>
		<h1 class="text-headline-small font-weight-bold mb-1">
			Payouts
		</h1>
		<p class="text-body-medium text-medium-emphasis mb-6">
			Mark boosters as paid, in batches. A payout settles every completed order they hold and deducts their unpaid fines. The money itself moves outside the app.
		</p>

		<v-alert v-if="owedError" type="error" variant="tonal" class="mb-4" text="What boosters are owed couldn't be loaded." />

		<v-card class="mb-6" title="Owed now">
			<template v-if="owed?.length">
				<v-table data-testid="owed-table">
					<thead>
						<tr>
							<th style="width: 56px">
								<v-checkbox-btn :model-value="allSelected" :indeterminate="selectedRows.length > 0 && !allSelected" :disabled="!payable.length" aria-label="Select everyone" @update:model-value="toggleAll" />
							</th>
							<th>Booster</th>
							<th class="text-end">
								Orders
							</th>
							<th class="text-end">
								Shares
							</th>
							<th class="text-end">
								Fines
							</th>
							<th class="text-end">
								To pay
							</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="row in owed" :key="row.booster.id" :data-testid="`owed-${row.booster.username}`">
							<td>
								<v-checkbox-btn v-model="selected" :value="row.booster.id" :disabled="row.owed_cents <= 0" :aria-label="`Pay ${row.booster.username}`" />
							</td>
							<td>
								<div class="font-weight-medium">
									{{ row.booster.username }}
								</div>
								<div v-if="row.owed_cents <= 0" class="text-body-small text-error">
									Fines are more than the shares: wait for more completed orders.
								</div>
							</td>
							<td class="text-end numeric">
								{{ row.orders_count }}
							</td>
							<td class="text-end numeric">
								{{ formatCents(row.share_cents) }}
							</td>
							<td class="text-end numeric" :class="{ 'text-error': row.fines_cents }">
								{{ row.fines_cents ? `−${formatCents(row.fines_cents)}` : '—' }}
							</td>
							<td class="text-end numeric font-weight-bold">
								{{ formatCents(row.owed_cents) }}
							</td>
						</tr>
					</tbody>
				</v-table>
				<v-divider />
				<v-card-actions class="px-4">
					<span class="text-body-medium text-medium-emphasis">
						{{ selectedRows.length ? `${plural(selectedRows.length, 'booster')} selected · ${formatCents(selectedTotal)}` : 'Select the boosters you have paid.' }}
					</span>
					<v-spacer />
					<v-btn color="primary" variant="flat" :disabled="!selectedRows.length" data-testid="pay" @click="confirming = true">
						Mark as paid
					</v-btn>
				</v-card-actions>
			</template>
			<v-card-text v-else-if="owed" class="text-medium-emphasis">
				Everyone is paid up. Completed orders show up here until they're paid out.
			</v-card-text>
		</v-card>

		<v-card title="History" :loading="historyStatus === 'pending'">
			<v-table v-if="history?.data.length" data-testid="payout-history">
				<thead>
					<tr>
						<th>Date</th>
						<th>Booster</th>
						<th class="text-end">
							Orders
						</th>
						<th class="text-end">
							Shares
						</th>
						<th class="text-end">
							Fines
						</th>
						<th class="text-end">
							Amount
						</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="payout in history.data" :key="payout.id">
						<td class="text-no-wrap">
							{{ formatDateTime(payout.paid_at) }}
						</td>
						<td>{{ payout.booster?.username }}</td>
						<td class="text-end numeric">
							{{ payout.orders_count }}
						</td>
						<td class="text-end numeric">
							{{ formatCents(payout.gross_cents) }}
						</td>
						<td class="text-end numeric">
							{{ payout.fines_cents ? `−${formatCents(payout.fines_cents)}` : '—' }}
						</td>
						<td class="text-end numeric font-weight-medium">
							{{ formatCents(payout.amount_cents) }}
						</td>
					</tr>
				</tbody>
			</v-table>
			<v-card-text v-else-if="historyStatus !== 'pending'" class="text-medium-emphasis">
				No payouts yet.
			</v-card-text>
		</v-card>
		<v-pagination v-if="(history?.meta.last_page ?? 1) > 1" v-model="page" :length="history?.meta.last_page" class="mt-4" density="comfortable" />

		<ConfirmDialog v-model="confirming" :title="`Mark ${plural(selectedRows.length, 'booster')} as paid, ${formatCents(selectedTotal)}?`" confirm-text="Mark as paid" :loading="paying" @confirm="pay">
			Their completed orders move to Paid out and their unpaid fines are deducted. Each booster gets a notification. This can't be undone.
		</ConfirmDialog>
	</div>
</template>

<style scoped>
.numeric {
	font-variant-numeric: tabular-nums;
}
</style>
