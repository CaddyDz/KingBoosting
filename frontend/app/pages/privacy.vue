<script setup lang="ts">
import content from '~/content/privacy.md?raw'

useSeoMeta({ title: 'Privacy', description: 'What the King Boosting demo stores, for how long, and which cookies it sets.' })
</script>

<template>
	<v-container class="py-12" style="max-width: 820px">
		<MarkdownContent :source="content" />
	</v-container>
</template>
