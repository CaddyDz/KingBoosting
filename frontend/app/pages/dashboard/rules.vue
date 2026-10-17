<script setup lang="ts">
import rules from '~/content/rules.md?raw'

definePageMeta({ layout: 'dashboard', middleware: ['auth', 'role'], roles: ['booster', 'moderator', 'admin'] })
useHead({ title: 'Booster rules' })
</script>

<template>
	<v-card class="pa-6" style="max-width: 860px">
		<MarkdownContent :source="rules" />
	</v-card>
</template>
