<script setup lang="ts">
import { mdiArrowLeft } from '@mdi/js'
import { errorMessage, statusOf, validationErrors } from '~/utils/api'
import type { Order, Wrapped } from '~/types/api'

useHead({ title: 'Checkout' })

const order = useOrderStore()
const auth = useAuthStore()
const snackbar = useSnackbar()

const loading = ref(true)
const tab = ref<'login' | 'register'>('login')
const details = reactive({ account_name: '', account_login: '', account_password: '', comment: '', booster_id: null as number | null })
const errors = useFieldErrors(details)
const failure = ref<string | null>(null)
const placing = ref(false)

const { data: boosters } = useAsyncData('checkout-boosters', async () => (await useApi()<{ data: { id: number, username: string }[] }>('/api/boosters')).data, { default: () => [] })
const boosterChoices = computed(() => [{ title: 'The first booster free', value: null }, ...boosters.value.map(booster => ({ title: booster.username, value: booster.id as number | null }))])

const isCustomer = computed(() => auth.user?.role === 'customer')
const serverLabel = computed(() => order.entry?.servers.find(server => server.key === order.server)?.label ?? '—')

onMounted(async () => {
	const [found] = await Promise.all([order.restore(), auth.loadIfSignedIn()])
	loading.value = false
	if (found) {
		// A fresh price: the one shown in the builder may be minutes old
		order.requestQuote(0)
	}
})

async function logout(): Promise<void> {
	await auth.logout()
}

async function placeOrder(): Promise<void> {
	if (!order.entry || !order.input) {
		return
	}
	placing.value = true
	failure.value = null
	try {
		const response = await useApi()<Wrapped<Order> & { client_secret: string }>('/api/orders', {
			method: 'POST',
			body: {
				...order.input,
				service: order.entry.key,
				server: order.server,
				account_name: details.account_name,
				account_login: details.account_login || null,
				account_password: details.account_password || null,
				comment: details.comment || null,
				booster_id: details.booster_id,
			},
		})
		order.clear()
		await navigateTo(`/checkout/${response.data.id}`)
	}
	catch (error) {
		errors.value = validationErrors(error)
		const status = statusOf(error)
		if (status === 503) {
			failure.value = 'Your order is saved, but payments aren\'t available on this server right now. You can pay for it later from your orders.'
		}
		else if (status === 429) {
			failure.value = 'That was a lot of orders in a short time. Wait a minute and try again.'
		}
		else if (!['account_name', 'account_login', 'account_password', 'comment', 'booster_id'].some(field => field in errors.value)) {
			// Anything else in a 422 is about the order itself, e.g. a rank that's no longer valid
			failure.value = Object.values(errors.value)[0] ?? errorMessage(error)
		}
		if (status === 503) {
			snackbar.info('Order saved without payment.')
		}
	}
	finally {
		placing.value = false
	}
}
</script>

