<template>
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
</template>

<script>
export default {
	data() {
		return {
			dialog: false
		};
	},
	methods: {
		order() {
			this.commitToStore({
				specificChampions: this.checkBox[0].checkbox,
				priorityOrder: this.checkBox[1].checkbox,
				streaming: this.checkBox[2].checkbox,
				discountCode: this.discountCode
			});
		},
		closeDialog() {
			// this.on=false
		}
	}
};
</script>

<style scoped>
.boost-me-card {
	background: linear-gradient(to right, #673ab7, #9c27b0);
}

.boost-me {
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: space-between;
	flex: 1;
}

.align-center {
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
}
</style>
