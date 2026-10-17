<script setup lang="ts">
import type { Stripe, StripeElements, StripePaymentElement } from '@stripe/stripe-js'
import { errorMessage } from '~/utils/api'

/**
 * Stripe's Payment Element for one order. Card details go from the browser to Stripe, never to our API.
 * Cards that need 3-D Secure leave for the bank's page and come back to /checkout/{order}.
 */
const props = defineProps<{ orderId: number, clientSecret: string, amountCents: number, offline?: boolean }>()
const emit = defineEmits<{ paid: [] }>()

const config = useRuntimeConfig()
const theme = useVTheme()
const mount = ref<HTMLElement | null>(null)
const ready = ref(false)
const submitting = ref(false)
const failure = ref<string | null>(null)
const unavailable = ref(!props.offline && !config.public.stripeKey)

// A local stack without Stripe keys: the API offers a stand-in for Stripe's confirmation
async function simulate(): Promise<void> {
	submitting.value = true
	failure.value = null
	try {
		await useApi()(`/api/testing/orders/${props.orderId}/pay`, { method: 'POST' })
		emit('paid')
	}
	catch (error) {
		failure.value = errorMessage(error)
	}
	finally {
		submitting.value = false
	}
}

let stripe: Stripe | null = null
let elements: StripeElements | null = null
let paymentElement: StripePaymentElement | null = null

onMounted(async () => {
	if (props.offline || unavailable.value || !mount.value) {
		return
	}
	try {
		// Loaded on demand, so only checkout pulls in Stripe.js
		const { loadStripe } = await import('@stripe/stripe-js')
		stripe = await loadStripe(config.public.stripeKey)
	}
	catch {
		stripe = null
	}
	if (!stripe) {
		failure.value = 'Stripe couldn\'t be loaded. Check your connection, or turn off a blocker for js.stripe.com.'

		return
	}
	const colors = theme.current.value.colors
	elements = stripe.elements({
		clientSecret: props.clientSecret,
		appearance: {
			theme: theme.current.value.dark ? 'night' : 'stripe',
			variables: { colorPrimary: String(colors.primary), colorBackground: String(colors.surface), borderRadius: '8px' },
		},
	})
	paymentElement = elements.create('payment', { layout: 'tabs' })
	paymentElement.on('ready', () => (ready.value = true))
	paymentElement.mount(mount.value)
})

onBeforeUnmount(() => paymentElement?.destroy())

async function pay(): Promise<void> {
	if (!stripe || !elements) {
		return
	}
	submitting.value = true
	failure.value = null
	const { error, paymentIntent } = await stripe.confirmPayment({
		elements,
		redirect: 'if_required',
		confirmParams: { return_url: `${window.location.origin}/checkout/${props.orderId}` },
	})
	submitting.value = false
	if (error) {
		failure.value = error.message ?? 'The payment didn\'t go through. Try again or use another card.'
	}
	else if (paymentIntent && ['succeeded', 'processing'].includes(paymentIntent.status)) {
		emit('paid')
	}
	else {
		failure.value = 'The payment didn\'t go through. Try again or use another card.'
	}
}
</script>

<template>
	<div>
		<template v-if="offline">
			<v-alert type="info" variant="tonal" data-testid="payments-offline">
				This local stack has no Stripe keys, so payments run offline. Simulate what Stripe would answer:
			</v-alert>
			<v-alert v-if="failure" type="error" variant="tonal" class="mt-4" :text="failure" />
			<v-btn block color="primary" variant="flat" size="large" class="mt-6" :loading="submitting" data-testid="simulate-payment" @click="simulate">
				Simulate a successful payment of {{ formatCents(amountCents) }}
			</v-btn>
		</template>
		<v-alert v-else-if="unavailable" type="info" variant="tonal" data-testid="payments-off">
			Payments are off in this environment: it has no Stripe test keys. The order is saved and waits for payment.
		</v-alert>
		<template v-else>
			<div ref="mount" class="payment-element" />
			<v-skeleton-loader v-if="!ready && !failure" type="article" color="transparent" />
			<v-alert v-if="failure" type="error" variant="tonal" class="mt-4" :text="failure" data-testid="payment-error" />
			<v-btn block color="primary" variant="flat" size="large" class="mt-6" :disabled="!ready" :loading="submitting" data-testid="pay" @click="pay">
				Pay {{ formatCents(amountCents) }}
			</v-btn>
		</template>
	</div>
</template>

<style scoped>
.payment-element {
	min-height: 1px;
}
</style>
