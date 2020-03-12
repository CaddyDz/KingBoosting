<template>
    <v-container class="container">
        <div class="title">
            <h1>Our Boosters</h1>
            <p>In this menu you can arrange and view our ELO booster and coach employees by using different filters. You can also learn about their detailed statistics throughout different ranked tiers and the champions they usually boost with.</p>
        </div>
        <div>
            <v-row>
                <v-col md="3">
                    
                    <v-list>
                        <v-list-group
                            prepend-icon="mdi-server-network"
                            value="true">
                            <template v-slot:activator>
                                <v-list-item-title>Servers</v-list-item-title>
                            </template>
                            <v-list-item
                                v-for="(admin, i) in admins"
                                :key="i"
                                link
                            >
                                <v-list-item-avatar >
                                    <v-checkbox
                                    color="primary"
                                    @change="commitFilter({id:i , value : $event})"
                                ></v-checkbox>
                                </v-list-item-avatar>
                                <v-list-item-title v-text="admin[0]"></v-list-item-title>
                            </v-list-item>
                        </v-list-group>
                        <v-list-group
                            prepend-icon="mdi-chart-line-variant"
                            value="true">
                            
                            <template v-slot:activator>
                                <v-list-item-title>Rank</v-list-item-title>
                            </template>

                        </v-list-group>
                        <v-list-group
                            prepend-icon="mdi-translate"
                            value="true">
                            
                            <template v-slot:activator>
                                <v-list-item-title>Language</v-list-item-title>
                            </template>
                            <v-list-item
                                v-for="(country, i) in countrys"
                                :key="i"
                                link
                            >
                                <v-list-item-avatar >
                                    <v-checkbox
                                        color="primary"
                                        @change="commitFilter({id:i , value : $event , country : country.name })"
                                    ></v-checkbox>
                                </v-list-item-avatar>
                                <v-list-item-title class="lng-title">
                                    <country-flag :country="country.code" size='small'/>
                                    <p class="lng-name">{{country.name}}</p>
                                </v-list-item-title>
                            </v-list-item>
                        </v-list-group>                        
                        </v-list>
                </v-col>
                <v-col md="9">
                    cards
                </v-col>
            </v-row>

        </div>
    </v-container>
</template>

<script>
export default {
    data(){
        return {
            active: "false",
            settings: [],
            countrys: [
                {name:"Russia",code:"rus"},
                {name:"English",code:"us"},
                {name:"Ukrainian",code:"ua"},
                {name:"German",code:"de"},
                {name:"Estonian",code:"ee"},
                {name:"Spani",code:"es"},
                {name:"Italian",code:"it"},
            ],
            cruds: [
                ['Create', 'add'],
                ['Read', 'insert_drive_file'],
                ['Update', 'update'],
                ['Delete', 'delete'],
            ],
        }
    },
    methods: {
        commitFilter(i){
            // TODO: commit filter 
            console.log(JSON.stringify(i));
        }
    }
}
</script>

<style scoped>
    .title {
        text-align: center;
        display: flex;
        flex: 1;
        flex-direction: column !important;
        align-items: center;
        justify-content: center;
        margin-top: 30px;
    }
    
    .title>h1 {
        margin-bottom: 20px;
    }

    .title>p {
        font-size : medium;
        font-weight : normal;
    }

    .lng-title {
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .lng-title>.lng-name {
        margin-bottom: 0;
        margin-left: 15px;
    }

</style>