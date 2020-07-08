<template>
	<v-stepper-content key="2-content" step="2">
		<v-form v-model="valid">
			<v-container>
				<v-row>
					<!-- Summoner -->
					<v-col cols="12" md="6">
						<v-text-field
							v-model="nickname"
							:label="$t('Nickname *')"
							:rules="nicknameRules"
							required
							:placeholder="$t('Your in-game name')"
						></v-text-field>
					</v-col>
					<!-- Select Booster -->
					<v-col class="d-flex" cols="12" sm="6">
						<v-select :items="boosters" :label="$t('Your Booster')" :loading="boostersLoading"></v-select>
					</v-col>
				</v-row>
				<v-row>
					<v-col cols="12" md="6">
						<v-text-field
							v-model="comment"
							:label="$t('Comments NOT REQUIRED')"
							:placeholder="$t('Your comments')"
						></v-text-field>
					</v-col>
				</v-row>
				<v-row>
					<v-col cols="12" md="6">
						<v-checkbox
							:label="$t('Appear Offline on Chat')"
							prepend-icon="mdi-account"
							v-model="offline"
						></v-checkbox>
					</v-col>
				</v-row>
				<v-row>
					<v-col cols="12" md="6">
						<v-icon>mdi-information-outline</v-icon>
						<span>{{ $t('Further information will be requested after payment') }}</span>
					</v-col>
				</v-row>
			</v-container>
		</v-form>
		<v-btn color="primary" @click="nextStep" :disabled="!valid">{{ $t('Next') }}</v-btn>
	</v-stepper-content>
</template>

<script>
export default {
	data() {
		return {
			valid: false,
			boosters: [],
			boostersLoading: true,
			comment: "",
			nickname: "",
			nicknameRules: [
				v => !!v || this.$i18n.t("Nickname is required"),
				v =>
					v.length >= 3 ||
					this.$i18n.t("Please insert a valid nickname")
			],
			offline: false
		};
	},
	methods: {
		getBoostersList() {
			this.$axios
				.get("/getBoostersNames")
				.then(res => {
					this.boosters = res.data;
					this.boostersLoading = false;
				})
				.catch(err => {
					$notify(
						this.$i18n.t("Failed to get list of boosters"),
						"error"
					);
				});
		},
		nextStep() {
			this.$emit("next");
		}
	},
	mounted() {
		this.getBoostersList();
	}
};
</script>

<i18n>
{
	"en": {
		"Nickname *": "Nickname *",
		"Your in-game name": "Your in-game name",
		"Comments NOT REQUIRED": "Comments NOT REQUIRED",
		"Your comments": "Your comments",
		"Appear Offline on Chat": "Appear Offline on Chat",
		"Further information will be requested after payment": "Further information will be requested after payment",
		"Next": "Next",
		"Your Booster": "Your Booster",
		"Failed to get list of boosters": "Failed to get list of boosters",
    "Nickname is required": "Nickname is required",
    "Please insert a valid nickname": "Please insert a valid nickname"
	}
}
</i18n>
