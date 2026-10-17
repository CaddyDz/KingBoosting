<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })

// Each role has its own landing screen
await navigateTo(dashboardHome(useAuthStore().role), { replace: true })
</script>

<template>
	<div />
</template>
