<template>

  <v-card dark max-width="450">
    <div class="d-flex flex-no-wrap justify-space-between">
      <div>
        <v-card-title class="headline" >Lakbir Hmad</v-card-title>
        <v-card-subtitle >
            lhalag
            <v-rating :value="4.5" color="amber" dense half-increments readonly size="14"></v-rating>
        </v-card-subtitle>
            <v-container class="chips">
                <v-chip v-for="chip in chips" :key="chip.id" class="ma-1" small v-text="chip.name" outlined></v-chip>
            </v-container>
      </div>
      <v-avatar class="ma-3" size="125" tile>
        <v-img src="https://cdn.vuetifyjs.com/images/cards/halcyon.png"></v-img>
      </v-avatar>
    </div>




  </v-card>
</template>

<script>
  export default {
    data() {
      return {
        chips: [{
            id: 0,
            name: "Toplane"
          },
          {
            id: 1,
            name: "Jungle"
          },
          {
            id: 2,
            name: "Marksman"
          },
          {
            id: 3,
            name: "Jungle"
          },
          {
            id: 4,
            name: "Marksman"
          }
        ]
      }
    }
  }

</script>

<style scoped>
  .chips {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
  }

</style>
