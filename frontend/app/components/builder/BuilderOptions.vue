<script setup lang="ts">
import type { CatalogOption } from '~/types/api'

defineProps<{ options: CatalogOption[] }>()
const model = defineModel<string[]>({ required: true })
</script>

<template>
	<fieldset class="options">
		<legend class="text-title-medium mb-1">
			Extras
		</legend>
		<p class="text-body-small text-medium-emphasis mb-3">
			Percentages apply to the whole order.
		</p>
		<label v-for="option in options" :key="option.key" class="option d-flex align-start ga-2 py-2">
			<v-checkbox-btn v-model="model" :value="option.key" color="primary" class="flex-grow-0" :aria-label="option.label" :data-testid="`option-${option.key}`" />
			<div class="flex-grow-1 pt-2">
				<div class="text-body-large">
					{{ option.label }}
				</div>
				<div class="text-body-small text-medium-emphasis">
					{{ option.description }}
				</div>
			</div>
			<v-chip size="small" :color="option.percent ? 'primary' : undefined" variant="tonal" class="mt-2">
				{{ option.percent ? `+${option.percent}%` : 'Free' }}
			</v-chip>
		</label>
	</fieldset>
</template>

<style scoped>
.options {
	min-width: 0;
	margin: 0;
	padding: 0;
	border: 0;
}

.option {
	cursor: pointer;
}

.option + .option {
	border-top: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
}
</style>
