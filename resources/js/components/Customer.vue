<template>
  <div class="customer">
    <div class="text-center d-flex justify-center align-center">
      <img src="/img/customers/review.png" height="100px" alt />
      <span class="display-1 font-weight-bold">
        Customer
        <span class="red--text">Voice</span>
      </span>
    </div>
    <div class="rating text-center">
      <span class="headline font-weight-bold">Excellent</span>
      <span class="d-block">
        4.77 Rating
        <span class="grey--text">4006 Review</span>
      </span>
    </div>
    <div class="text-center customer-slider">
      <v-container>
        <v-slide-group v-model="model" class="pa-4" active-class="success" show-arrows>
          <v-slide-item v-for="n in 20" :key="n">
            <v-col class="custom-bg">
            <v-card class="text-left card-customer " height="100" width="300">
                <div>
                    <v-icon color="orange" class="mx-1 " small="">mdi-star</v-icon> <span>Jon Doe</span> <span class="mx-2 text--grey caption font-italic">Verified</span>
                </div>
                <v-card-text>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                </v-card-text>
            </v-card>
            </v-col>
          </v-slide-item>
        </v-slide-group>
      </v-container>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    model: null
  })
};
</script>

<style scoped>
.customer {
    background-color: #141618;
    padding: 50px 0;
}
.card-customer {
    background-color: #212427;
}
.custom-bg {
	background-image: linear-gradient(to right, #d45151, #fd905c);
    padding: 5px;
    margin: 10px;
    border-radius: 10px;
}

</style>
