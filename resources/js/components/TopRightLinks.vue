<template>
	<div>
		<span>
			<v-icon color="white">mdi-cellphone-iphone</v-icon>
			<a href="/pay-with-mobile">{{ $t('Mobile Pay') }}</a>
		</span>
		<span>
			<a href="/contact">{{ $t('Contact') }}</a>
		</span>
		<span>
			<a href="/loyalty-program">{{ $t('Loyalty Program') }}</a>
		</span>
		<v-dialog v-model="shown" width="fit-content" hide-overlay content-class="languages">
			<template v-slot:default>
				<span class="language-selector" @mouseover="showLanguages">{{ $t('Language') }}</span>
			</template>

			<v-list @mouseleave="close">
				<v-list-item v-for="(language,index) in languages" :key="index">
					<v-list-item-icon></v-list-item-icon>
					<v-list-item-content>
						<v-list-item-title v-text="language"></v-list-item-title>
					</v-list-item-content>
				</v-list-item>
			</v-list>
		</v-dialog>
	</div>
</template>

<script>
export default {
	data() {
		return {
			shown: false,
			current: "gb",
			languages: {
				gb: "English",
				it: "Italian",
				fr: "French"
			}
		};
	},
	methods: {
		showLanguages() {
			this.shown = true;
		},
		close() {
			this.shown = false;
		}
	}
};
</script>

<style scoped>
a {
	color: white;
	text-decoration: none;
	color: linear-gradient(to right, purple 50%, white 50%);
	transition: all 500ms ease;
}
a:hover {
	color: purple !important;
}
span {
	padding-right: 10px;
	color: white;
}
.v-overflow-btn {
	margin-top: 22px;
	max-width: 51%;
}
.language-selector {
	cursor: pointer;
}
.theme--dark.v-list {
	background-color: #250f24;
	border-radius: 1px;
}
</style>

<i18n>
    {
        "en": {
            "Mobile Pay": "Mobile Pay",
            "Contact": "Contact",
            "Loyalty Program": "Loyalty Program",
			"Language": "Language"	
        }
    }
</i18n>