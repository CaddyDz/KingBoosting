<script setup lang="ts">
import { RANK_BADGES, inkFor } from '~/utils/rankBadges'

const props = withDefaults(defineProps<{ tier: string, size?: number }>(), { size: 32 })

const badge = computed(() => RANK_BADGES[props.tier] ?? { fill: '#6b6876', initials: props.tier.charAt(0).toUpperCase() })
const ink = computed(() => inkFor(badge.value.fill))
const gradient = useId()
</script>

<template>
	<svg :width="size" :height="size" viewBox="0 0 32 32" aria-hidden="true" class="rank-badge">
		<defs>
			<linearGradient :id="gradient" x1="0" y1="0" x2="0" y2="1">
				<stop offset="0" stop-color="#ffffff" stop-opacity="0.28" />
				<stop offset="1" stop-color="#ffffff" stop-opacity="0" />
			</linearGradient>
		</defs>
		<path d="M16 1.5 L28.5 8.75 V23.25 L16 30.5 L3.5 23.25 V8.75 Z" :fill="badge.fill" />
		<path d="M16 1.5 L28.5 8.75 V23.25 L16 30.5 L3.5 23.25 V8.75 Z" :fill="`url(#${gradient})`" />
		<path d="M16 6.5 L24 11.1 V20.9 L16 25.5 L8 20.9 V11.1 Z" fill="none" :stroke="ink" stroke-opacity="0.45" stroke-width="1.2" />
		<text x="16" y="16" dominant-baseline="central" text-anchor="middle" :fill="ink" font-size="9.5" font-weight="700" font-family="inherit">{{ badge.initials }}</text>
	</svg>
</template>

<style scoped>
.rank-badge {
	flex: none;
}
</style>
