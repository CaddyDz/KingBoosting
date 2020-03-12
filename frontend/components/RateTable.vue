<template>
  <v-data-table :headers="Headers" :items="Records" hide-default-footer class="elevation-1">
    <template v-slot:item.champion="{ item }" class="champion-img">
      <img :src="'/img/champions/'+item.champion+'.png'" >
    </template>
    <template v-slot:item.kills="{ item }">
      <p style="color:green; margin:0">{{ item.kills }}</p>
    </template>
    <template v-slot:item.deaths="{ item }">
      <p style="color:red; margin:0">{{ item.deaths }}</p>
    </template>
    <template v-slot:item.assists="{ item }">
      <p style="color:orange; margin:0">{{ item.assists }}</p>
    </template>
    <template v-slot:item.winrate="{ item }">
      <p style="color:#40bdab; margin:0">{{ item.winrate }}</p>
    </template>

  </v-data-table>
</template>


<script>
export default {
  data(){
    return {
      Headers:[
          {text: "Champion",value : "champion"},
          {text: "Total games",value : "count"},
          {text: "Kills",value : "kills"},
          {text: "Deaths",value : "deaths"},
          {text: "Assists",value : "assists"},
          {text: "KDA Ratio",value : "ratio"},
          {text: "Minions",value : "minions"},
          {text: "Winrate",value : "winrate"},
      ],
      Records: [
          {champion:"Jhin",
          count:"254",
          kills:"8.73",
          deaths:"4.02",
          assists:"8.26",
          ratio:"4.22",
          minions:"133.26 / game",
          winrate:"83.86%"},
          {champion:"Karma",
          count:"254",
          kills:"8.73",
          deaths:"4.02",
          assists:"8.26",
          ratio:"4.22",
          minions:"133.26 / game",
          winrate:"83.86%"},
          {champion:"Kayn",
          count:"254",
          kills:"8.73",
          deaths:"4.02",
          assists:"8.26",
          ratio:"4.22",
          minions:"133.26 / game",
          winrate:"83.86%"},
          {champion:"Kennen",
          count:"254",
          kills:"8.73",
          deaths:"4.02",
          assists:"8.26",
          ratio:"4.22",
          minions:"133.26 / game",
          winrate:"83.86%"},
          {champion:"Teemo",
          count:"254",
          kills:"8.73",
          deaths:"4.02",
          assists:"8.26",
          ratio:"4.22",
          minions:"133.26 / game",
          winrate:"83.86%"},
          {champion:"Talon",
          count:"254",
          kills:"8.73",
          deaths:"4.02",
          assists:"8.26",
          ratio:"4.22",
          minions:"133.26 / game",
          winrate:"83.86%"},
          {champion:"Udyr",
          count:"254",
          kills:"8.73",
          deaths:"4.02",
          assists:"8.26",
          ratio:"4.22",
          minions:"133.26 / game",
          winrate:"83.86%"},
          {champion:"Zoe",
          count:"254",
          kills:"8.73",
          deaths:"4.02",
          assists:"8.26",
          ratio:"4.22",
          minions:"133.26 / game",
          winrate:"83.86%"},
          {champion:"Zyra",
          count:"254",
          kills:"8.73",
          deaths:"4.02",
          assists:"8.26",
          ratio:"4.22",
          minions:"133.26 / game",
          winrate:"83.86%"},
          {champion:"Caitlyn",
          count:"254",
          kills:"8.73",
          deaths:"4.02",
          assists:"8.26",
          ratio:"4.22",
          minions:"133.26 / game",
          winrate:"83.86%"},
          {champion:"Ekko",
          count:"254",
          kills:"8.73",
          deaths:"4.02",
          assists:"8.26",
          ratio:"4.22",
          minions:"133.26 / game",
          winrate:"83.86%"},
      ],
    }
  }
}
</script>

<style scoped>
  img {
    width: 30px;
  }
</style>