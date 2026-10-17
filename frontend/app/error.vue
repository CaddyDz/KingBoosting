<script setup lang="ts">
import type { NuxtError } from '#app'

const props = defineProps<{ error: NuxtError }>()

const notFound = computed(() => props.error.statusCode === 404)

useHead({ title: notFound.value ? 'Page not found' : 'Something went wrong' })
</script>

<template>
	<v-app>
		<NuxtLayout>
			<v-container class="py-16 text-center" style="max-width: 640px">
				<p class="text-display-small font-weight-bold text-primary mb-2">
					{{ error.statusCode }}
				</p>
				<h1 class="text-headline-small font-weight-bold mb-3">
					{{ notFound ? 'Page not found' : 'Something went wrong' }}
				</h1>
				<p class="text-body-large text-medium-emphasis mb-8">
					{{ notFound ? 'That page doesn\'t exist, or it moved in the rebuild.' : 'Please try again in a moment.' }}
				</p>
				<v-btn color="primary" variant="flat" size="large" @click="clearError({ redirect: '/' })">
					Home page
				</v-btn>
			</v-container>
		</NuxtLayout>
	</v-app>
</template>
