<template>
	<v-stepper-content key="2-content" step="2">
		<v-form v-model="valid">
			<v-container>
				<v-row>
					<!-- Summoner -->
					<v-col cols="12" md="6">
						<v-text-field v-model="nickname" label="Nickname *" :rules="nicknameRules" required placeholder="Your in-game name"></v-text-field>
					</v-col>
					<!-- Select Booster -->
					<v-col class="d-flex" cols="12" sm="6">
						<v-autocomplete :items="boosters" label="Your Booster" :loading="boostersLoading" v-model="booster"></v-autocomplete>
					</v-col>
					<!-- Select Champion -->
					<!-- Only if client has checked specific champions in checkout -->
					<v-col class="d-flex" cols="12" sm="6" v-if="specificChampion">
						<v-select :items="champions" label="Your Champion" v-model="champion" item-text="name" item-value="name"></v-select>
					</v-col>
				</v-row>
				<v-row>
					<v-col cols="12" md="6">
						<v-text-field v-model="comment" label="Comments NOT REQUIRED" placeholder="Your comments"></v-text-field>
					</v-col>
				</v-row>
				<v-row>
					<v-col cols="12" md="6">
						<v-icon>mdi-information-outline</v-icon>
						<span>Further information will be requested after payment</span>
					</v-col>
				</v-row>
			</v-container>
		</v-form>
		<v-btn color="primary" @click="nextStep" :disabled="!valid">Next</v-btn>
	</v-stepper-content>
</template>

<script>
import champions from "~/assets/js/champions";

export default {
	data: () => ({
		valid: false,
		boosters: [],
		boostersLoading: true,
		booster: "",
		champions: champions,
		champion: "Aatrox",
		comment: "",
		nickname: "",
		nicknameRules: [
			(v) => !!v || "Nickname is required",
			(v) => v.length >= 3 || "Please insert a valid nickname",
		],
	}),
	computed: {
		specificChampion() {
			return this.$store.state.checkout.options.includes(
				"Specific champions/roles"
			);
		},
	},
	methods: {
		getBoostersList() {
			this.$axios
				.get("api/getBoostersNames")
				.then((response) => {
					this.boosters = response.data;
					this.boostersLoading = false;
				})
				.catch((error) => {
					this.$notify("Could not connect to server", "error");
				});
		},
		nextStep() {
			this.$store.commit("order/changeNickname", this.nickname);
			this.$store.commit("order/changeBooster", this.booster);
			this.$store.commit("order/changeComment", this.comment);
			this.$emit("next");
		},
	},
	mounted() {
		this.getBoostersList();
	},
};
</script>
