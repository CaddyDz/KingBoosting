<script setup lang="ts">
import content from '~/content/about.md?raw'

useSeoMeta({ title: 'About this demo', description: 'What King Boosting is, what to try in the demo, and how it is built.' })
</script>

<template>
	<v-container class="py-12" style="max-width: 820px">
		<MarkdownContent :source="content" />
	</v-container>
</template>
