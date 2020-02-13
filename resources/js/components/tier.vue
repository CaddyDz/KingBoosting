<template>
    <v-stepper-content step="1">
        <v-card raised class="mb-4">
            <v-container>
                <v-row align="center" justify="center">
                    <v-col md="3">
                        <img
                            :src="division.image || tier.image"
                            :alt="tier.name"
                            loading="lazy"
                            width="280"
                            height="320"
                        />
                    </v-col>
                    <v-col md="9">
                        <v-row>
                            <v-col>
                                <v-select
                                    :items="tiers"
                                    name="tier"
                                    label="Current tier"
                                    v-model="selectedTierID"
                                    item-text="name"
                                    item-value="id"
                                ></v-select>
                            </v-col>
                            <v-col v-if="this.hasDivisions">
                                <v-select
                                    :items="tier.divisions"
                                    label="Current division"
                                    item-text="name"
                                    v-model="selectedDivisionID"
                                    item-value="id"
                                ></v-select>
                            </v-col>
                        </v-row>
                        <v-select
                            :items="servers"
                            label="Select your server"
                            item-text="region"
                            :value="servers[0]"
                        ></v-select>
                    </v-col>
                </v-row>
            </v-container>
        </v-card>
    </v-stepper-content>
</template>

<script>
export default {
    props: ["service"],
    data() {
        return {
            tier: { wins: [] }, // Currently selected tier, wins is an empty array because it's used in template
            division: {}, // Currently selected division
            tiers: [], // List of all tiers
            // divisions: [], // LIST OF DIVISIONS
            selectedTierID: 1, // Pretty self explanatory
            selectedDivisionID: 1, // Same ooh, ooh same ooh
            hasDivisions: true,
            servers: []
        };
    },
    methods: {},
    computed: {
        SelectedTier: function() {
            return this.tier = this.$store.state.currentlySelectedTier // Currently selected tier
            console.log('from the computer')
        },
        SelectedDivision: function() {
            return this.division = this.$store.state.currentlySelectedDivision; // Currently selected division
        }
    },
    watch: {
        selectedTierID(tierId) {
            // User selected a tier, let's commit it to store
            this.$store.commit(
                "updateCurrentlySelectedTier",
                _.find(this.tiers, ["id", tierId])
            );
            if (!_.isEmpty(this.tier.divisions)) {
                // If the selected tier has divisions
                this.hasDivisions = true;
                this.selectedDivisionID = this.tier.divisions[0].id;
            } else {
                // Set division to an empty object with null image to pass coalesce in template
                this.$store.state.currentlySelectedDivision = { image: null };
                // Remove the divisions select from the DOM
                this.hasDivisions = false;
            }
        },
        selectedDivisionID(divisionId) {
            this.$store.commit(
                "updateCurrentlySelectedDivision",
                _.find(this.tier.divisions, ["id", divisionId])
            );
        }
    },
    mounted() {
        // Get list of tiers objects from db
        axios
            .post("/api/tiers", { service: this.service.slug })
            .then(response => {
                // The tiers array is the service
                this.tiers = response.data;
                this.tier = _.first(this.tiers)
                // Divisions of the first tier
                this.divisions = _.first(this.tiers).divisions;
                // Commit the first tier as the currently selected Tier
                this.$store.commit(
                    "updateCurrentlySelectedTier",
                    _.first(this.tiers)
                );
                console.log(this.division)
                console.log(this.tiers);
                console.log(this.tier);
                console.log(this.$store.state.currentlySelectedTier);
                //console.log(this.$store.state.currentlySelectedDivision)
                
                // Division IV
                this.$store.commit(
                    "updateCurrentlySelectedDivision",
                    // this.tier is the computed property
                    _.first(this.SelectedTier.divisions)
                );
                console.log(this.$store.state.currentlySelectedDivision)

                // Get the maximum number of wins in the tier, commit it to the store
                // this.$store.commit('updateMaximumNumberOfWins', _.maxBy(this.tier.wins, "wins"));

                // console.log( _.maxBy(this.$store.state.currentlySelectedTier.wins, "wins"))
                // console.log(this.$store.getters.getMaxNumberOfwins)
            });
        // Get servers from API
        axios
            .get("/api/servers")
            .then(response => (this.servers = response.data));
    }
};
</script>

<style></style>
