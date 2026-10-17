<script setup lang="ts">
/**
 * Asks before an action that's hard to undo. The parent runs the action on `confirm` and closes the dialog.
 */
withDefaults(defineProps<{
	title: string
	text?: string
	confirmText?: string
	color?: string
	loading?: boolean
}>(), { text: undefined, confirmText: 'Confirm', color: 'primary', loading: false })

const open = defineModel<boolean>({ required: true })
const emit = defineEmits<{ confirm: [] }>()
</script>

<template>
	<v-dialog v-model="open" max-width="440" :persistent="loading">
		<v-card :title="title">
			<v-card-text v-if="text || $slots.default" class="text-body-medium">
				<slot>{{ text }}</slot>
			</v-card-text>
			<v-card-actions>
				<v-spacer />
				<v-btn variant="text" :disabled="loading" @click="open = false">
					Cancel
				</v-btn>
				<v-btn :color="color" variant="flat" :loading="loading" data-testid="confirm" @click="emit('confirm')">
					{{ confirmText }}
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>
