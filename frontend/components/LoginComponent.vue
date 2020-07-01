<template>
	<div>
		<v-btn
			class="ma-2"
			outlined
			color="white"
			:href="`${process.env.HOST_URL}/dashboard`"
			v-if="auth"
		>{{ $t('Members Area') }}</v-btn>
		<v-dialog max-width="800px" v-model="dialog" v-else>
			<template v-slot:activator="{ on }">
				<v-btn class="ma-2" outlined color="white" v-on="on">{{ $t('Members Area') }}</v-btn>
			</template>
			<v-card class="px-5" color="#303030">
				<v-container>
					<v-row>
						<v-col cols="7">
							<LoginForm @close="dialog = false"></LoginForm>
						</v-col>
						<v-col cols="5">
							<SocialLogin></SocialLogin>
						</v-col>
					</v-row>
				</v-container>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
export default {
	data() {
		return {
			auth: false,
			dialog: false
		};
	}
};
</script>

<style scoped>
.v-btn:hover:before {
	content: none;
}
</style>

<i18n>
{
	"en": {
			"Members Area": "Members Area"
	},
	"fr": {
			"Members Area": "Zone Des Membres"
	},
	"it": {
		"Members Area": "Zona Dei Membri"
	},
	"de": {
		"Members Area": "Members Area"
	},
	"at": {
		"Members Area": "Members Area"
	},
	"swiss": {
		"Members Area": "Members Area"
	},
	"es": {
		"Members Area": "Members Area"
	},
	"nl": {
		"Members Area": "Members Area"
	},
	"se": {
		"Members Area": "Members Area"
	},
	"ne": {
			"Members Area": "Members Area"
	},
	"dk": {
		"Members Area": "Members Area"
	}
}
</i18n>
