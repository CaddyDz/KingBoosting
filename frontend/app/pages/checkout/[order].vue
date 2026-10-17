<script setup lang="ts">
import type { Order, Wrapped } from '~/types/api'
import { errorMessage, statusOf } from '~/utils/api'

/**
 * Pays for one order: right after checkout, after a 3-D Secure detour (Stripe returns here with
 * redirect_status), or later from the order page. Once Stripe says yes, it waits for the webhook to move the
 * order on, then opens it in the dashboard.
 */
definePageMeta({ middleware: ['auth'] })
useHead({ title: 'Payment' })

const route = useRoute()
const id = Number(route.params.order)
const snackbar = useSnackbar()

const order = ref<Order | null>(null)
const clientSecret = ref<string | null>(null)
const phase = ref<'loading' | 'paying' | 'confirming' | 'slow' | 'error'>('loading')
const problem = ref<string | null>(null)

const redirectStatus = typeof route.query.redirect_status === 'string' ? route.query.redirect_status : null

async function load(): Promise<Order> {
	order.value = (await useApi()<Wrapped<Order>>(`/api/orders/${id}`)).data

	return order.value
}

async function done(): Promise<void> {
	const booster = order.value?.booster?.username
	snackbar.success(booster ? `Payment received. ${booster} has your order.` : 'Payment received. Your order is waiting for a booster.')
	await navigateTo(`/dashboard/orders/${id}`, { replace: true })
}

// Stripe answers the browser first; the order changes when its webhook arrives, usually within seconds
async function confirm(): Promise<void> {
	phase.value = 'confirming'
	for (let attempt = 0; attempt < 30; attempt++) {
		const current = await load()
		if (current.status.value !== 'awaiting_payment') {
			return done()
		}
		await new Promise(resolve => setTimeout(resolve, 1500))
	}
	phase.value = 'slow'
}

async function startPayment(): Promise<void> {
	clientSecret.value = (await useApi()<Wrapped<{ client_secret: string, amount_cents: number }>>(`/api/orders/${id}/payment`)).data.client_secret
	phase.value = 'paying'
}

onMounted(async () => {
	try {
		const current = await load()
		if (current.status.value !== 'awaiting_payment') {
			return done()
		}
		if (redirectStatus === 'succeeded' || redirectStatus === 'processing') {
			return confirm()
		}
		if (redirectStatus) {
			problem.value = 'The payment didn\'t go through: the bank declined it or the check was cancelled. Try again, or use another card.'
		}
		await startPayment()
	}
	catch (error) {
		const messages: Record<number, string> = {
			403: 'This order isn\'t yours to pay.',
			404: 'This order doesn\'t exist. Unpaid orders are deleted after a day.',
			503: 'Payments aren\'t available on this server right now. Your order is saved: pay for it later from your orders.',
		}
		phase.value = 'error'
		problem.value = messages[statusOf(error) ?? 0] ?? errorMessage(error)
	}
})
</script>

<template>
	<v-container class="py-10" style="max-width: 720px">
		<h1 class="text-headline-large font-weight-bold mb-2">
			Payment
		</h1>
		<p v-if="order" class="text-body-large text-medium-emphasis mb-6">
			Order #{{ order.id }}: {{ order.title }} · {{ order.game.label }}
		</p>

		<div v-if="phase === 'loading'" class="d-flex justify-center py-16">
			<v-progress-circular indeterminate color="primary" />
		</div>

		<v-card v-else-if="phase === 'confirming'" class="pa-8 text-center" data-testid="confirming">
			<v-progress-circular indeterminate color="primary" class="mb-4" />
			<h2 class="text-title-large mb-1">
				Confirming your payment
			</h2>
			<p class="text-body-medium text-medium-emphasis">
				Stripe has accepted it. Your order opens as soon as it's recorded.
			</p>
		</v-card>

		<v-card v-else-if="phase === 'slow'" class="pa-8 text-center" data-testid="slow">
			<h2 class="text-title-large mb-1">
				Payment accepted, still recording it
			</h2>
			<p class="text-body-medium text-medium-emphasis mb-6">
				Stripe took the payment, but the order hasn't updated yet. You'll get a notification when it does.
			</p>
			<v-btn :to="`/dashboard/orders/${id}`" color="primary" variant="flat">
				Go to the order
			</v-btn>
		</v-card>

		<v-alert v-else-if="phase === 'error'" type="error" variant="tonal" :text="problem ?? undefined" data-testid="payment-problem">
			<template #append>
				<v-btn to="/dashboard/orders" variant="text">
					Your orders
				</v-btn>
			</template>
		</v-alert>

		<v-card v-else-if="phase === 'paying' && order && clientSecret" class="pa-6">
			<v-alert v-if="problem" type="error" variant="tonal" class="mb-4" :text="problem" data-testid="declined" />
			<div class="d-flex justify-space-between align-baseline mb-4">
				<span class="text-title-medium">To pay</span>
				<span class="text-headline-medium font-weight-bold">{{ formatCents(order.price_cents ?? 0) }}</span>
			</div>
			<CheckoutPaymentForm :order-id="order.id" :client-secret="clientSecret" :amount-cents="order.price_cents ?? 0" @paid="confirm" />
			<p class="text-body-small text-medium-emphasis mt-4 text-center">
				Test mode: <code>4242 4242 4242 4242</code>, or <code>4000 0027 6000 3184</code> for 3-D Secure. Any future date and CVC.
			</p>
		</v-card>
	</v-container>
</template>
