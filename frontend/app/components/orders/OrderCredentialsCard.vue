<script setup lang="ts">
import { mdiEye, mdiEyeOff, mdiKeyVariant } from '@mdi/js'
import { errorMessage, validationErrors } from '~/utils/api'
import type { Order, Wrapped } from '~/types/api'

const props = defineProps<{ order: Order }>()
const emit = defineEmits<{ updated: [order: Order] }>()

const canEdit = computed(() => props.order.allowed_actions.includes('update-credentials'))
const missing = computed(() => !props.order.credentials?.login)
const reveal = ref(false)
const editing = ref(false)
const form = reactive({ login: '', password: '' })
const errors = ref<Record<string, string>>({})
const saving = ref(false)
const snackbar = useSnackbar()

function edit(): void {
	form.login = props.order.credentials?.login ?? ''
	form.password = ''
	errors.value = {}
	editing.value = true
}

async function save(): Promise<void> {
	saving.value = true
	try {
		const response = await useApi()<Wrapped<Order>>(`/api/orders/${props.order.id}/credentials`, { method: 'PUT', body: form })
		emit('updated', response.data)
		editing.value = false
		snackbar.success('Your login details were saved.')
	}
	catch (error) {
		errors.value = validationErrors(error)
		if (!Object.keys(errors.value).length) {
			snackbar.error(errorMessage(error))
		}
	}
	finally {
		saving.value = false
	}
}
</script>

<template>
	<v-card v-if="order.credentials || canEdit" data-testid="credentials">
		<v-card-item :prepend-icon="mdiKeyVariant" title="Account login" subtitle="Only you, your booster while they play, and an admin can see this" />
		<v-card-text>
			<v-alert v-if="missing && canEdit" type="warning" variant="tonal" density="compact" class="mb-3" text="Add the login your booster will play on." />
			<template v-if="order.credentials && !missing">
				<div class="d-flex align-center ga-2 mb-1">
					<span class="text-medium-emphasis" style="width: 90px">Login</span>
					<code>{{ order.credentials.login }}</code>
				</div>
				<div class="d-flex align-center ga-2">
					<span class="text-medium-emphasis" style="width: 90px">Password</span>
					<code data-testid="password">{{ reveal ? order.credentials.password : '••••••••' }}</code>
					<v-btn :icon="reveal ? mdiEyeOff : mdiEye" size="x-small" variant="text" :aria-label="reveal ? 'Hide the password' : 'Show the password'" @click="reveal = !reveal" />
				</div>
			</template>
		</v-card-text>
		<v-card-actions v-if="canEdit">
			<v-btn variant="tonal" @click="edit">
				{{ missing ? 'Add the login' : 'Change the login' }}
			</v-btn>
		</v-card-actions>

		<v-dialog v-model="editing" max-width="440">
			<v-card title="Account login">
				<v-card-text>
					<v-text-field v-model="form.login" label="Login" autocomplete="off" :error-messages="errors.login" />
					<v-text-field v-model="form.password" label="Password" type="password" autocomplete="new-password" :error-messages="errors.password" />
				</v-card-text>
				<v-card-actions>
					<v-spacer />
					<v-btn variant="text" @click="editing = false">
						Cancel
					</v-btn>
					<v-btn color="primary" :loading="saving" @click="save">
						Save
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</v-card>
</template>
