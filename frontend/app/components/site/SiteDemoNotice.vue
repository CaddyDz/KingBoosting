<script setup lang="ts">
/**
 * The line every page carries (docs/adr/0004). `compact` shortens it on phones, for the one-line bar at the top.
 */
defineProps<{ compact?: boolean }>()
</script>

<template>
	<p class="demo-notice text-body-small" data-testid="demo-notice">
		<template v-if="compact">
			<span class="d-none d-sm-inline">Portfolio demo · not affiliated with Riot Games · test payments only</span>
			<span class="d-sm-none">Demo · not affiliated with Riot Games · test payments</span>
		</template>
		<template v-else>
			Portfolio demo · not affiliated with Riot Games · test payments only
		</template>
	</p>
</template>

<style scoped>
.demo-notice {
	color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
</style>
