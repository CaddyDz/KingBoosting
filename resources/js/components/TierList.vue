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

        maxNumberOfWins: undefined,
        defaultNumberOfWins: undefined
    };
  },
  methods: {},
  computed: {},
  watch: {
    selectedTierID(tierID){

      this.$store.commit("tierList/updateSelectedTier", _.find(this.tiers, ["id", tierID]));
      this.tier = this.$store.getters['tierList/getSelectedTier'];
      
      this.$store.commit("tierList/updateMaxNumberOfWins", (_.maxBy(this.tier.wins, "wins").wins) );
      this.maxNumberOfWins = this.$store.getters['tierList/getMaxNumberOfWins'];
      
      this.$store.commit('tierList/updateETA', (_.find(this.tier.wins, ['wins', this.defaultNumberOfWins]).eta ) );
      this.eta = this.$store.getters['tierList/getETA'];
      
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
      // CHNAGE ETA WHEN TIER CHANGED
      this.$root.$emit('change', this.$store.getters['tierList/getETA'])

    },
    selectedDivisionID(divisionId) {
      // UPDATE ETA
      this.eta = _.find(this.tier.wins, ['wins', this.maxNumberOfWins] ).eta;
      this.$store.commit('tierList/updateETA', (_.find(this.tier.wins, ['wins', this.maxNumberOfWins]).eta ) );

      // UPDATE DIVISION
      this.division = _.find(this.tier.divisions, ["id", divisionId]);
      this.$store.commit('tierList/updateSelectedDivision', _.find(this.tier.divisions, ["id", divisionId]) );
    },
  },
  beforeMount(){
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
        
        // UPDATE DIVISIONS of TIER
        this.divisions = this.tier.divisions;
        this.$store.commit("tierList/updateAllDivisions", this.tier.divisions);

        // UPDATE SELECTED DIVISION
        this.division = _.first(this.tier.divisions);
        this.$store.commit("tierList/updateSelectedDivision", (_.first(this.tier.divisions)) );

        // UPDATE MAX NUMBER OF WINS IN THE TIER
        this.maxNumberOfWins = _.maxBy(this.tier.wins, "wins").wins;
        this.$store.commit("tierList/updateMaxNumberOfWins", (_.maxBy(this.tier.wins, "wins").wins) );

        // UPDATE THE DEFAULT NUMBER OF WINS
        this.defaultNumberOfWins = _.find(this.tier.wins, ["wins", this.$store.getters['tierList/getDefaultNumberOfWins']]).wins;
        this.$store.commit("tierList/updateDefaultNumberOfWins", (_.find(this.tier.wins, ["wins", this.$store.getters['tierList/getDefaultNumberOfWins']]).wins) );
        // UPDATE ETA
			  this.eta = _.find(this.tier.wins, ['wins', this.defaultNumberOfWins] ).eta;
        this.$store.commit('tierList/updateETA', (_.find(this.tier.wins, ['wins', this.defaultNumberOfWins]).eta ) );

        // UPDATE PRICE
			  this.price = this.division.price * this.maxNumberOfWins;
        this.$store.commit('tierList/updatePrice', (this.division.price * this.maxNumberOfWins))

        //console.log(_.find(this.tier.wins, ["wins", this.$store.getters['tierList/getDefaultNumberOfWins']]).wins) 

    });
    // Get servers from API
    axios.get("/api/servers").then(response => (this.servers = response.data));
  },
  mounted() {
    // Get list of tiers objects from db
    /*axios.post("/api/tiers", { service: this.service.slug }).then(response => {
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
        
        // UPDATE DIVISIONS of TIER
        this.divisions = this.tier.divisions;
        this.$store.commit("tierList/updateAllDivisions", this.tier.divisions);

        // UPDATE SELECTED DIVISION
        this.division = _.first(this.tier.divisions);
        this.$store.commit("tierList/updateSelectedDivision", (_.first(this.tier.divisions)) );

        // UPDATE MAX NUMBER OF WINS IN THE TIER
        this.maxNumberOfWins = _.maxBy(this.tier.wins, "wins").wins;
        this.$store.commit("tierList/updateMaxNumberOfWins", (_.maxBy(this.tier.wins, "wins").wins) );

        // UPDATE ETA
			  this.eta = _.find(this.tier.wins, ['wins', this.maxNumberOfWins] ).eta;
        this.$store.commit('tierList/updateETA', (_.find(this.tier.wins, ['wins', this.maxNumberOfWins]).eta ) );

        // UPDATE PRICE
			  this.price = this.division.price * this.maxNumberOfWins;
        this.$store.commit('tierList/updatePrice', (this.division.price * this.maxNumberOfWins))

    });
    // Get servers from API
    axios.get("/api/servers").then(response => (this.servers = response.data));*/

    // console.log(this.$store)
  }
};
</script>

<style></style>
