<template>
	<div>
		<div class="title">
			<div class="title-id">
				<h2>2</h2>
			</div>
			<h2 class="title-txt">{{ title }}</h2>
			<h3 class="title-txt" v-if="description">{{ description }}</h3>
		</div>
		<v-card raised class="mb-4" :style="{ boxShadow: tier.box_shadow }">
			<v-container>
				<v-row align="center" justify="center">
					<v-col md="3">
						<v-img :src="image" :alt="tier.name" loading="lazy" width="170" />
					</v-col>
					<v-col md="9" class="have-selectors">
						<v-container>
							<v-row>
								<v-col>
									<v-select :items="tiers" label="Desired tier" dense solo v-model="tier" item-text="name" item-value="id" return-object></v-select>
								</v-col>
								<v-col v-if="tier.divisions">
									<v-select :items="tier.divisions" label="Desired division" dense solo v-model="division" item-text="name" item-value="id" return-object></v-select>
								</v-col>
								<v-col v-else>
									<v-text-field v-model="lp" hide-details single-line type="number" suffix="Desired LP" />
								</v-col>
							</v-row>
							<v-row>
								<v-col v-if="mobile">
									<v-select :items="platforms" v-model="platform" label="Select your system" dense solo></v-select>
								</v-col>
								<v-col>
									<v-select :items="servers" v-model="server" label="Select your server" dense solo></v-select>
								</v-col>
								<v-col v-if="showQueue">
									<v-radio-group class="radio-group" v-model="queue" dark mandatory row>
										<v-radio label="Solo/Duo" value="Solo/Duo"></v-radio>
										<v-radio label="Flex 5v5" value="Flex"></v-radio>
									</v-radio-group>
								</v-col>
							</v-row>
						</v-container>
					</v-col>
				</v-row>
			</v-container>
		</v-card>
	</div>
</template>

<script>
import servers from "~/assets/js/servers";

export default {
	props: {
		title: {
			type: String,
			required: false,
			default: "Select Desired League",
		},
		description: {
			type: String,
			required: false,
			default: null,
		},
		tiers: {
			type: Array,
			required: true,
		},
		showQueue: {
			type: Boolean,
			required: false,
			default: true,
		},
		mobile: {
			type: Boolean,
			required: false,
			defaut: false,
		},
	},
	data: () => ({
		servers: servers,
		platforms: ["Android", "iOS"],
	}),
	computed: {
		tier: {
			get() {
				return this.$store.state.desired.tier;
			},
			set(tier) {
				// If the new tier is lower than the one in the current league, change the one in the current league to be one step lower
				// First let's get the id of the tier in the current league
				let current_tier_id = this.$store.state.current.tier.id;
				// Let's now get the id of the desired tier
				let desired_tier_id = tier.id;
				if (desired_tier_id < current_tier_id) {
					// Get the tier that is one step lower than desired_tier_id
					let lower_tier_id =
						desired_tier_id === 1 ? 1 : desired_tier_id - 1;
					let lower_tier = _.find(this.tiers, ["id", lower_tier_id]);
					// Commit it to the VueX store
					this.$store.commit("current/changeTier", lower_tier);
					// Commit its first division to the store as well
					this.$store.commit(
						"current/changeDivision",
						_.first(lower_tier.divisions)
					);
				}
				this.$store.commit("desired/changeTier", tier);
				if (this.tier.divisions) {
					this.$store.commit(
						"desired/changeDivision",
						_.find(this.tier.divisions, ["name", "III"])
					);
				}
				this.$emit("divisionChanged");
			},
		},
		division: {
			get() {
				return this.$store.state.desired.division;
			},
			set(division) {
				this.$store.commit("desired/changeDivision", division);
				this.$emit("divisionChanged");
			},
		},
		lp: {
			get() {
				return this.$store.state.desired.lp;
			},
			set(lp) {
				this.$store.commit("desired/changeLP", lp);
				this.$emit("lpchanged");
			},
		},
		server: {
			get() {
				return this.$store.state.desired.server;
			},
			set(server) {
				this.$store.commit("desired/changeServer", server);
			},
		},
		queue: {
			get() {
				return this.$store.state.desired.queue;
			},
			set(queue) {
				this.$store.commit("desired/changeQueue", queue);
			},
		},
		image() {
			if (this.tier.divisions) {
				return this.division.image;
			}
			return this.tier.image;
		},
		platform: {
			get() {
				return this.$store.state.desired.platform;
			},
			set(platform) {
				this.$store.commit("desired/changePlatform", platform);
			},
		},
	},
	created() {
		this.tier = _.find(this.tiers, ["name", "Gold"]);
	},
};
</script>

<style scoped>
.have-selectors {
	padding-right: 20px;
	padding-left: 20px;
}

.title {
	display: flex;
	flex-direction: row;
	align-items: center;
	margin-bottom: 20px;
}

.title-txt {
	margin-left: 20px;
}

.title .title-id {
	color: #181818;
}

.title-id {
	width: 50px;
	height: 50px;
	border-radius: 50%;
	background: linear-gradient(#673ab7, #9c27b0);
	display: flex;
	align-items: center;
	justify-content: center;
}
.v-text-field {
	padding-top: 0px;
}
.v-input--selection-controls {
	margin-top: 0px;
}
.v-input--radio-group.v-input--radio-group--row .v-radio {
	margin-right: 2px;
}
</style>
