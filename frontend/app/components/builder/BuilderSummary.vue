<script setup lang="ts">
const order = useOrderStore()

const serverLabel = computed(() => order.entry?.servers.find(server => server.key === order.server)?.label ?? '—')
</script>

<template>
	<v-card class="pa-6" data-testid="summary">
		<div class="text-title-medium mb-1">
			Your order
		</div>
		<div class="text-body-large font-weight-medium" data-testid="quote-title">
			{{ order.quote?.title ?? order.entry?.label }}
		</div>
		<div class="text-body-small text-medium-emphasis mb-4">
			{{ order.entry?.game.label }} · {{ serverLabel }}
		</div>

		<v-alert v-if="order.error" type="warning" variant="tonal" density="compact" class="mb-4" :text="order.error" data-testid="quote-error" />

		<div class="price" :class="{ stale: order.quoting }" :aria-busy="order.quoting">
			<template v-if="order.quote">
				<div v-for="line in order.quote.lines" :key="line.label" class="d-flex justify-space-between ga-4 text-body-medium py-1">
					<span class="text-medium-emphasis">{{ line.label }}</span>
					<span class="numeric text-no-wrap">{{ formatCents(line.cents) }}</span>
				</div>
				<v-divider class="my-3" />
				<div class="d-flex justify-space-between align-baseline">
					<span class="text-title-medium">Total</span>
					<span class="text-headline-medium font-weight-bold" data-testid="quote-total">{{ formatCents(order.quote.total_cents) }}</span>
				</div>
			</template>
			<v-skeleton-loader v-else-if="!order.error" type="list-item-two-line, divider, heading" color="transparent" />
		</div>

		<v-btn block color="primary" variant="flat" size="large" class="mt-6" :disabled="!order.canCheckout" to="/checkout" data-testid="checkout">
			Continue to checkout
		</v-btn>
		<p class="text-body-small text-medium-emphasis mt-3 text-center">
			Test payments only: no real money moves.
		</p>
	</v-card>
</template>

<style scoped>
.price {
	transition: opacity 0.15s;
}

/* While a new price loads, the old one stays in place, dimmed */
.price.stale {
	opacity: 0.55;
}

.numeric {
	font-variant-numeric: tabular-nums;
}
</style>
