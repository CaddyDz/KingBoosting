<script setup lang="ts">
import { statusColor } from '~/utils/orders'
import type { Labelled, OrderStatus } from '~/types/api'

defineProps<{ status: Labelled<OrderStatus>, size?: 'x-small' | 'small' | 'default' }>()
</script>

<template>
	<v-chip :color="statusColor(status.value)" :size="size ?? 'small'" variant="tonal" label>
		{{ status.label }}
	</v-chip>
</template>
