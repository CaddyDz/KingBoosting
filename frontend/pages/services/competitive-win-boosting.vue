<template>
	<v-row>
		<v-col cols="12" sm="6" md="7">
			<Current title="Current Rank" description="Please select your Current Rank and Division" showServerSelection :tiers="ranks" />
			<slider title step :showQueue="false" />
		</v-col>
		<v-col cols="6" md="5">
			<checkout :options="options" />
			<boost-me />
		</v-col>
	</v-row>
</template>

<script>
import ranks from "~/assets/js/competitive-win-boosting/ranks";
import options from "~/assets/js/competitive-win-boosting/options";

export default {
	layout: "order",
	data: () => ({
		ranks: ranks,
		options: options,
	}),
	mounted() {
		this.$store.commit("slider/changeAmount", 5);
		this.$store.commit("valorant/current/changeDivision", 9);
	},
	beforeDestroy() {
		this.$store.commit("slider/changeAmount", 1);
	},
};
</script>
