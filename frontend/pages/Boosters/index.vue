<template>
    <v-container class="container">
        <div class="title">
            <h1>Our Boosters</h1>
            <p>In this menu you can arrange and view our ELO booster and coach employees by using different filters. You can also learn about their detailed statistics throughout different ranked tiers and the champions they usually boost with.</p>
        </div>
        <div>
            <v-row>
                <v-col md="3">
                    menu    
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
    
}
</script>

<style>
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

</style>