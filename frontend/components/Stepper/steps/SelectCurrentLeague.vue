<template>
	<div>
		<div class="title">
			<div class="title-id">
				<h2>1</h2>
			</div>
			<h2 class="title-txt">Select Your Current League</h2>
		</div>
		<v-card raised class="mb-4" :style="{boxShadow: tier.box_shadow}">
			<v-container>
				<v-row align="center" justify="center">
					<v-col md="3">
						<img :src="division.image || tier.image" :alt="tier.name" loading="lazy" width="200" />
					</v-col>
					<v-col md="9" class="have-selectors">
						<v-container>
							<v-row>
								<v-col>
									<v-select
										:items="tiers"
										label="Current tier"
										dense
										solo
										v-model="selectedTierID"
										item-text="name"
										item-value="id"
									></v-select>
								</v-col>
								<v-col v-if="hasDivisions">
									<v-select
										:items="tier.divisions"
										label="Current division"
										dense
										solo
										v-model="selectedDivisionID"
										item-text="name"
										item-value="id"
									></v-select>
								</v-col>
							</v-row>
							<v-select
								:items="servers"
								label="Select your server"
								dense
								solo
								v-model="selectedServerID"
								item-text="region"
								item-value="id"
							></v-select>
						</v-container>
					</v-col>
				</v-row>
			</v-container>
		</v-card>
	</div>
</template>

