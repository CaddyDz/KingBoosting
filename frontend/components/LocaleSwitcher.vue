<template>
	<span>
		<span class="language-selector" @mouseover="showLanguages">{{ $t('Language') }}</span>
		<v-dialog v-model="shown" width="fit-content" hide-overlay content-class="languages">
			<template v-slot:default>
				<v-list @mouseleave="close">
					<v-row>
						<v-col>
							<v-list-item v-for="(language, key) in mainLanguages" :key="key">
								<v-list-item-icon></v-list-item-icon>
								<v-list-item-content>
									<v-list-item-title v-text="language.name" @click="switchLocale(language.id)"></v-list-item-title>
								</v-list-item-content>
							</v-list-item>
						</v-col>
						<v-col>
							<v-list-item v-for="(language, key) in otherLanguages" :key="key">
								<v-list-item-icon></v-list-item-icon>
								<v-list-item-content>
									<v-list-item-title v-text="language.name" @click="switchLocale(language.id)"></v-list-item-title>
								</v-list-item-content>
							</v-list-item>
						</v-col>
					</v-row>
				</v-list>
			</template>
		</v-dialog>
	</span>
</template>

<script>
export default {
	data() {
		return {
			shown: false,
			current: "en",
			itemsPerRow: 5,
			mainLanguages: [
				{ id: "en", name: "English" },
				{ id: "fr", name: "Français" },
				{ id: "de", name: "Deutsche" },
				{ id: "at", name: "Deutsche" },
				{ id: "swiss", name: "Suisse" }
			],
			otherLanguages: [
				{ id: "es", name: "Español" },
				{ id: "nl", name: "Nederlands" },
				{ id: "it", name: "Italiano" },
				{ id: "se", name: "Svenska" },
				{ id: "no", name: "Norsk" },
				{ id: "dk", name: "Dansk" }
			]
		};
	},
	methods: {
		showLanguages() {
			this.shown = true;
		},
		close() {
			this.shown = false;
		},
		switchLocale(locale) {
			this.current = locale;
			this.$root.$i18n.setLocale(locale);
		}
	}
};
</script>

<style scoped>
.language-selector {
	cursor: pointer;
}
</style>

<i18n>
{
    "en": {
        "Language": "Language"
    },
    "fr": {
        "Language": "Langue"
    },
    "it": {
        "Language": "linguaggio"
    },
    "de": {
        "Language": "Language"
    },
    "at": {
        "Language": "Language"
    },
    "swiss": {
        "Language": "Language"
    },
    "es": {
        "Language": "Language"
    },
    "nl": {
        "Language": "Language"
    },
    "se": {
        "Language": "Language"
    },
    "ne": {
        "Language": "Language"
    },
    "dk": {
        "Language": "Language"
    }
}
</i18n>
