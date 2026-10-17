<script setup lang="ts">
import { validRank, type Rank } from '~/utils/orderBuilder'
import type { CatalogRank } from '~/types/api'

const props = defineProps<{ ranks: CatalogRank[], label: string, testid: string }>()
const model = defineModel<Rank>({ required: true })

const divisions = computed(() => props.ranks.find(tier => tier.key === model.value.tier)?.divisions ?? [])
</script>

<template>
	<fieldset class="rank-picker">
		<legend class="text-title-small mb-2">
			{{ label }}
		</legend>
		<v-select
			:model-value="model.tier"
			:items="ranks"
			item-title="name"
			item-value="key"
			label="Tier"
			hide-details
			class="mb-3"
			:data-testid="`${testid}-tier`"
			@update:model-value="tier => model = validRank(ranks, { tier, division: model.division })"
		/>
		<v-btn-toggle
			v-if="divisions.length"
			:model-value="model.division"
			mandatory
			divided
			variant="outlined"
			density="comfortable"
			:aria-label="`${label}: division`"
			:data-testid="`${testid}-division`"
			@update:model-value="division => model = { ...model, division }"
		>
			<v-btn v-for="division in divisions" :key="division" :value="division" class="px-4">
				{{ division }}
			</v-btn>
		</v-btn-toggle>
		<p v-else class="text-body-small text-medium-emphasis">
			No divisions in this tier.
		</p>
	</fieldset>
</template>

<style scoped>
.rank-picker {
	min-width: 0;
	margin: 0;
	padding: 0;
	border: 0;
}
</style>
