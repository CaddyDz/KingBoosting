<script setup lang="ts">
const props = defineProps<{ champions: string[], maxLevel: number, tokens: { min: number, max: number } | null }>()
const champion = defineModel<string>('champion', { required: true })
const level = defineModel<{ current: number, desired: number }>('level', { required: true })
const tokenCount = defineModel<number>('tokenCount', { required: true })

// Level 0 is a champion never played
const currentLevels = computed(() => Array.from({ length: props.maxLevel }, (_, index) => ({ title: index === 0 ? '0 (new champion)' : String(index), value: index })))
const desiredLevels = computed(() => Array.from({ length: props.maxLevel - level.value.current }, (_, index) => level.value.current + index + 1))

function setCurrent(current: number): void {
	level.value = { current, desired: Math.max(level.value.desired, current + 1) }
}
</script>

<template>
	<div>
		<v-autocomplete v-model="champion" :items="champions" label="Champion" class="mb-2" data-testid="champion" />
		<v-row density="compact">
			<v-col cols="6">
				<v-select :model-value="level.current" :items="currentLevels" label="Current level" data-testid="level-current" @update:model-value="setCurrent" />
			</v-col>
			<v-col cols="6">
				<v-select :model-value="level.desired" :items="desiredLevels" label="Desired level" data-testid="level-desired" @update:model-value="desired => level = { ...level, desired }" />
			</v-col>
		</v-row>
		<template v-if="tokens">
			<BuilderAmount v-model="tokenCount" label="mastery tokens" :min="tokens.min" :max="tokens.max" />
			<p class="text-body-small text-medium-emphasis">
				Levels 6 and 7 also need mastery tokens, earned with high grades. Choose how many your booster should earn.
			</p>
		</template>
	</div>
</template>
