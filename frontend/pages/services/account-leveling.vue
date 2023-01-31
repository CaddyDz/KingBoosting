<template>
	<v-row>
		<v-col cols="12" sm="6" md="7">
			<v-card raised class="mb-4">
				<v-container class="container">
					<!-- First we need to divide a row into two columns -->
					<v-row>
						<v-col>
							<v-btn color="primary" elevation="24" icon outlined x-large @click="decreaseCurrent">
								<v-icon>mdi-minus</v-icon>
							</v-btn>
							{{ currentLevel }}
							<v-btn color="primary" elevation="24" icon outlined x-large @click="increaseCurrent">
								<v-icon>mdi-plus</v-icon>
							</v-btn>
						</v-col>
						<v-col>
							<v-btn color="primary" elevation="24" icon outlined x-large @click="decreaseDesired">
								<v-icon>mdi-minus</v-icon>
							</v-btn>
							{{ desiredLevel }}
							<v-btn color="primary" elevation="24" icon outlined x-large @click="increaseDesired">
								<v-icon>mdi-plus</v-icon>
							</v-btn>
						</v-col>
					</v-row>
				</v-container>
			</v-card>
		</v-col>
		<v-col cols="6" md="5">
			<checkout :options="options" />
			<boost-me />
		</v-col>
	</v-row>
</template>

<script>
import levels from "~/assets/js/account-leveling/levels";
import options from "~/assets/js/account-leveling/options";

export default {
	layout: "order",
	data: () => ({
		levels: levels,
		options: options,
		currentLevel: 2,
		desiredLevel: 30,
	}),
	watch: {
		currentLevel() {
			this.changePrice();
		},
		desiredLevel() {
			this.changePrice();
		},
	},
	methods: {
		increaseCurrent() {
			if (this.currentLevel >= 29) return;
			this.currentLevel++;
		},
		decreaseCurrent() {
			if (this.currentLevel <= 2) return;
			this.currentLevel--;
		},
		increaseDesired() {
			if (this.desiredLevel >= 30) return;
			this.desiredLevel++;
		},
		decreaseDesired() {
			if (this.desiredLevel <= 3) return;
			this.desiredLevel--;
		},
		changePrice() {
			let filteredLevels = _.filter(this.levels, (level) =>
				// This is a whereIn replacement
				_.range(this.desiredLevel, this.currentLevel).includes(
					level.level
				)
			);
			// Sum up their prices using JS reduce
			let total = filteredLevels.reduce((sum, level) => {
				return sum + level.price;
			}, 0);
			// Get the current level and desired level
			// Accumulate the prices based on the range from lodash
			this.$store.commit("price/changePrice", total);
		},
	},
	mounted() {
		this.changePrice();
	},
};
</script>
