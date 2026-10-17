<script setup lang="ts">
import { mdiMagnify } from '@mdi/js'
import { ORDER_STATUSES, formatDateTime } from '~/utils/orders'
import type { Order, Paginated } from '~/types/api'

definePageMeta({ layout: 'dashboard', middleware: ['auth'] })
useHead({ title: 'Orders' })

const route = useRoute()
const router = useRouter()
const { role, isStaff } = useUser()
const { games, serviceLabel } = useCatalog()

// The filters live in the URL, so a filtered view can be shared and survives a reload
const filters = reactive({
	status: ([] as string[]).concat((route.query.status as string | undefined)?.split(',').filter(Boolean) ?? []),
	game: (route.query.game as string | undefined) ?? null,
	service: (route.query.service as string | undefined) ?? null,
	search: (route.query.search as string | undefined) ?? '',
	sort: (route.query.sort as 'newest' | 'oldest' | undefined) ?? 'newest',
	page: Number(route.query.page ?? 1),
	perPage: Number(route.query.per_page ?? 15),
})
const search = ref(filters.search)
let searchTimer: ReturnType<typeof setTimeout> | undefined
watch(search, (value) => {
	clearTimeout(searchTimer)
	searchTimer = setTimeout(() => {
		filters.search = value
		filters.page = 1
	}, 300)
})

const query = computed(() => ({
	...(filters.status.length ? { status: filters.status.join(',') } : {}),
	...(filters.game ? { game: filters.game } : {}),
	...(filters.service ? { service: filters.service } : {}),
	...(filters.search ? { search: filters.search } : {}),
	...(filters.sort !== 'newest' ? { sort: filters.sort } : {}),
	...(filters.page > 1 ? { page: String(filters.page) } : {}),
	...(filters.perPage !== 15 ? { per_page: String(filters.perPage) } : {}),
}))
watch(query, value => router.replace({ query: value }))

const { data, status, error } = useAsyncData('dashboard-orders', () => useApi()<Paginated<Order>>('/api/orders', {
	query: { ...query.value, page: filters.page, per_page: filters.perPage },
}), { watch: [query] })

// Customers never see the payout step
const statusOptions = computed(() => ORDER_STATUSES.filter(status => role.value !== 'customer' || status.value !== 'paid_out'))

const serviceOptions = computed(() => games.value
	.filter(game => !filters.game || game.value === filters.game)
	.flatMap(game => game.services.map(service => ({ title: `${game.label} · ${service.label}`, value: service.key }))))

const headers = computed(() => [
	{ title: '#', key: 'id', width: 72 },
	{ title: 'Order', key: 'title' },
	{ title: 'Status', key: 'status' },
	...(isStaff.value ? [{ title: 'Customer', key: 'customer' }] : []),
	...(role.value !== 'booster' ? [{ title: 'Booster', key: 'booster' }] : []),
	// The API only sends the price to admins and the customer who paid, and the share to boosters
	...(role.value === 'admin' || role.value === 'customer' ? [{ title: 'Price', key: 'price_cents', align: 'end' as const }] : []),
	...(role.value === 'booster' ? [{ title: 'Your share', key: 'booster_share_cents', align: 'end' as const }] : []),
	{ title: 'Placed', key: 'created_at' },
])

function updateOptions(options: { page: number, itemsPerPage: number }): void {
	filters.page = options.page
	filters.perPage = options.itemsPerPage
}

function openOrder(_event: unknown, row: { item: Order }): void {
	navigateTo(`/dashboard/orders/${row.item.id}`)
}

const title = computed(() => (isStaff.value ? 'Orders' : 'My orders'))
</script>

<template>
	<div>
		<div class="d-flex flex-wrap align-center ga-4 mb-4">
			<h1 class="text-headline-small font-weight-bold">
				{{ title }}
			</h1>
			<v-spacer />
			<v-text-field v-model="search" :prepend-inner-icon="mdiMagnify" :placeholder="isStaff ? 'Order #, purchase, customer, booster' : 'Order #, purchase'" density="compact" hide-details clearable style="max-width: 340px" />
		</div>

		<v-row dense class="mb-2">
			<v-col cols="12" md="4">
				<v-select v-model="filters.status" :items="statusOptions" label="Status" multiple chips closable-chips clearable density="compact" hide-details @update:model-value="filters.page = 1" />
			</v-col>
			<v-col cols="6" md="3">
				<v-select v-model="filters.game" :items="games" item-title="label" item-value="value" label="Game" clearable density="compact" hide-details @update:model-value="filters.service = null; filters.page = 1" />
			</v-col>
			<v-col cols="6" md="3">
				<v-select v-model="filters.service" :items="serviceOptions" label="Service" clearable density="compact" hide-details @update:model-value="filters.page = 1" />
			</v-col>
			<v-col cols="12" md="2" class="d-flex align-center">
				<v-btn-toggle v-model="filters.sort" mandatory density="compact" variant="outlined" divided>
					<v-btn value="newest" size="small">
						Newest
					</v-btn>
					<v-btn value="oldest" size="small">
						Oldest
					</v-btn>
				</v-btn-toggle>
			</v-col>
		</v-row>

		<v-alert v-if="error" type="error" variant="tonal" class="mb-4" text="The orders couldn't be loaded." />

		<v-card>
			<v-data-table-server
				:headers="headers"
				:items="data?.data ?? []"
				:items-length="data?.meta.total ?? 0"
				:loading="status === 'pending'"
				:page="filters.page"
				:items-per-page="filters.perPage"
				:items-per-page-options="[15, 30, 50]"
				hover
				data-testid="orders-table"
				@update:options="updateOptions"
				@click:row="openOrder"
			>
				<template #[`item.title`]="{ item }">
					<div class="py-2">
						<div class="font-weight-medium">
							{{ item.title }}
						</div>
						<div class="text-body-small text-medium-emphasis">
							{{ item.game.label }} · {{ serviceLabel(item.service) }} · {{ item.server.toUpperCase() }}
						</div>
					</div>
				</template>
				<template #[`item.status`]="{ item }">
					<OrdersOrderStatusChip :status="item.status" />
				</template>
				<template #[`item.customer`]="{ item }">
					{{ item.customer?.username }}
				</template>
				<template #[`item.booster`]="{ item }">
					<span :class="{ 'text-medium-emphasis': !item.booster }">{{ item.booster?.username ?? 'Not claimed yet' }}</span>
				</template>
				<template #[`item.price_cents`]="{ item }">
					{{ item.price_cents === undefined ? '' : formatCents(item.price_cents) }}
				</template>
				<template #[`item.booster_share_cents`]="{ item }">
					{{ item.booster_share_cents === undefined ? '' : formatCents(item.booster_share_cents) }}
				</template>
				<template #[`item.created_at`]="{ item }">
					<span class="text-no-wrap">{{ formatDateTime(item.created_at) }}</span>
				</template>
				<template #no-data>
					<div class="py-8 text-medium-emphasis">
						No orders match these filters.
					</div>
				</template>
			</v-data-table-server>
		</v-card>
	</div>
</template>
