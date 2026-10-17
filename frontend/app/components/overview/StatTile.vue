<script setup lang="ts">
/**
 * One headline number on the admin overview, optionally linking to the screen behind it.
 */
defineProps<{
	label: string
	value: string
	caption?: string
	to?: string
}>()
</script>

<template>
	<v-card class="pa-4 h-100" :to="to">
		<div class="text-body-medium text-medium-emphasis">
			{{ label }}
		</div>
		<div class="text-headline-large font-weight-bold mt-1" data-testid="stat-value">
			{{ value }}
		</div>
		<div v-if="caption" class="text-body-small text-medium-emphasis mt-1">
			{{ caption }}
		</div>
	</v-card>
</template>
