<template>
	<v-row>
		<v-col cols="12" sm="6" md="7">
			<slider title="Select Your Number Of Games" :showQueue="false" />
		</v-col>
		<v-col cols="6" md="5">
			<checkout :options="options" />
			<boost-me />
		</v-col>
	</v-row>
</template>

<script>
import options from "~/assets/js/wildrift-normal-matches/options";

export default {
	layout: "order",
	data: () => ({
		options: options,
		service: "Wild Rift Normal Matches",
	}),
	mounted() {
		this.$store.commit("slider/changeAmount", 4);
	},
	beforeDestroy() {
		this.$store.commit("slider/changeAmount", 1);
	},
};
</script>
