<script setup lang="ts">
import { mdiAccountKeyOutline, mdiAccountStarOutline, mdiArrowRight, mdiBellRingOutline, mdiCalculatorVariantOutline, mdiChatProcessingOutline, mdiPauseCircleOutline } from '@mdi/js'

useSeoMeta({
	title: 'Game boosting, as a portfolio demo',
	description: 'A working demo of an order marketplace for League of Legends, Valorant and Wild Rift boosts: live prices, Stripe test checkout, real-time chat and a back office for every role.',
})

const { games } = useCatalog()

const BLURBS: Record<string, string> = {
	lol: 'Climb divisions solo or in duo, finish your placements, bank ranked wins or level a champion\'s mastery.',
	valorant: 'Rank boosting, placement matches, and unrated games to warm up a fresh account.',
	wildrift: 'Rank boosting, placement games and ranked wins on the mobile ladder.',
}

const steps = [
	{ title: 'Build your order', text: 'Pick the game, where you are and where you want to be. The price updates as you go, and the server works it out, so what you see is what you pay.' },
	{ title: 'Pay with a test card', text: 'Checkout runs on Stripe in test mode. Card 4242 4242 4242 4242 goes through, and no real money moves.' },
	{ title: 'Follow it live', text: 'A booster claims the order. Chat with them on the order page, pause it when you want to play, and get notified when it\'s done.' },
]

const features = [
	{ icon: mdiAccountKeyOutline, title: 'Your login stays private', text: 'Account details are encrypted. Your booster sees them only while playing your order; other boosters and moderators never do.' },
	{ icon: mdiPauseCircleOutline, title: 'Pause any time', text: 'Want to play yourself tonight? Pause the order, and resume it when you\'re done.' },
	{ icon: mdiChatProcessingOutline, title: 'Chat with your booster', text: 'Every order has its own chat, with read receipts. Messages arrive instantly in both directions.' },
	{ icon: mdiCalculatorVariantOutline, title: 'Prices you can check', text: 'Every price is built from published rate tables and options, in whole cents. The browser never sets it.' },
	{ icon: mdiAccountStarOutline, title: 'Choose your booster', text: 'Pick a booster you\'ve played with at checkout, or leave it open for the first one free.' },
	{ icon: mdiBellRingOutline, title: 'Notified at every step', text: 'Claimed, paused, completed: each change shows up in the app and in your inbox.' },
]
</script>