<template>
	<v-container class="py-10" style="max-width: 1100px">
		<h1 class="text-headline-large font-weight-bold mb-6">
			Checkout
		</h1>

		<div v-if="loading" class="d-flex justify-center py-16">
			<v-progress-circular indeterminate color="primary" />
		</div>

		<v-card v-else-if="!order.entry" class="pa-8 text-center" data-testid="empty-checkout">
			<h2 class="text-title-large mb-2">
				Nothing to check out yet
			</h2>
			<p class="text-body-medium text-medium-emphasis mb-6">
				Build an order first: pick a game and a boost.
			</p>
			<v-btn to="/#games" color="primary" variant="flat">
				Choose a boost
			</v-btn>
		</v-card>

		<v-row v-else>
			<v-col cols="12" md="7" class="d-flex flex-column ga-4">
				<v-card class="pa-6">
					<h2 class="text-title-large mb-4">
						1. Your account
					</h2>
					<div v-if="isCustomer" class="d-flex flex-wrap align-center ga-2" data-testid="signed-in">
						<span class="text-body-large">Signed in as <strong>{{ auth.user?.username }}</strong></span>
						<span class="text-body-medium text-medium-emphasis">({{ auth.user?.email }})</span>
						<v-spacer />
						<v-btn variant="text" size="small" @click="logout">
							Not you?
						</v-btn>
					</div>
					<v-alert v-else-if="auth.user" type="warning" variant="tonal" data-testid="not-customer">
						You're signed in as a {{ auth.user.role }}. Only customer accounts can buy boosts.
						<template #append>
							<v-btn variant="text" @click="logout">
								Log out
							</v-btn>
						</template>
					</v-alert>
					<template v-else>
						<p class="text-body-medium text-medium-emphasis mb-4">
							Your orders, chat and notifications live in your account. Demo customer: customer@kingboosting.test, password <code>password</code>.
						</p>
						<v-tabs v-model="tab" class="mb-4" color="primary">
							<v-tab value="login">
								Log in
							</v-tab>
							<v-tab value="register">
								Create an account
							</v-tab>
						</v-tabs>
						<AuthLoginForm v-if="tab === 'login'" @signed-in="snackbar.success('Signed in.')" />
						<AuthRegisterForm v-else @registered="snackbar.success('Welcome! Your account is ready.')" />
					</template>
				</v-card>

				<v-card class="pa-6" :disabled="!isCustomer">
					<h2 class="text-title-large mb-1">
						2. Your game account
					</h2>
					<p class="text-body-medium text-medium-emphasis mb-4">
						Use made-up details: this is a demo. Only the booster playing your order sees the login, and only while they play.
					</p>
					<!-- Disabled for guests and staff: the card only greys out, the form also blocks the keyboard -->
					<v-form :disabled="!isCustomer" @submit.prevent="placeOrder">
						<v-text-field v-model="details.account_name" label="In-game name" maxlength="64" :error-messages="errors.account_name" data-testid="account-name" />
						<v-row density="compact">
							<v-col cols="12" sm="6">
								<v-text-field v-model="details.account_login" label="Account login" autocomplete="off" hint="Optional: you can add it later" persistent-hint :error-messages="errors.account_login" />
							</v-col>
							<v-col cols="12" sm="6">
								<v-text-field v-model="details.account_password" label="Account password" type="password" autocomplete="new-password" :error-messages="errors.account_password" />
							</v-col>
						</v-row>
						<v-select v-model="details.booster_id" :items="boosterChoices" label="Booster" class="mt-4" :error-messages="errors.booster_id" data-testid="booster" />
						<v-textarea v-model="details.comment" label="Anything your booster should know?" rows="2" counter="1000" maxlength="1000" :error-messages="errors.comment" />
						<v-alert v-if="failure" type="error" variant="tonal" class="mb-4" :text="failure" data-testid="checkout-error" />
						<v-btn type="submit" color="primary" variant="flat" size="large" block :disabled="!isCustomer || !order.canCheckout" :loading="placing" data-testid="place-order">
							Continue to payment
						</v-btn>
					</v-form>
				</v-card>
			</v-col>

			<v-col cols="12" md="5">
				<v-card class="pa-6" data-testid="checkout-summary">
					<div class="text-title-medium mb-1">
						Your order
					</div>
					<div class="text-body-large font-weight-medium">
						{{ order.quote?.title ?? order.entry.label }}
					</div>
					<div class="text-body-small text-medium-emphasis mb-4">
						{{ order.entry.game.label }} · {{ order.entry.label }} · {{ serverLabel }}
					</div>
					<v-alert v-if="order.error" type="warning" variant="tonal" density="compact" class="mb-4" :text="order.error" />
					<template v-if="order.quote">
						<div v-for="line in order.quote.lines" :key="line.label" class="d-flex justify-space-between ga-4 text-body-medium py-1">
							<span class="text-medium-emphasis">{{ line.label }}</span>
							<span class="text-no-wrap">{{ formatCents(line.cents) }}</span>
						</div>
						<v-divider class="my-3" />
						<div class="d-flex justify-space-between align-baseline">
							<span class="text-title-medium">Total</span>
							<span class="text-headline-medium font-weight-bold" data-testid="checkout-total">{{ formatCents(order.quote.total_cents) }}</span>
						</div>
					</template>
					<v-skeleton-loader v-else-if="!order.error" type="list-item-two-line, heading" color="transparent" />
					<v-btn :to="`/${order.entry.key}`" variant="text" :prepend-icon="mdiArrowLeft" class="mt-4 px-0">
						Change the order
					</v-btn>
				</v-card>
				<v-card class="pa-5 mt-4" variant="flat">
					<div class="text-title-small mb-1">
						Test cards
					</div>
					<p class="text-body-small text-medium-emphasis">
						<code>4242 4242 4242 4242</code> pays straight away. <code>4000 0027 6000 3184</code> asks for 3-D Secure first. Any future date and any CVC work.
					</p>
				</v-card>
			</v-col>
		</v-row>
	</v-container>
</template>
