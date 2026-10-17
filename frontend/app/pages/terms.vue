<script setup lang="ts">
import content from '~/content/terms.md?raw'

useSeoMeta({ title: 'Terms of use', description: 'The terms for using the King Boosting portfolio demo.' })
</script>

<template>
	<v-container class="py-12" style="max-width: 820px">
		<MarkdownContent :source="content" />
	</v-container>
</template>
