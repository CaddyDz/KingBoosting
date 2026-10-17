<script setup lang="ts">
import { tokenRule, type BuilderState } from '~/utils/orderBuilder'
import type { CatalogEntry, PricingStrategy, Wrapped } from '~/types/api'

// One page for every service, at the legacy URLs (/division-boosting...): a new service only needs config/pricing
definePageMeta({ key: route => route.fullPath })

const INTROS: Record<PricingStrategy, string> = {
	'division-ladder': 'Choose where you are and where you want to be. Your booster climbs every division in between.',
	'per-game': 'Your booster plays your placement games. The price depends on your rank at the end of last season.',
	'per-win': 'Pay per win: your booster plays until you have every win you ordered.',
	'mastery': 'Raise a champion\'s mastery level. From level 5 up, mastery also takes tokens, which your booster earns for you.',
	'unrated': 'Warm up a new account, or keep an old one active, with unrated matches.',
}

const route = useRoute()
const service = String(route.params.service)

const { data: entry, error } = await useAsyncData(`catalog-${service}`, async () => (await useApi()<Wrapped<CatalogEntry>>(`/api/catalog/${service}`)).data)
if (!entry.value) {
	throw createError({
		statusCode: error.value?.statusCode === 404 ? 404 : 503,
		statusMessage: error.value?.statusCode === 404 ? 'Page not found' : 'The store is unavailable',
		fatal: true,
	})
}

useSeoMeta({
	title: `${entry.value.label} · ${entry.value.game.label}`,
	description: `${entry.value.game.label} ${entry.value.label.toLowerCase()}: ${INTROS[entry.value.strategy]}`,
})

const order = useOrderStore()
order.start(entry.value)

const catalog = computed(() => entry.value!)
const builder = computed(() => order.state as BuilderState)
const tokens = computed(() => tokenRule(catalog.value, builder.value.level))

// Keep the token count inside what the chosen levels allow
watch(tokens, (rule) => {
	if (rule) {
		builder.value.tokens = Math.min(rule.max, Math.max(rule.min, builder.value.tokens))
	}
}, { immediate: true })

// Prices come from the API in the browser only: server renders are cached and shared, and quotes are rate limited
onMounted(() => {
	watch(() => order.input, () => order.requestQuote(), { deep: true, immediate: true })
})

const rankLabel = computed(() => (catalog.value.strategy === 'per-game' ? 'Last season\'s rank' : 'Current rank'))
</script>

<template>
	<v-container class="py-10" style="max-width: 1200px">
		<p class="text-label-large text-primary mb-2">
			{{ catalog.game.label }}
		</p>
		<h1 class="text-headline-large font-weight-bold mb-2">
			{{ catalog.label }}
		</h1>
		<p class="text-body-large text-medium-emphasis mb-8" style="max-width: 640px">
			{{ INTROS[catalog.strategy] }}
		</p>

		<v-row>
			<v-col cols="12" md="7" lg="8">
				<v-card class="pa-6 mb-4">
					<v-row v-if="catalog.ranks && catalog.strategy === 'division-ladder'">
						<v-col cols="12" sm="6">
							<BuilderRankPicker v-model="builder.current" :ranks="catalog.ranks" label="Current rank" testid="current" />
						</v-col>
						<v-col cols="12" sm="6">
							<BuilderRankPicker v-model="builder.desired" :ranks="catalog.ranks" label="Desired rank" testid="desired" />
						</v-col>
						<v-col v-if="catalog.lp" cols="12" sm="6">
							<v-select v-model="builder.lp" :items="catalog.lp" label="LP in your current division" hide-details data-testid="lp" />
						</v-col>
						<v-col v-if="catalog.mmr" cols="12" sm="6">
							<v-select v-model="builder.mmr" :items="catalog.mmr" label="LP you gain per win" hide-details data-testid="mmr" />
						</v-col>
					</v-row>

					<v-row v-else-if="catalog.ranks && catalog.amount">
						<v-col cols="12" sm="6">
							<BuilderRankPicker v-model="builder.current" :ranks="catalog.ranks" :label="rankLabel" testid="current" />
						</v-col>
						<v-col cols="12" sm="6">
							<BuilderAmount v-model="builder.amount" :label="catalog.amount.label" :min="catalog.amount.min" :max="catalog.amount.max" />
						</v-col>
					</v-row>

					<BuilderMastery
						v-else-if="catalog.mastery && catalog.champions"
						v-model:champion="builder.champion"
						v-model:level="builder.level"
						v-model:token-count="builder.tokens"
						:champions="catalog.champions"
						:max-level="catalog.mastery.max_level"
						:tokens="tokens"
					/>

					<BuilderAmount v-else-if="catalog.amount" v-model="builder.amount" :label="catalog.amount.label" :min="catalog.amount.min" :max="catalog.amount.max" />

					<v-divider class="my-6" />
					<v-select v-model="order.server" :items="catalog.servers" item-title="label" item-value="key" label="Server" hide-details data-testid="server" style="max-width: 320px" />
				</v-card>

				<v-card class="pa-6">
					<BuilderOptions v-model="builder.options" :options="catalog.options" />
				</v-card>

				<!-- On phones the summary comes last, so the total follows the builder at the bottom of the screen -->
				<div class="mobile-total">
					<div>
						<div class="text-body-small text-medium-emphasis">
							Total
						</div>
						<div class="text-title-large font-weight-bold" :class="{ 'text-medium-emphasis': order.quoting }">
							{{ order.quote ? formatCents(order.quote.total_cents) : '—' }}
						</div>
					</div>
					<v-btn color="primary" variant="flat" :disabled="!order.canCheckout" to="/checkout">
						Checkout
					</v-btn>
				</div>
			</v-col>

			<v-col cols="12" md="5" lg="4">
				<div class="summary">
					<BuilderSummary />
				</div>
			</v-col>
		</v-row>
	</v-container>
</template>

<style scoped>
.summary {
	position: sticky;
	top: 108px;
}

.mobile-total {
	position: sticky;
	bottom: 0;
	z-index: 1;
	display: none;
	align-items: center;
	justify-content: space-between;
	margin: 16px -16px 0;
	padding: 12px 16px;
	border-top: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
	background: rgb(var(--v-theme-surface));
}

@media (max-width: 959.98px) {
	.mobile-total {
		display: flex;
	}
}
</style>
