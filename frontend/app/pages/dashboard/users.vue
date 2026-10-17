<script setup lang="ts">
import { mdiBellPlusOutline, mdiMagnify } from '@mdi/js'
import { errorMessage } from '~/utils/api'
import { formatDate } from '~/utils/orders'
import type { AdminUser, Paginated, Role, Wrapped } from '~/types/api'

definePageMeta({ layout: 'dashboard', middleware: ['auth', 'role'], roles: ['admin'] })
useHead({ title: 'Users' })

const ROLES: { title: string, value: Role }[] = [
	{ title: 'Admin', value: 'admin' },
	{ title: 'Moderator', value: 'moderator' },
	{ title: 'Booster', value: 'booster' },
	{ title: 'Customer', value: 'customer' },
]
const roleTitle = (role: Role) => ROLES.find(item => item.value === role)?.title.toLowerCase() ?? role

const { user: me } = useUser()
const snackbar = useSnackbar()

const roleFilter = ref<Role | null>(null)
const search = ref('')
const searchQuery = ref('')
const page = ref(1)
let searchTimer: ReturnType<typeof setTimeout> | undefined
watch(search, (value) => {
	clearTimeout(searchTimer)
	searchTimer = setTimeout(() => {
		searchQuery.value = value ?? ''
		page.value = 1
	}, 300)
})
watch(roleFilter, () => (page.value = 1))

const { data, status, error } = useAsyncData('admin-users', () => useApi()<Paginated<AdminUser>>('/api/users', {
	query: {
		page: page.value,
		...(roleFilter.value ? { role: roleFilter.value } : {}),
		...(searchQuery.value ? { search: searchQuery.value } : {}),
	},
}), { watch: [roleFilter, searchQuery, page] })

// The page's data is a shallow ref: swap the whole page in so the row re-renders
function replace(user: AdminUser): void {
	if (data.value) {
		data.value = { ...data.value, data: data.value.data.map(row => (row.id === user.id ? user : row)) }
	}
}

async function update(user: AdminUser, changes: Partial<Pick<AdminUser, 'role' | 'visible'>>): Promise<AdminUser> {
	const response = await useApi()<Wrapped<AdminUser>>(`/api/users/${user.id}`, { method: 'PATCH', body: changes })
	replace(response.data)

	return response.data
}

// A role change asks first: it changes what the person can see and do straight away
const roleChange = ref<{ user: AdminUser, role: Role } | null>(null)
const confirmingRole = ref(false)
const savingRole = ref(false)

function askRole(user: AdminUser, role: Role): void {
	roleChange.value = { user, role }
	confirmingRole.value = true
}

async function changeRole(): Promise<void> {
	if (!roleChange.value) {
		return
	}
	const { user, role } = roleChange.value
	savingRole.value = true
	try {
		await update(user, { role })
		snackbar.success(`${user.username} is now a ${roleTitle(role)}.`)
		confirmingRole.value = false
	}
	catch (failure) {
		snackbar.error(errorMessage(failure))
	}
	finally {
		savingRole.value = false
	}
}

const togglingVisibility = ref<number | null>(null)

async function toggleVisibility(user: AdminUser, visible: boolean | null): Promise<void> {
	togglingVisibility.value = user.id
	try {
		await update(user, { visible: Boolean(visible) })
		snackbar.success(visible ? `Customers can choose ${user.username} at checkout again.` : `${user.username} is hidden from customers.`)
	}
	catch (failure) {
		snackbar.error(errorMessage(failure))
	}
	finally {
		togglingVisibility.value = null
	}
}

const notice = reactive({ open: false, user: null as AdminUser | null, message: '', sending: false })

function openNotice(user: AdminUser): void {
	Object.assign(notice, { open: true, user, message: '' })
}

async function sendNotice(): Promise<void> {
	if (!notice.user || !notice.message.trim()) {
		return
	}
	notice.sending = true
	try {
		await useApi()(`/api/users/${notice.user.id}/notices`, { method: 'POST', body: { message: notice.message.trim() } })
		snackbar.success(`Sent to ${notice.user.username}.`)
		notice.open = false
	}
	catch (failure) {
		snackbar.error(errorMessage(failure))
	}
	finally {
		notice.sending = false
	}
}

function activity(user: AdminUser): string {
	if (user.role === 'booster') {
		return `${user.jobs_count} ${user.jobs_count === 1 ? 'job' : 'jobs'}`
	}

	return user.role === 'customer' ? `${user.orders_count} ${user.orders_count === 1 ? 'order' : 'orders'}` : '—'
}
</script>

