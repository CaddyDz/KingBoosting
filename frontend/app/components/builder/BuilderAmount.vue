<script setup lang="ts">
const props = defineProps<{ label: string, min: number, max: number }>()
const model = defineModel<number>({ required: true })

function typed(value: string | number): void {
	const amount = Math.round(Number(value))
	if (Number.isFinite(amount)) {
		model.value = Math.min(props.max, Math.max(props.min, amount))
	}
}
</script>

<template>
	<fieldset class="amount">
		<legend class="text-title-small mb-2">
			Number of {{ label }}
		</legend>
		<div class="d-flex align-center ga-4">
			<v-slider v-model="model" :min="min" :max="max" :step="1" color="primary" hide-details :aria-label="`Number of ${label}`" />
			<v-text-field
				:model-value="model"
				type="number"
				:min="min"
				:max="max"
				hide-details
				density="compact"
				style="max-width: 96px"
				:aria-label="`Number of ${label}, typed`"
				data-testid="amount"
				@update:model-value="typed"
			/>
		</div>
		<p class="text-body-small text-medium-emphasis mt-1">
			From {{ min }} to {{ max }}.
		</p>
	</fieldset>
</template>

<style scoped>
.amount {
	min-width: 0;
	margin: 0;
	padding: 0;
	border: 0;
}
</style>
