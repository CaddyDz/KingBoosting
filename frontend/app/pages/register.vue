<script setup lang="ts">
definePageMeta({ layout: 'auth', middleware: ['guest'] })
useHead({ title: 'Create an account' })
</script>

<template>
	<v-card class="pa-6">
		<h1 class="text-headline-small font-weight-bold mb-6">
			Create an account
		</h1>
		<AuthRegisterForm @registered="navigateTo('/dashboard')" />
		<p class="text-body-medium text-center mt-6">
			Already have an account?
			<NuxtLink to="/login" class="text-primary">
				Log in
			</NuxtLink>
		</p>
	</v-card>
</template>
