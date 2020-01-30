<template>
	<v-stepper v-model="e6" vertical non-linear>
		<v-stepper-step :complete="e6 > 1" step="1" editable>Select Your Current League</v-stepper-step>

		<v-stepper-content step="1">
			<v-card raised class="mb-4" elevation="24">
				<v-container>
					<v-row align="center" justify="center">
						<v-col md="3">
							<img src="/img/divisions/normal.png" alt />
						</v-col>
						<v-col md="9">
							<v-row>
								<v-col>
									<v-select :items="items" label="Current tier" :value="items[0]"></v-select>
								</v-col>
								<v-col>
									<v-select :items="items" label="Current division" :value="items[0]"></v-select>
								</v-col>
							</v-row>
							<v-select :items="items" label="Select your server" :value="items[0]"></v-select>
						</v-col>
					</v-row>
				</v-container>
			</v-card>
		</v-stepper-content>

		<v-stepper-step :complete="e6 > 1" step="1" editable>Select Your Number Of Wins</v-stepper-step>

		<v-stepper-content step="1">
			<v-card raised class="mb-4" elevation="24">
				<v-container>
					<v-row justify="center" align="center">
						<v-col md="2"></v-col>
						<v-col md="8">
							<number-of-wins :wins="wins"></number-of-wins>
						</v-col>
						<v-col md="2"></v-col>
					</v-row>
					<v-row>
						<v-col md="3"></v-col>
						<v-col md="6">
							<v-radio-group row v-model="radios">
								<v-icon>mdi-account</v-icon>
								<v-radio label="Solo/Duo" value="solo"></v-radio>
								<v-icon>mdi-account-group</v-icon>
								<v-radio label="Flex 5v5" value="flex"></v-radio>
							</v-radio-group>
						</v-col>
						<v-col md="3"></v-col>
					</v-row>
				</v-container>
			</v-card>
		</v-stepper-content>

		<v-stepper-step :complete="e6 > 1" step="1" editable>Checkout</v-stepper-step>

		<v-stepper-content step="1">
			<v-card color="black lighten-1" class="mb-12" outlined>
				<v-container>
					<!-- Checkboxes and ETA -->
					<v-row no-gutters>
						<v-col md="6">
							<v-checkbox label="Specific champions at +20% cost" prepend-icon="mdi-account-group"></v-checkbox>
							<v-checkbox label="Priority order (2x speed) at +25% cost" prepend-icon="mdi-account-group"></v-checkbox>
							<v-checkbox label="With Streaming +15% cost" prepend-icon="mdi-account-group"></v-checkbox>
						</v-col>
						<v-col md="6">
							<!-- ETA -->
							<p class="text-right">Approximate completion time: 0-1 days</p>
						</v-col>
					</v-row>
					<v-row>
						<v-col md="4">
							<strong class="red--text text--lighten-1">
								<s>€24.00($26.66)</s>
							</strong>
							<br />€13.90 ($15.44) OR 1,390
						</v-col>
						<v-col md="4">
							<v-text-field
								label="Discount Code"
								placeholder="Discount Code"
								outlined
								append-icon="mdi-check-bold"
							></v-text-field>
						</v-col>
						<v-col md="4">
							<v-btn class="float-right" outlined color="purple">BOOST ME</v-btn>
						</v-col>
					</v-row>
					<!-- Price and discount -->
					<v-row></v-row>
				</v-container>
			</v-card>
		</v-stepper-content>
	</v-stepper>
</template>
<script>
export default {
	data() {
		return {
			e6: 1,
			items: ["Foo", "Bar", "Fizz", "Buzz"],
			wins: 1,
			radios: "solo"
		};
	}
};
</script>


