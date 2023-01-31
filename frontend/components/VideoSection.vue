<template>
  <v-row
    style="background: rgba(0, 0, 0, 0.5)"
    justify="center"
    class="mt-16 mb-16"
  >
    <v-dialog v-model="dialog">
      <template v-slot:activator="{ on }">
        <v-icon color="white darken-2" size="1000%" v-on="on" light
          >mdi-play-circle-outline</v-icon
        >
      </template>
      <iframe
        src="https://www.youtube.com/embed/LO1mTELoj6o?controls=0"
        frameborder="0"
        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
      ></iframe>
    </v-dialog>
  </v-row>
</template>

<script>
export default {
  data: () => ({
    dialog: false,
  }),
};
</script>

<style>
.theme--light.v-icon:hover {
  color: #4a148c !important;
  caret-color: #4a148c !important;
  animation: none;
}
.theme--light.v-icon {
  width: 200px;
  height: 200px;
  box-shadow: 0 0 0 0 #4a148c;
  border-radius: 50%;
  animation: pulse 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
}
@keyframes pulse {
  to {
    box-shadow: 0 0 0 45px rgba(232, 76, 61, 0);
  }
}
</style>
