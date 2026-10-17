<script setup lang="ts">
const { snack } = useSnackbar()
const open = computed({
	get: () => snack.value !== null,
	set: (value: boolean) => {
		if (!value) {
			snack.value = null
		}
	},
})
</script>

<template>
	<v-snackbar v-model="open" :color="snack?.color" timeout="5000" location="bottom end">
		{{ snack?.text }}
	</v-snackbar>
</template>
