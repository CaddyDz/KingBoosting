<script setup lang="ts">
const columns = [
	{ title: 'Store', links: [{ title: 'Boosts', to: '/#games' }, { title: 'Boosters', to: '/boosters' }, { title: 'Your orders', to: '/dashboard' }] },
	{ title: 'Company', links: [{ title: 'About this demo', to: '/about' }, { title: 'Become a booster', to: '/apply' }, { title: 'Contact', to: '/contact' }] },
	{ title: 'Legal', links: [{ title: 'Terms of use', to: '/terms' }, { title: 'Privacy', to: '/privacy' }] },
]
</script>

<template>
	<footer class="site-footer mt-16">
		<v-container class="py-10" style="max-width: 1200px">
			<v-row>
				<v-col cols="12" md="5">
					<NuxtLink to="/" class="d-inline-flex align-center ga-2 text-decoration-none mb-3">
						<img src="/favicon.svg" alt="" width="32" height="32">
						<span class="text-title-medium font-weight-bold">King Boosting</span>
					</NuxtLink>
					<p class="text-body-medium text-medium-emphasis" style="max-width: 360px">
						An order marketplace for game boosts, rebuilt as a portfolio piece. Nothing is sold, and payments run in Stripe's test mode.
					</p>
				</v-col>
				<v-col v-for="column in columns" :key="column.title" cols="6" sm="4" md="2" :offset-md="column.title === 'Store' ? 1 : 0">
					<div class="text-title-small mb-3">
						{{ column.title }}
					</div>
					<ul class="footer-links">
						<li v-for="link in column.links" :key="link.to">
							<NuxtLink :to="link.to" class="text-body-medium text-medium-emphasis">
								{{ link.title }}
							</NuxtLink>
						</li>
					</ul>
				</v-col>
			</v-row>
			<v-divider class="my-6" />
			<SiteDemoNotice class="mb-2" />
			<p class="text-body-small text-medium-emphasis">
				League of Legends, Valorant and Wild Rift are trademarks of Riot Games, Inc. King Boosting isn't endorsed by Riot Games and doesn't reflect its views.
			</p>
		</v-container>
	</footer>
</template>

<style scoped>
.site-footer {
	border-top: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
	background: rgb(var(--v-theme-surface));
}

.footer-links {
	list-style: none;
	padding: 0;
}

.footer-links li {
	margin-bottom: 0.5rem;
}

.footer-links a {
	text-decoration: none;
}

.footer-links a:hover {
	color: rgb(var(--v-theme-primary)) !important;
}
</style>
