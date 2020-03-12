<template>
    <div>
        <v-container>
            <v-row>
                <v-col md="8">
                    <v-card>
                        <v-row>
                            <v-col md="3" class="image-container">
                                <v-img
                                    src="https://cdn.vuetifyjs.com/images/cards/halcyon.png"
                                    alt="Sunny image"
                                    width="92"
                                    class="profile-picture"
                                ></v-img>
                            </v-col>
                            <v-col md="9">
                                <v-card-title>Kitani islam</v-card-title>
                                <v-card-subtitle>
                					<v-rating :value="4.5"  dense half-increments readonly size="14"></v-rating>
                                </v-card-subtitle>
                                <v-card-text>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quo nesciunt aspernatur omnis sapiente praesentium provident, aliquam doloribus voluptates asperiores voluptate, consequuntur vitae, aperiam nemo rerum ducimus nihil. Dolores, neque eum?</v-card-text>
                            </v-col>
                        </v-row>
                        <v-card-actions>
                            <v-chip v-for="chip in chips" :key="chip.id" class="ma-1" small v-text="chip.name" outlined></v-chip>
                            <v-spacer></v-spacer>
                              <div v-for="item in languages" :key="item.id">
                                    <country-flag :country="item.code" size="small" class="flag" />
                              </div>
                        </v-card-actions>
                    </v-card>
                </v-col>
                <v-col md="4">
                    <v-card class="skills">
                        <v-card-title>Coaching Skills</v-card-title>
                        <v-card-text>
                            <div class="coaching-skills-simple" v-for="item in coachingSkills" :key="item.id">
                                <p>{{item.name}}</p>
                                <v-rating :value="item.rate" style="margin-left: 15px"  dense half-increments readonly size="18"></v-rating>
                            </div>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn block outlined v-text="'Coach Me'"></v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
            <div class="circular-progress">
                <v-progress-circular 
                    v-for="item in animatedCircular" :key="item.id"
                    :rotate="360"
                    :size="250"
                    :width="15"
                    :value="item.value"
                    color="#673ab7"
                    >
                    <div class="circular-contant">
                        <h1 style="font-size: 48px">{{ item.value }}</h1>
                        <p>{{item.name}}</p>
                    </div>
                </v-progress-circular>
                <br>
            </div>
            <div style="margin-top: 80px">
                <h1 class="section-title">Top Champions</h1>
                <rate-table />
            </div>
            <div style="margin-top: 80px">
                <h1 class="section-title">Boosting Statistics By Tiers</h1>
            </div>
        </v-container>    
    </div>    
</template>

<script>
export default {

    data(){
        return {
            finalCircular:[
                {value : 80,id:0},
                {value : 60,id:1},
                {value : 30,id:2},
                {value : 45,id:3},
            ],
            languages: [
				{ name: "Russia", code: "rus" },
				{ name: "English", code: "us" },
				{ name: "German", code: "de" },
				{ name: "Spani", code: "es" },
				{ name: "Italian", code: "it" }
			],
            animatedCircular:[
                {value : 0,id:0,name:"Orders Completed"},
                {value : 0,id:1,name:"Orders in Progress"},
                {value : 0,id:2,name:"% of Buyers Recommend"},
                {value : 0,id:3,name:"Booster Since"},
            ],
            shouldCleanInterval:0,
            interval: {},
            chips: [{id: 0,name: "Toplane"},
				{id: 1,name: "Jungle"},
				{id: 2,name: "Marksman"},
				{id: 3,name: "Jungle"},
				{id: 4,name: "Marksman"}
            ],
            coachingSkills:[
                {id:0,name:"Game knowledge",rate:4.5},
                {id:1,name:"Ingame attitude",rate:3.5},
                {id:2,name:"Language skill",rate:5},
                {id:3,name:"Champions",rate:4.3},
            ]
        }
    },
    beforeDestroy () {
      clearInterval(this.interval)
    },
    mounted () {
        // animation for Circular
      this.interval = setInterval(() => {
        this.animatedCircular.map((item,index) => {
            if(this.finalCircular[index].value > item.value){
                item.value ++
            }
        })
      }, 80)
    },
    
}
</script>

<style scoped>
    .linear-progress {
        height: 10px;
    }

    .image-container {
        display: flex;
        align-items: center;
        justify-content: center;   
    }

    .profile-picture {
        margin-left: 20px;
    }

    .circular-progress {
        justify-self: center;
        margin-top: 40px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-around;
    }

    .flag {
        margin-right: 1px !important;
    }

    .circular-contant {
        color: white;
    }

    .coaching-skills-simple {
        display:  flex;
        flex-direction: row;
        align-items: center;
    }

    .coaching-skills-simple>p {
        margin: 0;
    }
    .skills {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }
    .section-title{
        margin-bottom: 20px;
    }
</style>