// This entire component is in AppBarComponent.vue
<template>
	<v-container fluid>
		<v-row>
			<!-- Necessary to finish 12 -->
			<v-col md="2"></v-col>
			<v-col md="2">
				<v-menu transition="slide-y-transition" min-width="100%" open-on-hover>
					<template v-slot:activator="{on}">
						<a href="/regular-boosting" v-on="on" class="link">Boosting</a>
					</template>
					<v-row align="center" justify="center">
						<v-col md="3">
							<a href="/regular-boosting">
								<img src="/images/tft.png" alt />
								<!-- Vuetify Built-in class -->
								<p class="text-center">Regular Boosting</p>
							</a>
						</v-col>
						<v-col md="3">
							<a href="/regular-boosting">
								<img src="/images/tft.png" alt />
								<p class="text-center">Regular Boosting</p>
							</a>
						</v-col>
					</v-row>
				</v-menu>
			</v-col>
			<v-col md="2">
				<a href="#" title="Coaching" class="link">Coaching</a>
			</v-col>
			<v-col md="2">
				<a href="#" title="Lol Accounts" class="link">Lol Accounts</a>
			</v-col>
			<v-col md="2">
				<a href="#" title="Boosters" class="link">Boosters</a>
			</v-col>
			<v-col md="2">
				<a href="#" title="More*" class="link">More*</a>
			</v-col>
		</v-row>
	</v-container>
</template>

<style scoped>
/* Make the background color of the menu content dark */
.theme--dark {
	background: rgb(0, 0, 0);
	background: linear-gradient(
		180deg,
		rgba(0, 0, 0, 0) 0%,
		rgba(18, 4, 17, 1) 15%,
		rgba(18, 4, 17, 1) 15%,
		rgba(18, 4, 17, 1) 87%,
		rgba(18, 4, 17, 0) 87%
	);
}
/* The purple underground shade */
img:hover {
	background-image: url("/img/service_nav_bg.png");
	background-position: center bottom;
}
/* Remove stubborn underline */
a {
	text-decoration: none !important;
}
/* Make the text above the underground purple shade */
p {
	position: relative;
	bottom: 40px;
}
/* Add underline manually to navbar links text */
.link {
	padding-bottom: 6px;
	border-bottom: 3px solid currentColor;
}
</style>
