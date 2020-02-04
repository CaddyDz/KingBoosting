<template>
	<div>
		<v-stepper-step step="3">Checkout</v-stepper-step>

		<v-stepper-content step="1">
			<v-card color="black lighten-1" class="mb-12" outlined>
				<v-container>
					<!-- Checkboxes and ETA -->
					<v-row no-gutters>
						<v-col md="6">
							<v-checkbox label="Specific champions at +20% cost" prepend-icon="mdi-account-group"></v-checkbox>
							<v-checkbox label="Priority order (2x speed) at +25% cost" prepend-icon="mdi-flash"></v-checkbox>
							<v-checkbox label="With Streaming +15% cost" prepend-icon="mdi-video"></v-checkbox>
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
	</div>
</template>
