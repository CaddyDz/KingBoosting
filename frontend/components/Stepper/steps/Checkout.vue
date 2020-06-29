<template>
	<div>
		<div class="title">
			<div class="title-id">
				<h2>3</h2>
			</div>
			<h2 class="title-txt">Checkout</h2>
		</div>
		<v-card raised class="mb-4">
			<v-container class="container">
				<v-row>
					<v-col class="left-col">
						<div class="align-center-to-left" v-for="item in checkBox" :key="item.id">
							<v-checkbox v-model="item.checkbox" @change="checkBoxChangeSteteHandler(item.id)"></v-checkbox>
							<v-icon color="#673ab7">{{ item.icon }}</v-icon>
							<p>{{ item.title }}</p>
						</div>
					</v-col>
					<v-col class="right-col">
						<h5>Approximate completion time: {{ eta }}</h5>
						<v-col class="input" md="8">
							<v-text-field
								label="Discount Code"
								:value="discountCode"
								@change="inputCodeChangeHandler($event)"
								outlined
								clearable
								dark
								append-icon="mdi-check"
							></v-text-field>
						</v-col>
					</v-col>
				</v-row>
			</v-container>
		</v-card>
		<BoostMe />
	</div>
</template>

<script>
export default {
	data() {
		return {
			discountCode: "",
			checkBox: [
				{
					id: 0,
					checkbox: false,
					icon: "mdi-account-supervisor",
					title: "Specific champions at +20% cost"
				},
				{
					id: 1,
					checkbox: false,
					icon: "mdi-flash",
					title: "Priority order (2x speed) at +25% cost"
				},
				{
					id: 2,
					checkbox: false,
					icon: "mdi-camcorder",
					title: "With Streaming +15% cost"
				}
			]
		};
	},
	computed: {
		eta() {
			return this.$store.getters["league/eta"];
		}
	},
	methods: {
		commitToStore(c) {
			this.$store.commit("boosting_order/setOptions", c);
		},
		checkBoxChangeSteteHandler(e) {
			this.checkBox[e].checkbox = !this.checkBox[e].checkbox;
		},
		inputCodeChangeHandler(e) {
			this.discountCode = e;
		}
	}
};
</script>

<style scoped>
.container {
	display: flex;
	flex-direction: column;
	justify-content: center;
}

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

.right-col {
	display: flex;
	flex-direction: column;
	align-items: flex-end;
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
