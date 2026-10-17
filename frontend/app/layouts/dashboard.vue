<script setup lang="ts">
import { mdiAccountCircleOutline, mdiLogout, mdiThemeLightDark } from '@mdi/js'

const nav = useDashboardNav()
const { user } = useUser()
const { mdAndUp } = useVDisplay()
const theme = useVTheme()
const drawer = ref<boolean | null>(null)

function toggleTheme(): void {
	theme.change(theme.global.current.value.dark ? 'light' : 'dark')
}

async function logout(): Promise<void> {
	await useAuthStore().logout()
	await navigateTo('/login')
}
</script>

<template>
	<v-layout>
		<v-navigation-drawer v-model="drawer" :permanent="mdAndUp" width="248">
			<NuxtLink to="/" class="d-flex align-center ga-2 pa-4 text-decoration-none">
				<img src="/favicon.svg" alt="" width="32" height="32">
				<span class="text-subtitle-1 font-weight-bold">King Boosting</span>
			</NuxtLink>
			<v-divider />
			<v-list nav density="comfortable">
				<v-list-item v-for="item in nav" :key="item.to" :to="item.to" :prepend-icon="item.icon" :title="item.title" color="primary" />
			</v-list>
		</v-navigation-drawer>

		<v-app-bar flat border="b">
			<v-app-bar-nav-icon v-if="!mdAndUp" aria-label="Menu" @click="drawer = !drawer" />
			<v-spacer />
			<DashboardNotificationBell />
			<v-menu location="bottom end">
				<template #activator="{ props }">
					<v-btn v-bind="props" variant="text" :prepend-icon="mdiAccountCircleOutline" class="text-none">
						{{ user?.username }}
					</v-btn>
				</template>
				<v-list density="compact">
					<v-list-item :subtitle="user?.email" :title="`Signed in as ${user?.role}`" />
					<v-divider />
					<v-list-item :prepend-icon="mdiThemeLightDark" title="Switch theme" @click="toggleTheme" />
					<v-list-item :prepend-icon="mdiLogout" title="Log out" @click="logout" />
				</v-list>
			</v-menu>
		</v-app-bar>

		<v-main>
			<v-container fluid class="pa-4 pa-md-6" style="max-width: 1400px">
				<slot />
			</v-container>
		</v-main>
	</v-layout>
</template>
