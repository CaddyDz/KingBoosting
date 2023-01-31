<template>
	<v-snackbar v-model="snackbar" right top :color="mode" shaped>
		<p class="black--text bold--text text-h6">{{ text }}</p>
		<ul v-if="errors">
			<li v-for="(error, index) in errors" :key="index">
				{{ index }}
				<ul>
					<li v-for="(err, i) in error" :key="i">{{ err }}</li>
				</ul>
			</li>
		</ul>
		<template v-slot:action="{ attrs }">
			<v-btn color="red" v-bind="attrs" @click="snackbar = false">Close</v-btn>
		</template>
	</v-snackbar>
</template>

<script>
export default {
	computed: {
		snackbar: {
			get() {
				return this.$store.state.notification.isOpen;
			},
			set() {
				this.$store.commit("notification/close");
			},
		},
		text() {
			return this.$store.state.notification.text;
		},
		mode() {
			return this.$store.state.notification.mode;
		},
		errors() {
			return this.$store.state.notification.errors;
		},
	},
};
</script>
