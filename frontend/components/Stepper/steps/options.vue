<template>
	<div>
		<div class="title">
			<div class="title-id">
				<h2>{{ step.id }}</h2>
			</div>
			<h2 class="title-txt">{{ step.title }}</h2>
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
						<h5>Approximate completion time: 1-2 days</h5>
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
				<div></div>
			</v-container>
		</v-card>
		<v-card class="boost-me-card">
			<v-container class="boost-me">
				<div class="align-center">
					<v-icon size="50">mdi-currency-eur</v-icon>
					<h2>61.90 ($68.86)</h2>
				</div>
				<v-dialog v-model="dialog" width="940">
					<template v-slot:activator="{ on }">
						<v-btn class="ma-2" outlined @click="order()" v-on="on">
							<v-icon left>mdi-shopping-outline</v-icon>Boost Me
						</v-btn>
					</template>
					<!-- Entire stepper -->
					<post-order @closeDialog="closeDialog" />
				</v-dialog>
				<!-- end of Test -->
			</v-container>
		</v-card>
	</div>
</template>

<script>
export default {
	props: ["step"],
	data() {
		return {
			dialog: false,
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
	methods: {
		closeDialog() {
			// this.on=false
		},
		commitToStore(c) {
			this.$store.commit("boosting_order/setOptions", c);
		},
		checkBoxChangeSteteHandler(e) {
			this.checkBox[e].checkbox = !this.checkBox[e].checkbox;
		},
		inputCodeChangeHandler(e) {
			this.discountCode = e;
		},
		order() {
			this.commitToStore({
				specificChampions: this.checkBox[0].checkbox,
				priorityOrder: this.checkBox[1].checkbox,
				streaming: this.checkBox[2].checkbox,
				discountCode: this.discountCode
			});
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

.align-center {
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
}

.align-center-to-left {
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: center;
}

.boost-me-card {
	background: linear-gradient(to right, #673ab7, #9c27b0);
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

.boost-me {
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: space-between;
	flex: 1;
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
