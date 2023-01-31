<template>
  <div>
    <p>Hey I'm booster {{ booster.name }}</p>
  </div>
</template>

<script>
export default {
  head: () => ({
    title: this.booster.name,
    meta: [
      {
        hid: "desc",
        name: "description",
        content: this.booster.description,
      },
    ],
  }),
  data: () => ({
    booster: {},
  }),
  methods: {
    getBooster(id) {
      this.booster = id;
    },
  },
  mounted() {
    this.getBooster(this.$route.params.Booster);
  },
};
</script>
