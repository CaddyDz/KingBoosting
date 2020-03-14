<template>
	<v-row>
		<v-col class="left-container" md="8">
			<v-list>
				<v-list-item v-for="step in steps" :key="step.id">
					<v-list-item-avatar>
						<v-icon color="grey" class="icon have-gradient" size="40">{{step.icon}}</v-icon>
					</v-list-item-avatar>
					<v-list-item-content>
						<v-list-item-title v-text="step.step"></v-list-item-title>
					</v-list-item-content>
				</v-list-item>
			</v-list>
		</v-col>
		<v-col class="right-container" md="4" @click="showHideOverlay">
			<div class="watch-video">
				<v-icon size="400px">mdi-play-circle-outline</v-icon>
			</div>
			<div class="how-to-buy-icon">
				<v-icon class="icon" size="100px">mdi-shopping</v-icon>
				<h1>How To Buy</h1>
			</div>
			<v-overlay :value="overlay">
				<v-btn icon @click="showHideOverlay">
					<v-icon>mdi-close</v-icon>
				</v-btn>
			</v-overlay>
		</v-col>
	</v-row>
</template>

<script>
export default {
	data: () => {
		return {
			overlay: false,
			steps: [
				{
					id: 1,
					step: this.$i18n.t("Select the details of your purchase."),
					icon: "mdi-check-circle-outline"
				},
				{
					id: 2,
					step: this.$i18n.t(
						"After clicking the 'Buy Boost' button, you can either continue as a guest or as a returning customer."
					),
					icon: "mdi-login-variant"
				},
				{
					id: 3,
					step: this.$i18n.t(
						"By clicking the 'Purchase' button, you will be redirected to the chosen payment gateway."
					),
					icon: "mdi-cash-usd"
				},
				{
					id: 4,
					step: yhis.i18n.t(
						"In the members area you can track your order, spectate, pause or chat live with your booster!"
					),
					icon: "mdi-ticket"
				}
			]
		};
	},
	methods: {
		showHideOverlay() {
			this.overlay = !this.overlay;
		}
	}
};
</script>

<style>
.right-container {
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: center;
	background-image: linear-gradient(to right, #673ab7, #9c27b0);
	position: relative;
	overflow: hidden;
}

.left-container {
	background-color: rgb(30, 30, 31);
	padding: 80px;
}

.have-gradient {
	background: -webkit-linear-gradient(#673ab7, #9c27b0);
	background-clip: text;
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
}

h4 {
	margin: 0;
}

h4:hover {
	background-image: linear-gradient(#673ab7, #9c27b0);
}

.how-to-buy-icon {
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	position: absolute;
	transition: 0.6s;
	transform: translate3d(0, 0, 0);
	backface-visibility: hidden;
	animation-name: shakeMe;
	animation-duration: 4s;
	animation-iteration-count: infinite;
	animation-timing-function: linear;
}

.watch-video {
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	position: absolute;
	left: -400px;
	transition: 0.6s;
}

.right-container:hover .watch-video {
	left: -130px;
}

.right-container:hover .how-to-buy-icon {
	opacity: 0;
}

@keyframes shakeMe {
	2%,
	18% {
		transform: translate3d(-5px, 0, 0);
	}

	4%,
	16% {
		transform: translate3d(5px, 0, 0);
	}

	6%,
	10%,
	14% {
		transform: translate3d(-5px, 0, 0);
	}

	8%,
	12% {
		transform: translate3d(5px, 0, 0);
	}

	18.1% {
		transform: translate3d(0px, 0, 0);
	}
}
</style>
<i18n>
{
  "en": {
    "How To Buy": "How To Buy",
    "Select the details of your purchase.": "Select the details of your purchase.",
    "After clicking the 'Buy Boost' button, you can either continue as a guest or as a returning customer.": "After clicking the 'Buy Boost' button, you can either continue as a guest or as a returning customer.",
    "By clicking the 'Purchase' button, you will be redirected to the chosen payment gateway.": "By clicking the 'Purchase' button, you will be redirected to the chosen payment gateway.",
    "In the members area you can track your order, spectate, pause or chat live with your booster!": "In the members area you can track your order, spectate, pause or chat live with your booster!"
  },
  "fr": {
    "How To Buy": "Comment Acheter",
    "Select the details of your purchase": "Sélectionnez les détails de votre achat",
    "After clicking the 'Buy Boost' button, you can either continue as a guest or as a returning customer.": "Après avoir cliqué sur le bouton "Buy Boost", vous pouvez continuer en tant qu'invité ou en tant que client fidèle.",
    "By clicking the 'Purchase' button, you will be redirected to the chosen payment gateway.": "En cliquant sur le bouton "Acheter", vous serez redirigé vers la passerelle de paiement choisie.",
    "In the members area you can track your order, spectate, pause or chat live with your booster!": "Dans la zone membres, vous pouvez suivre votre commande, regarder, faire une pause ou discuter en direct avec votre booster",
  }
}

</i18n>
