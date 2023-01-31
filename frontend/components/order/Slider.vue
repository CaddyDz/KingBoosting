<template>
	<div>
		<div class="title">
			<div class="title-id" v-show="step">
				<h2>{{ step }}</h2>
			</div>
			<h2 class="title-txt">{{ title }}</h2>
		</div>
		<v-card raised class="mb-4">
			<v-container class="container">
				<v-row>
					<v-col>
						<v-img :src="image" v-if="image" width="15%" />
					</v-col>
				</v-row>
				<v-slider v-model="amount" thumb-label="always" min="1" :max="max" height="130"></v-slider>
				<v-radio-group class="radio-group" v-model="queue" dark mandatory row v-if="showQueue">
					<v-radio label="Solo/Duo" value="Solo/Duo"></v-radio>
					<v-radio label="Flex 5v5" value="Flex"></v-radio>
				</v-radio-group>
			</v-container>
		</v-card>
	</div>
</template>

<script>
export default {
	props: {
		title: {
			type: String,
			required: false,
			default: "Select Your Number Of Wins",
		},
		image: {
			type: String,
			required: false,
			default: null,
		},
		showQueue: {
			type: Boolean,
			required: false,
			default: true,
		},
		step: {
			type: String,
			required: false,
			default: "2",
		},
		max: {
			type: Number,
			required: false,
			default: 10,
		},
	},
	data: () => ({
		queue: "Solo/Duo",
	}),
	computed: {
		amount: {
			get() {
				return this.$store.state.slider.amount;
			},
			set(value) {
				this.$store.commit("slider/changeAmount", value);
				this.$emit("amountChange");
			},
		},
	},
	watch: {
		queue(value) {
			this.$store.commit("slider/changeQueue", value);
		},
	},
};
</script>

<style scoped>
.container {
	display: flex;
	flex-direction: column;
	justify-content: center;
}

.radio-group {
	align-self: center;
	margin-top: 40px;
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
