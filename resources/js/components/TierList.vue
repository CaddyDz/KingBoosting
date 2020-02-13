<template>
  <v-stepper-content step="1">
    <v-card raised class="mb-4" :style="{boxShadow: tier.box_shadow}">
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
        servers: [],
        eta: "",
        
        // TIERS props
        tiers: [], // List of all tiers
        tier: { wins: [] }, // Currently selected tier, wins is an empty array because it's used in template
        selectedTierID: 1, // Pretty self explanatory

        // DIVISIONS props
        divisions: [], // LIST OF DIVISIONS
        division: {}, // Currently selected division
        selectedDivisionID: 1, // Same ooh, ooh same ooh
        hasDivisions: true,

        wantedNumberOfWins: undefined,
        numberOfWins: undefined,

    };
  },
  methods: {},
  computed: {},
  watch: {
    // WE STOPED HERE
    #######################
    selectedTierID(tierID){
			this.tier = _.find(this.tiers, ["id", tierID]);
			this.max = _.maxBy(this.tier.wins, "wins").wins;
			this.eta = _.find(this.tier.wins, ["wins", this.number_of_wins]).eta;
			if (!_.isEmpty(this.tier.divisions)) {
				// Divisions not empty, therefor less than master
				this.hasDivisions = true;
				this.selectedDivisionID = this.tier.divisions[0].id;
			} else {
				// Set division to an empty object with null image to pass coalesce in template
				this.division = { image: null };
				// Remove the divisions select from the DOM
				this.hasDivisions = false;
				// Set price to tier's price
				this.price = parseFloat(this.tier.price);
			}
    }
  },
  mounted() {
    // Get list of tiers objects from db
    axios.post("/api/tiers", { service: this.service.slug }).then(response => {
        // The tiers array is the service
        // UPDATE TIERS
        this.tiers = response.data;
        this.$store.commit("tierList/updateAllTiers", this.tiers);

        // UPDATE TIER 
        this.tier = _.first(this.tiers);
        this.$store.commit("tierList/updateSelectedTier", this.tier);

        // UPDATE TIER ID
        this.selectedTierID = _.first(this.tiers).id;
        this.$store.commit('tierList/updateSelectedTierID', (_.first(this.tiers).id) )
        
        // UPDATE DIVISIONS
        this.divisions = this.tier.divisions;
        this.$store.commit("tierList/updateAllDivisions", this.tier.divisions);

        // UPDATE DIVISION
        this.division = _.first(this.tier.divisions);
        this.$store.commit("tierList/updateSelectedDivision", (_.first(this.tier.divisions)) );

        // UPDATE NUMBER OF WINS
        this.numberOfWins = _.maxBy(this.tier.wins, "wins");
        this.$store.commit("tierList/updateNumberOfWins", (_.maxBy(this.tier.wins, "wins")) );

        // UPDATE ETA
			  this.eta = _.find(this.tier.wins, this.numberOfWins).eta;
        this.$store.commit('tierList/updateETA', (_.find(this.tier.wins, this.numberOfWins).eta) );

        // UPDATE PRICE
			  this.price = this.division.price * this.numberOfWins.wins;
        this.$store.commit('tierList/updatePrice', (this.division.price * this.numberOfWins.wins))

        console.log(this.tiers)
        console.log(this.$store.getters['tierList/getSelectedDivision'])

    });
    // Get servers from API
    axios.get("/api/servers").then(response => (this.servers = response.data));

    console.log(this.$store)
  }
};
</script>

<style></style>
