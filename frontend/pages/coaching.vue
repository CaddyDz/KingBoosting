<template>
	<div>
		<div class="coaching" fluid>
			<v-container>
				<v-row align="end" justify="space-between" style="height: 150px">
					<v-col md="4">
						<p class="text-h3">LoL Coaching</p>
						<p class="font-weight-bold">
							Coaching is an efficient way to improve your skills and mindset in
							LoL. We provide experienced high elo players at your service.
						</p>
					</v-col>
					<v-col cols="2">
						<v-btn color="primary" depressed x-large to="/boosters">Discover Coaches</v-btn>
					</v-col>
				</v-row>
			</v-container>
		</div>
		<customers-voice />
		<v-container>
			<v-row>
				<v-col md="4">
					<v-card class="mx-auto my-12">
						<v-img height="250" src="/img/coaching/professor-ryze.png"></v-img>
						<v-card-title>Cafe Badilico</v-card-title>
						<v-card-text>
							<v-row align="center" class="mx-0">
								<v-rating :value="4.5" color="amber" dense half-increments readonly size="14"></v-rating>
								<div class="grey--text ml-4">4.5 (413)</div>
							</v-row>
							<div class="my-4 subtitle-1">$ • Italian, Cafe</div>
							<div>
								Small plates, salads & sandwiches - an intimate setting with 12
								indoor seats plus patio seating.
							</div>
						</v-card-text>
						<v-divider class="mx-4"></v-divider>
						<v-card-title>Tonight's availability</v-card-title>
						<v-card-text>
							<v-chip-group active-class="deep-purple accent-4 white--text" column>
								<v-chip>5:30PM</v-chip>
								<v-chip>7:30PM</v-chip>
								<v-chip>8:00PM</v-chip>
								<v-chip>9:00PM</v-chip>
							</v-chip-group>
						</v-card-text>
					</v-card>
				</v-col>
				<v-col></v-col>
			</v-row>
		</v-container>
	</div>
</template>

<style scoped>
.coaching {
	background-image: url("/img/backgrounds/coaching.png");
	background-position: top;
	background-size: cover;
	min-height: 440px;
}
</style>
