<template>
  <v-layout
    column
  >
    <why-us />
    <blogs />
  </v-layout>
</template>

<script>
import Blogs from '~/components/Blogs.vue'
import WhyUs from '~/components/WhyUs.vue'
export default {
  components: {
    Blogs,
    WhyUs
  }
}
</script>
