<script setup lang="ts">
import { marked } from 'marked'
import rules from '~/content/rules.md?raw'

definePageMeta({ layout: 'dashboard', middleware: ['auth', 'role'], roles: ['booster', 'moderator', 'admin'] })
useHead({ title: 'Booster rules' })

// Our own Markdown, bundled with the app: nothing user-written goes through v-html here
const html = marked.parse(rules, { async: false })
</script>

<template>
	<v-card class="pa-6 rules" style="max-width: 860px">
		<!-- eslint-disable-next-line vue/no-v-html -->
		<div v-html="html" />
	</v-card>
</template>

<style scoped>
.rules :deep(h1) {
	font-size: 1.75rem;
	margin-bottom: 0.75rem;
}

.rules :deep(h2) {
	font-size: 1.2rem;
	margin: 1.75rem 0 0.5rem;
}

.rules :deep(ul) {
	padding-left: 1.25rem;
}

.rules :deep(li) {
	margin: 0.35rem 0;
	line-height: 1.55;
}
</style>
