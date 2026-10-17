<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })
useHead({ title: 'Dashboard' })

const { user } = useUser()
const live = ref<'connecting' | 'connected' | 'failed'>('connecting')

onMounted(() => {
	if (!user.value) {
		return
	}
	// Notifications arrive on the user's private channel
	useEcho()
		.private(`App.Models.User.${user.value.id}`)
		.subscribed(() => (live.value = 'connected'))
		.error(() => (live.value = 'failed'))
})

async function logout(): Promise<void> {
	await useAuthStore().logout()
	await navigateTo('/login')
}
</script>

<template>
	<v-container class="py-12">
		<h1 class="text-h4 font-weight-bold mb-2">
			Hello, {{ user?.username }}
		</h1>
		<p class="text-body-1 mb-6">
			You're signed in as <strong data-testid="role">{{ user?.role }}</strong>.
		</p>
		<v-chip :color="live === 'connected' ? 'success' : live === 'failed' ? 'error' : 'warning'" data-testid="live">
			Live updates: {{ live }}
		</v-chip>
		<div class="mt-8">
			<v-btn variant="outlined" @click="logout">
				Log out
			</v-btn>
		</div>
	</v-container>
</template>