<script>
export default {
	data: () => ( {
			tiers: [
				{
					id: 1,
					name: "Iron",
					box_shadow:
						"inset 60px 0 120px rgba(152,129,108,.2), 0 0 9px 0 rgba(0,0,0,.6)",
					divisions: [
						{
							id: 1,
							image: "/img/divisions/iron/IV.webp",
							price: 1.9,
							name: "IV"
						},
						{
							id: 2,
							image: "/img/divisions/iron/III.png",
							price: 1.9,
							name: "III"
						},
						{
							id: 3,
							image: "/img/divisions/iron/II.png",
							price: 1.9,
							name: "II"
						},
						{
							id: 4,
							image: "/img/divisions/iron/I.png",
							price: 1.9,
							name: "I"
						}
					]
				},
				{
					id: 2,
					name: "Bronze",
					box_shadow:
						"inset 60px 0 120px rgba(89,60,28,.2), 0 0 9px 0 rgba(0,0,0,.6)",
					divisions: [
						{
							id: 5,
							image: "/img/divisions/bronze/IV.png",
							price: 1.9,
							name: "IV"
						},
						{
							id: 6,
							image: "/img/divisions/bronze/III.png",
							price: 1.9,
							name: "III"
						},
						{
							id: 7,
							image: "/img/divisions/bronze/II.png",
							price: 1.9,
							name: "II"
						},
						{
							id: 8,
							image: "/img/divisions/bronze/I.png",
							price: 1.9,
							name: "I"
						}
					]
				},
				{
					id: 3,
					name: "Silver",
					box_shadow:
						"inset 60px 0 120px rgba(119,134,131,.2), 0 0 9px 0 rgba(0,0,0,.6)",
					divisions: [
						{
							id: 9,
							image: "/img/divisions/silver/IV.webp",
							price: 1.9,
							name: "IV"
						},
						{
							id: 10,
							image: "/img/divisions/silver/III.webp",
							price: 2.9,
							name: "III"
						},
						{
							id: 11,
							image: "/img/divisions/silver/II.webp",
							price: 2.9,
							name: "II"
						},
						{
							id: 12,
							image: "/img/divisions/silver/I.webp",
							price: 3.9,
							name: "I"
						}
					]
				},
				{
					id: 4,
					name: "Gold",
					box_shadow:
						"inset 60px 0 120px rgba(228,189,62,.2), 0 0 9px 0 rgba(0,0,0,.6)",
					divisions: [
						{
							id: 13,
							image: "/img/divisions/gold/IV.webp",
							price: 3.9,
							name: "IV"
						},
						{
							id: 14,
							image: "/img/divisions/gold/III.webp",
							price: 4.9,
							name: "III"
						},
						{
							id: 15,
							image: "/img/divisions/gold/II.webp",
							price: 4.9,
							name: "II"
						},
						{
							id: 16,
							image: "/img/divisions/gold/I.webp",
							price: 5.9,
							name: "I"
						}
					]
				},
				{
					id: 5,
					name: "Platinum",
					box_shadow:
						"inset 60px 0 120px rgba(57,123,135,.2), 0 0 9px 0 rgba(0,0,0,.6)",
					divisions: [
						{
							id: 17,
							image: "/img/divisions/platinum/IV.webp",
							price: 6.9,
							name: "IV"
						},
						{
							id: 18,
							image: "/img/divisions/platinum/III.webp",
							price: 6.9,
							name: "III"
						},
						{
							id: 19,
							image: "/img/divisions/platinum/II.webp",
							price: 7.9,
							name: "II"
						},
						{
							id: 20,
							image: "/img/divisions/platinum/I.webp",
							price: 9.9,
							name: "I"
						}
					]
				},
				{
					id: 6,
					name: "Diamond",
					box_shadow:
						"inset 60px 0 120px rgba(48,113,222,.2), 0 0 9px 0 rgba(0,0,0,.6)",
					divisions: [
						{
							id: 21,
							image: "/img/divisions/diamond/IV.webp",
							price: 12.9,
							name: "IV"
						},
						{
							id: 22,
							image: "/img/divisions/diamond/III.webp",
							price: 16.9,
							name: "III"
						},
						{
							id: 23,
							image: "/img/divisions/diamond/II.webp",
							price: 21.9,
							name: "II"
						},
						{
							id: 24,
							image: "/img/divisions/diamond/I.webp",
							price: 22.9,
							name: "I"
						}
					]
				},
				{
					id: 7,
					name: "Master",
					box_shadow:
						"inset 60px 0 120px rgba(128,29,161,.2), 0 0 9px 0 rgba(0,0,0,.6)",
					image: "/img/divisions/master.webp",
					price: 26.9
				},
				{
					id: 8,
					name: "Grandmaster",
					box_shadow:
						"inset 60px 0 120px rgba(161,29,29,.2), 0 0 9px 0 rgba(0,0,0,.6)",
					image: "/img/divisions/grandmaster.webp",
					price: 29.9
				},
				{
					id: 9,
					name: "Challenger",
					box_shadow:
						"inset 60px 0 120px rgba(161,113,29,.2), 0 0 9px 0 rgba(0,0,0,.6)",
					image: "/img/divisions/challenger.webp",
					price: 32.9
				}
			],
			selectedTierID: 3, // Silver
			selectedDivisionID: 12, // Silver I
			selectedServerID: "EU-West",
			servers: [
				"North America",
				"EU-West",
				"EU-Nordic & East",
				"Turkey",
				"Russia",
				"Brazil",
				"Latin America North",
				"Latin America South",
				"Oceania",
				"PBE"
			]
	}),
	computed: {
		tier() {
			let index = this.$store.state.league.tier;
			return _.find(this.tiers, ["id", index]);
		},
		division() {
			if (this.hasDivisions) {
				return _.find(this.tier.divisions, ["id", this.selectedDivisionID]);
			}
			return {image:null};
		},
		hasDivisions() {
			return !_.isEmpty(this.tier.divisions);
		}
	},
	watch: {
		selectedTierID(id) {
			this.$store.commit("league/changeTier", id);
			if (this.hasDivisions) {
				this.selectedDivisionID = this.tier.divisions[3].id;
			}
		}
	}
};
</script>

<style scoped>
.have-selectors {
	padding-right: 20px;
	padding-left: 20px;
}

.title {
	display: flex;
	flex-direction: row;
	align-items: center;
	margin-bottom: 20px;
}

.title-txt {
	margin-left: 20px;
}

.title .title-id {
	color: #181818;
}

.title-id {
	width: 50px;
	height: 50px;
	border-radius: 50%;
	background: linear-gradient(#673ab7, #9c27b0);
	display: flex;
	align-items: center;
	justify-content: center;
}
</style>
