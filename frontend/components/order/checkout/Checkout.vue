<template>
	<div>
		<div class="title" v-if="showTitle">
			<div class="title-id">
				<h2>3</h2>
			</div>
			<h2 class="title-txt">Checkout</h2>
		</div>
		<v-card raised class="mb-4">
			<v-container>
				<v-row>
					<v-col md="10">
						<div v-for="(checkbox, index) in options" :key="index">
							<v-tooltip top color="primary" max-width="350">
								<template v-slot:activator="{ on, attrs }">
									<v-switch v-model="checkbox.checked" @change="checkOption(checkbox)">
										<template v-slot:label>
											<div v-bind="attrs" v-on="on">
												<v-icon color="#673ab7" class="mr-1">{{ checkbox.icon }}</v-icon>
												<v-badge :color="checkbox.percentage ? 'primary' : 'green'" :content="badgeContent(checkbox.percentage)" class="text-uppercase">{{ checkbox.title }}</v-badge>
											</div>
										</template>
									</v-switch>
								</template>
								<span>{{ checkbox.tip }}</span>
							</v-tooltip>
						</div>
						<h5>Approximate completion time: {{ eta }}</h5>
					</v-col>
					<v-col class="input" md="8">
						<v-text-field label="Discount Code" v-model="discountCode" outlined clearable dark append-icon="mdi-check"></v-text-field>
					</v-col>
				</v-row>
			</v-container>
		</v-card>
	</div>
</template>

<script>
export default {
	props: {
		options: {
			type: Array,
			required: true,
		},
		showTitle: {
			type: Boolean,
			required: false,
			default: true,
		},
	},
	data: () => ({
		discountCode: "",
	}),
	computed: {
		eta() {
			let wins = this.$store.state.slider.amount;
			let days = parseInt(wins / 2);
			return `${days} - ${days + 1} Days`;
		},
	},
	watch: {
		discountCode(value) {
			this.$store.commit("checkout/changeDiscountCode", value);
		},
	},
	methods: {
		checkOption(checkbox) {
			if (checkbox.checked) {
				this.$store.commit("checkout/addOption", checkbox.title);
				this.$store.commit("checkout/addPercent", checkbox.percentage);
			} else {
				this.$store.commit("checkout/removeOption", checkbox.title);
				this.$store.commit(
					"checkout/substractPercent",
					checkbox.percentage
				);
			}
		},
		badgeContent(percentage) {
			switch (true) {
				case percentage > 0:
					return `+${percentage}%`;
				case percentage < 0:
					return `${percentage}%`;
				default:
					return "Free";
			}
		},
	},
	mounted() {
		this.$store.commit("checkout/clearOptions");
	},
};
</script>

<style scoped>
.align-center-to-left {
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: center;
}

p {
	margin-bottom: 0;
	margin-left: 10px;
}

h5 {
	margin-bottom: 0;
	margin-right: 15px;
}

h2 {
	margin: 0;
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
