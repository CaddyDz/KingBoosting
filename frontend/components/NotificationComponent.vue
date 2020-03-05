<template>
	<v-snackbar v-model="snackbar" right top :color="mode" :timeout="3000">
		{{ text }}
		<v-btn color="black" text @click="snackbar = false">{{ $t('Close') }}</v-btn>
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
			}
		},
		text() {
			return this.$store.state.notification.text;
		},
		mode() {
			return this.$store.state.notification.mode;
		}
	}
};
</script>

<i18n>
{
  "en": {
    "Close": "Close"
  }
}
</i18n>