<template>
	<div>
		<div class="d-flex flex-wrap align-center ga-4 mb-4">
			<div>
				<h1 class="text-headline-small font-weight-bold">
					Users
				</h1>
				<p class="text-body-medium text-medium-emphasis">
					Change roles, hide boosters from customers, and send anyone a notice.
				</p>
			</div>
			<v-spacer />
			<v-select v-model="roleFilter" :items="ROLES" label="Role" clearable density="compact" hide-details style="max-width: 180px" />
			<v-text-field v-model="search" :prepend-inner-icon="mdiMagnify" placeholder="Username or email" density="compact" hide-details clearable style="max-width: 280px" />
		</div>

		<v-alert v-if="error" type="error" variant="tonal" class="mb-4" text="The users couldn't be loaded." />

		<v-card :loading="status === 'pending'">
			<v-table v-if="data?.data.length" data-testid="users-table">
				<thead>
					<tr>
						<th>User</th>
						<th style="width: 180px">
							Role
						</th>
						<th>Visible to customers</th>
						<th>Activity</th>
						<th>Joined</th>
						<th />
					</tr>
				</thead>
				<tbody>
					<tr v-for="user in data.data" :key="user.id" :data-testid="`user-${user.username}`">
						<td class="py-2">
							<div class="font-weight-medium">
								{{ user.username }}
							</div>
							<div class="text-body-small text-medium-emphasis">
								{{ user.email }}
							</div>
						</td>
						<td>
							<!-- Admins can't change their own role, so the last admin can't lock everyone out -->
							<span v-if="user.id === me?.id" data-testid="own-role">{{ ROLES.find(item => item.value === user.role)?.title }} <span class="text-medium-emphasis">(you)</span></span>
							<v-select
								v-else
								:model-value="user.role"
								:items="ROLES"
								density="compact"
								variant="plain"
								hide-details
								aria-label="Role"
								data-testid="role-select"
								@update:model-value="role => role !== user.role && askRole(user, role)"
							/>
						</td>
						<td>
							<v-switch
								v-if="user.role === 'booster'"
								:model-value="user.visible"
								:loading="togglingVisibility === user.id"
								color="primary"
								density="compact"
								hide-details
								:aria-label="`${user.username} visible to customers`"
								data-testid="visibility-switch"
								@update:model-value="visible => toggleVisibility(user, visible)"
							/>
							<span v-else class="text-medium-emphasis">—</span>
						</td>
						<td class="text-no-wrap">
							{{ activity(user) }}
						</td>
						<td class="text-no-wrap">
							{{ formatDate(user.created_at) }}
						</td>
						<td class="text-end">
							<v-btn :icon="mdiBellPlusOutline" variant="text" size="small" :aria-label="`Send ${user.username} a notice`" data-testid="notice" @click="openNotice(user)" />
						</td>
					</tr>
				</tbody>
			</v-table>
			<v-card-text v-else-if="status !== 'pending'" class="py-8 text-center text-medium-emphasis">
				Nobody matches.
			</v-card-text>
		</v-card>
		<v-pagination v-if="(data?.meta.last_page ?? 1) > 1" v-model="page" :length="data?.meta.last_page" class="mt-4" density="comfortable" />

		<ConfirmDialog v-model="confirmingRole" :title="`Make ${roleChange?.user.username} a ${roleChange ? roleTitle(roleChange.role) : ''}?`" confirm-text="Change role" :loading="savingRole" @confirm="changeRole">
			They get a {{ roleChange ? roleTitle(roleChange.role) : '' }}'s access from their next page load. Orders they hold stay where they are.
		</ConfirmDialog>

		<v-dialog v-model="notice.open" max-width="520">
			<v-card :title="`Notice to ${notice.user?.username}`" subtitle="It shows in their notifications and arrives by email.">
				<v-card-text>
					<v-textarea v-model="notice.message" label="Message" rows="4" counter="1000" maxlength="1000" autofocus data-testid="notice-message" />
				</v-card-text>
				<v-card-actions>
					<v-spacer />
					<v-btn variant="text" @click="notice.open = false">
						Cancel
					</v-btn>
					<v-btn color="primary" variant="flat" :loading="notice.sending" :disabled="!notice.message.trim()" data-testid="send-notice" @click="sendNotice">
						Send
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>
