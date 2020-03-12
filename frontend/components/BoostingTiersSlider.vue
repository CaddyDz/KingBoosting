<template>
  <v-sheet
    class="mx-auto"
    elevation="8"
    color="#121212"
  >
    <v-slide-group
      v-model="model"
      class="pa-4"
      show-arrows
    >
      <v-slide-item
        v-for="n in icons"
        :key="n"
      >
        <v-card
          color="#121212"
          class="ma-4"
          height="400"
          width="250"
        >
            <v-row
                class="fill-height card-row"
            >
                <img :src="'/img/divisions'+n" style="width: 180px">
                <h2 style="margin-bottom:20px">Challanger</h2>
                 <v-btn-toggle
                  dense
                  group
                >
                  <v-btn :value="1" text color="#27ae60">
                    <v-icon>mdi-arrow-up-bold</v-icon>
                    10 Win
                  </v-btn>

                  <v-btn :value="2" text color="#e74c3c">
                    <v-icon>mdi-arrow-down-bold</v-icon>
                    3 Lose
                  </v-btn>


                </v-btn-toggle>
                  <v-btn :value="3" text>
                    <v-icon>mdi-chart-line-variant</v-icon>
                    20% Winrate
                  </v-btn>
            </v-row>
        </v-card>
      </v-slide-item>
    </v-slide-group>

    <v-expand-transition>
      <v-sheet
        v-if="model != null"
        color="grey lighten-4"
        height="200"
        tile
      >
        <v-row
          class="fill-height"
          align="center"
          justify="center"
        >
          <h3 class="title">Selected {{ model }}</h3>
        </v-row>
      </v-sheet>
    </v-expand-transition>
  </v-sheet>
</template>


<script>
  export default {
    data: () => ({
      model: null,
      icons:["/platinum/III.webp","/diamond/IV.webp","/gold/II.webp","/iron/III.png","/platinum/IV.webp"]
    }),
  }
</script>

<style scoped>
    .card-row {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: #101010;
    }
    .avatar-container {
        display: flex;
        width: 100%;
        flex-direction: row;
        align-items: center;
        justify-content: space-around;
    }

    .avatar-item {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

</style>