<template>
	<v-main class="d-flex align-center justify-center py-12">
		<div style="width: 100%; max-width: 440px" class="px-4">
			<NuxtLink to="/" class="d-flex align-center justify-center ga-2 mb-8 text-decoration-none">
				<img src="/favicon.svg" alt="" width="40" height="40">
				<span class="text-headline-small font-weight-bold">King Boosting</span>
			</NuxtLink>
			<slot />
		</div>
	</v-main>
</template>
