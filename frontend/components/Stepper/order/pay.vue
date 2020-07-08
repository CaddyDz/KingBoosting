<template>
	<v-stepper-content key="3-content" step="3">
		<v-card class="mb-12" color="grey lighten-1" height="200px"></v-card>
		<v-btn color="primary" @click="sendOrder">{{ $t('Continue') }}</v-btn>
		<v-btn text @click="cancel">{{ $t('Cancel') }}</v-btn>
	</v-stepper-content>
</template>

<script>
export default {
	methods: {
		sendOrder() {
			console.log(this.$store.state);
			// Get all data from store and post them to DB
		},
		cancel() {
			this.$emit("cancel");
		}
	}
};
</script>

<i18n>
{
  "en": {
    "Continue": "Continue",
    "Cancel": "Cancel"
  }
}
</i18n>
