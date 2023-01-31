<template>
	<v-container class="title">
		<div class="text-center">
			<p class="overline">Application</p>
			<p class="h2 bold">Booster Application</p>
			<p>Please fill out the form below!</p>
		</div>
		<v-form ref="form" v-model="valid">
			<v-text-field name="summoner" label="Summoner name" :rules="summonerRules"></v-text-field>
			<v-text-field name="email" label="Email" :rules="[(v) => /.+@.+\..+/.test(v) || 'E-mail must be valid']"></v-text-field>
			<v-select name="country" label="Country" :rules="[(v) => !!v || 'Country is required']" :items="countries"></v-select>
			<v-text-field name="discord_skype" label="Discord / Skype"></v-text-field>
			<v-text-field name="rank" label="Current soloQ rank"></v-text-field>
			<v-text-field name="languages" label="Spoken languages"></v-text-field>
			<v-select name="games" label="Games" :rules="[(v) => !!v || 'Games are required']" :items="gamesItems" item-text="name" item-value="name" multiple></v-select>
			<v-select name="servers" label="Servers" :rules="[(v) => !!v || 'Servers are required']" :items="serversItems" multiple></v-select>
			<v-select name="hours" label="Hours" :rules="[(v) => !!v || 'Hours are required']" :items="hoursItems" item-text="text" item-value="value"></v-select>
			<v-textarea outlined name="application"></v-textarea>
			<v-btn :disabled="!valid" color="primary" class="mr-4" @click="submit">Submit</v-btn>
			<v-btn color="error" class="mr-4" @click="reset">Reset Form</v-btn>
		</v-form>
	</v-container>
</template>

<script>
import games from "~/assets/js/games";
import servers from "~/assets/js/servers";
import countries from "~/assets/js/countries";

export default {
	data: () => ({
		countries: countries,
		valid: false,
		summonerRules: [
			(v) =>
				(v && v.length <= 10) ||
				"Summoner name must be less than 10 characters",
		],
		gamesItems: games,
		serversItems: servers,
		hours: [],
		hoursItems: [
			{ value: "1-3", text: "1-3 H/Day" },
			{ value: "4-6", text: "4-6 H/Day" },
			{ value: "7-8", text: "7-8 H/Day" },
			{ value: "8+", text: "8+ H/Day" },
		],
	}),

	methods: {
		submit() {
			this.validate();
			if (this.valid) {
				const formData = new FormData(this.$refs.form.$el); // reference to form element
				const data = {}; // need to convert it before using not with XMLHttpRequest
				for (let [key, val] of formData.entries()) {
					Object.assign(data, { [key]: val });
				}
				this.$axios
					.post("api/apply", data)
					.then((response) => {
						this.$notify(response.data.message, "success");
						this.reset();
					})
					.catch((errors) => {
						this.$notify(
							errors.response.data.message,
							"error",
							errors.response.data.errors
						);
					});
			}
		},
		validate() {
			this.$refs.form.validate();
		},
		reset() {
			this.$refs.form.reset();
		},
	},
};
</script>
