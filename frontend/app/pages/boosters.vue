<script setup lang="ts">
useSeoMeta({ title: 'Boosters', description: 'The boosters you can choose at checkout.' })

const { data: boosters } = useAsyncData('boosters', async () => (await useApi()<{ data: { id: number, username: string }[] }>('/api/boosters')).data, {
	default: () => [],
})
</script>

<template>
	<v-container class="py-12" style="max-width: 1200px">
		<h1 class="text-headline-large font-weight-bold mb-2">
			Boosters
		</h1>
		<p class="text-body-large text-medium-emphasis mb-8" style="max-width: 640px">
			Pick one of them at checkout to have them play your order, or leave it open and the first booster free claims it. Every booster applied and was reviewed by the team before getting any orders.
		</p>
		<v-row v-if="boosters.length">
			<v-col v-for="booster in boosters" :key="booster.id" cols="12" sm="6" md="4" lg="3">
				<v-card class="pa-4 d-flex align-center ga-4" data-testid="booster">
					<v-avatar color="primary" variant="tonal" size="48">
						<span class="text-title-large">{{ booster.username.charAt(0).toUpperCase() }}</span>
					</v-avatar>
					<div>
						<div class="text-title-medium">
							{{ booster.username }}
						</div>
						<div class="text-body-small text-medium-emphasis">
							Available to choose at checkout
						</div>
					</div>
				</v-card>
			</v-col>
		</v-row>
		<v-card v-else class="pa-8 text-center text-medium-emphasis">
			No boosters are taking picks right now. Orders still go to the first booster free.
		</v-card>
		<p class="text-body-medium mt-8">
			Good at the game? <NuxtLink to="/apply" class="text-primary">Apply to become a booster</NuxtLink>.
		</p>
	</v-container>
</template>
