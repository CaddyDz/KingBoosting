<script setup lang="ts">
import { mdiMenu } from '@mdi/js'

const drawer = ref(false)

onMounted(() => useAuthStore().loadIfSignedIn())

const links = [
	{ title: 'Boosts', to: '/#games' },
	{ title: 'Boosters', to: '/boosters' },
	{ title: 'Become a booster', to: '/apply' },
	{ title: 'Contact', to: '/contact' },
]
</script>

<template>
	<v-layout>
		<v-system-bar class="justify-center" height="28" color="surface-variant">
			<SiteDemoNotice compact class="text-truncate" />
		</v-system-bar>

		<v-app-bar flat border="b" height="64">
			<v-container class="d-flex align-center py-0 site-width">
				<NuxtLink to="/" class="d-flex align-center ga-2 text-decoration-none me-6">
					<img src="/favicon.svg" alt="" width="32" height="32">
					<span class="text-title-medium font-weight-bold">King Boosting</span>
				</NuxtLink>
				<nav class="d-none d-md-flex ga-1" aria-label="Main">
					<v-btn v-for="link in links" :key="link.to" :to="link.to" variant="text" class="text-none">
						{{ link.title }}
					</v-btn>
				</nav>
				<v-spacer />
				<div class="d-none d-sm-flex">
					<SiteAccountButtons />
				</div>
				<v-app-bar-nav-icon class="d-md-none ms-2" :icon="mdiMenu" aria-label="Menu" @click="drawer = !drawer" />
			</v-container>
		</v-app-bar>

		<v-navigation-drawer v-model="drawer" temporary location="right">
			<v-list nav>
				<v-list-item v-for="link in links" :key="link.to" :to="link.to" :title="link.title" @click="drawer = false" />
			</v-list>
			<div class="pa-4 d-sm-none">
				<SiteAccountButtons />
			</div>
		</v-navigation-drawer>

		<v-main>
			<slot />
			<SiteFooter />
		</v-main>
	</v-layout>
</template>

<style scoped>
.site-width {
	max-width: 1200px;
}
</style>
