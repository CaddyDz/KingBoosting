<template>
	<div class="customer">
		<div class="customer-container-title">
			<v-icon class="shield-icon have-linear-gradient">mdi-message-text-outline</v-icon>
			<span class="display-1 font-weight-bold">
				{{ $t('Customer') }}
				<span class="have-linear-gradient">{{ $t('Voice') }}</span>
			</span>
			<span class="headline font-weight-bold have-linear-gradient">Excellent</span>
			<span class="d-block">
				4.77 Rating
				<span class="grey--text">4006 Review</span>
			</span>
		</div>

		<div class="text-center customer-slider">
			<v-container>
				<v-slide-group v-model="model" class="pa-4" active-class="success" show-arrows>
					<v-slide-item v-for="n in 20" :key="n">
						<v-col class="custom-bg">
							<v-card class="mx-auto" dark max-width="400">
								<v-card-text
									class="headline font-weight-bold"
								>Lorem ipsum dolor sit amet consectetur adipisicing elit.</v-card-text>
								<v-card-actions>
									<v-list-item class="grow">
										<v-list-item-avatar color="grey darken-3">
											<v-img
												class="elevation-6"
												src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
											></v-img>
										</v-list-item-avatar>
										<v-list-item-content class="customer-comment" justify="start">
											<v-list-item-title>Evan You</v-list-item-title>
										</v-list-item-content>
										<v-row align="center" justify="end">
											<v-icon class="mr-1" color="green">mdi-check-circle-outline</v-icon>
										</v-row>
									</v-list-item>
								</v-card-actions>
							</v-card>
						</v-col>
					</v-slide-item>
				</v-slide-group>
			</v-container>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			model: null
		};
	}
};
</script>

<style scoped>
.customer {
	background-color: #212224;
	padding: 50px;
}
.card-customer {
	background-color: #212427;
}
.custom-bg {
	background-image: linear-gradient(to right, #673ab7, #9c27b0);
	padding: 5px;
	margin: 10px;
	border-radius: 10px;
}
.customer-comment {
	text-align: left;
	margin: 0;
}
.customer-container-title {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
}
.have-linear-gradient {
	background: -webkit-linear-gradient(#673ab7, #9c27b0);
	background-clip: text;
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
}
.shield-icon {
	font-size: 120px;
}
</style>

<i18n>
{
  "en": {
    "Customer": "Customer",
    "Voice": "Voice"
  },
  "fr": {
    "Customer": "Client",
    "Voice": "Voix"
  }
}
</i18n>