<template>
	<div>
		<section class="hero">
			<svg class="hero-crown" viewBox="0 0 64 64" aria-hidden="true">
				<path d="M14 44 L18 22 L27 32 L32 18 L37 32 L46 22 L50 44 Z" />
				<rect x="14" y="46" width="36" height="5" rx="2" />
			</svg>
			<v-container class="site-width py-16 position-relative">
				<p class="text-label-large text-primary mb-3">
					League of Legends · Valorant · Wild Rift
				</p>
				<h1 class="hero-title font-weight-bold mb-4">
					Climb with a pro.<br>Watch every step.
				</h1>
				<p class="text-body-large text-medium-emphasis mb-8" style="max-width: 560px">
					Pick a boost, see the price before you pay, and follow your order live: chat with your booster, pause when you want to play, and get notified when it's done.
				</p>
				<div class="d-flex flex-wrap ga-3">
					<v-btn to="/#games" color="primary" size="large" variant="flat" :append-icon="mdiArrowRight">
						Choose a boost
					</v-btn>
					<v-btn to="/about" size="large" variant="outlined">
						About this demo
					</v-btn>
				</div>
			</v-container>
		</section>

		<v-container id="games" class="site-width py-12">
			<h2 class="text-headline-small font-weight-bold mb-2">
				Pick a game
			</h2>
			<p class="text-body-medium text-medium-emphasis mb-6">
				Every service has its own order builder with a live price.
			</p>
			<v-row>
				<v-col v-for="game in games" :key="game.value" cols="12" md="4">
					<v-card class="h-100 game-card" :class="`game-${game.value}`" data-testid="game">
						<v-card-item :title="game.label" class="pt-5" />
						<v-card-text class="text-body-medium text-medium-emphasis pb-2">
							{{ BLURBS[game.value] }}
						</v-card-text>
						<v-list density="compact" bg-color="transparent">
							<v-list-item v-for="service in game.services" :key="service.key" :to="`/${service.key}`" :title="service.label" :append-icon="mdiArrowRight" />
						</v-list>
					</v-card>
				</v-col>
			</v-row>
		</v-container>

		<v-container class="site-width py-12">
			<h2 class="text-headline-small font-weight-bold mb-6">
				How it works
			</h2>
			<v-row>
				<v-col v-for="(step, index) in steps" :key="step.title" cols="12" md="4">
					<div class="step-number text-title-large font-weight-bold mb-3">
						{{ index + 1 }}
					</div>
					<h3 class="text-title-large mb-2">
						{{ step.title }}
					</h3>
					<p class="text-body-medium text-medium-emphasis">
						{{ step.text }}
					</p>
				</v-col>
			</v-row>
		</v-container>

		<v-container class="site-width py-12">
			<h2 class="text-headline-small font-weight-bold mb-6">
				Built around the customer
			</h2>
			<v-row>
				<v-col v-for="feature in features" :key="feature.title" cols="12" sm="6" md="4">
					<v-card class="h-100 pa-5" variant="flat">
						<v-icon :icon="feature.icon" color="primary" size="28" class="mb-3" />
						<h3 class="text-title-medium mb-1">
							{{ feature.title }}
						</h3>
						<p class="text-body-medium text-medium-emphasis">
							{{ feature.text }}
						</p>
					</v-card>
				</v-col>
			</v-row>
		</v-container>

		<v-container class="site-width py-12">
			<v-card class="pa-8 cta" color="secondary" variant="flat">
				<v-row align="center">
					<v-col cols="12" md="8">
						<h2 class="text-headline-small font-weight-bold mb-2">
							Try every side of it
						</h2>
						<p class="text-body-large">
							Sign in as a customer, a booster or an admin with the demo accounts on the login page. Open two browsers and watch an order move between them in real time.
						</p>
					</v-col>
					<v-col cols="12" md="4" class="d-flex flex-wrap ga-3 justify-md-end">
						<v-btn to="/login" color="surface" variant="flat" size="large">
							Demo accounts
						</v-btn>
						<v-btn to="/apply" variant="outlined" size="large">
							Become a booster
						</v-btn>
					</v-col>
				</v-row>
			</v-card>
		</v-container>
	</div>
</template>

<style scoped>
.site-width {
	max-width: 1200px;
}

/* Original art only (docs/adr/0004): soft colour fields and the logo's crown */
.hero {
	position: relative;
	overflow: hidden;
	background:
		radial-gradient(55% 70% at 10% 10%, rgba(var(--v-theme-primary), 0.35), transparent 70%),
		radial-gradient(45% 60% at 90% 20%, rgba(var(--v-theme-secondary), 0.5), transparent 70%),
		rgb(var(--v-theme-background));
}

.hero-crown {
	position: absolute;
	right: -4%;
	bottom: -18%;
	width: min(52vw, 560px);
	fill: rgba(var(--v-theme-primary), 0.12);
	transform: rotate(-12deg);
}

.hero-title {
	font-size: clamp(2.25rem, 5vw, 3.5rem);
	line-height: 1.1;
}

.game-card {
	border-top: 4px solid rgb(var(--v-theme-primary));
}

.step-number {
	display: grid;
	place-items: center;
	width: 44px;
	height: 44px;
	border-radius: 50%;
	background: rgba(var(--v-theme-primary), 0.16);
	color: rgb(var(--v-theme-primary));
}
</style>
