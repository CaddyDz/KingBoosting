<script setup lang="ts">
import type { AdminUser, Paginated } from '~/types/api'

/**
 * Picks a booster by username, searching the users API as the admin types. Hidden boosters are included.
 */
const model = defineModel<number | null>({ required: true })

const search = ref('')
const boosters = ref<AdminUser[]>([])
const loading = ref(false)
let timer: ReturnType<typeof setTimeout> | undefined

async function load(): Promise<void> {
	loading.value = true
	try {
		const response = await useApi()<Paginated<AdminUser>>('/api/users', { query: { role: 'booster', ...(search.value ? { search: search.value } : {}) } })
		// Keep the chosen booster in the list, or the field would show its id
		const chosen = boosters.value.find(booster => booster.id === model.value)
		boosters.value = chosen && !response.data.some(booster => booster.id === chosen.id) ? [chosen, ...response.data] : response.data
	}
	finally {
		loading.value = false
	}
}

watch(search, () => {
	clearTimeout(timer)
	timer = setTimeout(load, 250)
})
onMounted(load)
</script>

<template>
	<v-autocomplete
		v-model="model"
		v-model:search="search"
		:items="boosters"
		item-title="username"
		item-value="id"
		:loading="loading"
		no-filter
		label="Booster"
		no-data-text="No booster by that name"
	>
		<template #item="{ props: itemProps, item }">
			<v-list-item v-bind="itemProps" :subtitle="item.visible ? item.email : `${item.email} · hidden from customers`" />
		</template>
	</v-autocomplete>
</template>
