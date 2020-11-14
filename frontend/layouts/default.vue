<template>
  <v-app dark>
    <notification-component></notification-component>
    <v-system-bar lights-out app>
      <!-- <social-component></social-component> -->
      <v-spacer></v-spacer>
      <!-- <top-right-links></top-right-links> -->
    </v-system-bar>
    <app-bar-component></app-bar-component>
    <v-main>
      <v-container fluid>
        <nuxt />
      </v-container>
    </v-main>
    <v-footer absolute class="font-weight-medium">
      <img src="/img/header_logo.png" alt="King Boosting" />
      <v-col class="text-center" cols="12">
        {{ new Date().getFullYear() }} —
        <strong>King Boosting</strong>
      </v-col>
    </v-footer>
  </v-app>
</template>

<script>
export default {};
</script>

<style scoped>
.container {
  padding: 0px;
}
</style>
