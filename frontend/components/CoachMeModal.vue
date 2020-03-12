<template>
      <v-card>
        <v-card-title class="headline">Duration time</v-card-title>
        <v-card-text>
            <v-slider v-model="hours" thumb-label="always" max="10" min="1" @end="hoursHandler($event)">
                <template v-slot:thumb-label="{ value }">
                    {{value}}H
                </template>
            </v-slider>
        </v-card-text>
        <v-card-actions>
            <div class="align-center">
                <p>€30/h</p>
                <v-icon size="35">mdi-currency-eur</v-icon>
                <h2>{{inEuro}} (${{inDollar}})</h2>
            </div>
          <v-btn color="darken-1" text @click="emitCloseModale()">Disagree</v-btn>
          <v-btn color="green darken-1" @click="emitCloseModale()">Agree</v-btn>
        </v-card-actions>
      </v-card>
</template>


<script>
  export default {
    data() {
      return {
        dialog: false,
        inEuro: 60.00 ,
        inDollar: 68.10,
        hours: 2,
        perHour:30
      }
    },
    methods:{
        emitCloseModale(){
            console.log('ooooooooooh');
            this.$emit('closeModal',false);
        },
        hoursHandler(h){
            this.inEuro = this.perHour * h ;
            this.inDollar = this.inEuro + 8.10 ; 
        }
    }
  }

</script>


<style scoped>
    .align-center {
        display: flex;
        flex-direction: row;
        align-items: center;
        width: 100%;
    }

    h2 , p {
        margin-bottom: 0;
        margin-right: 10px;
    }

    p {

    }
</style>