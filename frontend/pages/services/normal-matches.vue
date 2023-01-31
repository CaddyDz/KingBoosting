<template>
	<v-row>
		<v-col cols="12" sm="6" md="7">
			<slider title="Select Your Number Of Games" :showQueue="false" image="/img/divisions/normal.png" :step="null" :max="30" />
		</v-col>
		<v-col cols="6" md="5">
			<checkout :options="options" />
			<boost-me />
		</v-col>
	</v-row>
</template>

<script>
import options from "~/assets/js/normal-matches/options";

export default {
	layout: "order",
	data: () => ({
		options: options,
	}),
	mounted() {
		this.$store.commit("slider/changeAmount", 15);
		this.$store.commit("price/changePrice", 2.84);
	},
	beforeDestroy() {
		this.$store.commit("slider/changeAmount", 1);
	},
};
</script>
