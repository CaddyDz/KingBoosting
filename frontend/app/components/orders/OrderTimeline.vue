<script setup lang="ts">
import { eventText } from '~/utils/orderActions'
import { formatDateTime, statusColor } from '~/utils/orders'
import type { OrderEvent } from '~/types/api'

defineProps<{ events: OrderEvent[] }>()
</script>

<template>
	<v-card title="Timeline">
		<v-card-text>
			<v-timeline density="compact" side="end" truncate-line="both">
				<v-timeline-item
					v-for="event in events"
					:key="event.id"
					:dot-color="event.type === 'payment_failed' ? 'error' : event.to_status ? statusColor(event.to_status) : 'grey'"
					size="x-small"
				>
					<div class="text-body-2 font-weight-medium">
						{{ eventText(event.type, event.actor) }}
					</div>
					<div class="text-caption text-medium-emphasis">
						{{ formatDateTime(event.created_at) }}
					</div>
				</v-timeline-item>
			</v-timeline>
		</v-card-text>
	</v-card>
</template>
