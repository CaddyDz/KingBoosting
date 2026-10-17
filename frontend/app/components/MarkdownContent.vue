<script setup lang="ts">
import { marked } from 'marked'

/**
 * Renders Markdown bundled with the app (app/content). Never pass it anything a user wrote: it goes through v-html.
 */
const props = defineProps<{ source: string }>()

const html = computed(() => marked.parse(props.source, { async: false }))
</script>

<template>
	<!-- eslint-disable-next-line vue/no-v-html -->
	<div class="markdown" v-html="html" />
</template>

<style scoped>
.markdown :deep(h1) {
	font-size: 2rem;
	line-height: 1.25;
	margin-bottom: 1rem;
}

.markdown :deep(h2) {
	font-size: 1.25rem;
	margin: 2rem 0 0.5rem;
}

.markdown :deep(p),
.markdown :deep(li) {
	line-height: 1.65;
}

.markdown :deep(p) {
	margin-bottom: 0.75rem;
}

.markdown :deep(ul) {
	padding-left: 1.25rem;
	margin-bottom: 0.75rem;
}

.markdown :deep(li) {
	margin: 0.35rem 0;
}

.markdown :deep(a) {
	color: rgb(var(--v-theme-primary));
}

.markdown :deep(code) {
	padding: 0.1em 0.35em;
	border-radius: 4px;
	background: rgba(var(--v-theme-on-surface), 0.08);
	font-size: 0.9em;
}
</style>
