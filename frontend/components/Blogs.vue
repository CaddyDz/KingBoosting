<template>
  <div class="blogPosts-container">
    <div class="section-name">
      <h1>Blogs</h1>
    </div>
    <v-row no-gutters>
      <v-col class="description-col" align-self="center">
        <h1 class="headline mb-1">
          #{{ article.id }}
          {{ article.title }}
        </h1>
        <p class="description-txt">{{ article.excerpt }}</p>
        <v-btn color="#673ab7">READ MORE</v-btn>
        <v-btn color="#673ab7" outlined>GO TO BLOG</v-btn>
      </v-col>
      <v-col>
        <v-carousel
          cycle
          show-arrows-on-hover
          hide-delimiter-background
          hide-delimiters
          height="300"
          class="carousel"
          v-on:change="handleChange"
          progress
          progress-color="#673ab7"
        >
          <v-carousel-item
            v-for="index in articlesCount"
            :key="index"
            :src="$asset(article.image)"
          ></v-carousel-item>
        </v-carousel>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  props: ["firstArticle", "articlesCount"],
  data: () => ({
    currentSlide: 1,
    article: this.$props.firstArticle,
  }),
  methods: {
    handleChange(e) {
      this.$axios
        .get(`/blog/${e + 1}`)
        .then((response) => {
          this.article = response.data;
        })
        .catch((response) => {
          this.$notify("Something went wrong", "error", response.data.errors);
        });
    },
  },
};
</script>

<style>
.blogPosts-container {
  background-image: url("/img/blogPosts/blog-bg.webp");
  background-size: cover;
  height: 80vh;
  display: flex;
  align-items: center;
  background-color: #141618;
  position: relative;
}
.carousel {
  width: 40vw;
}
.description-col {
  margin-left: 70px;
}
.description-txt {
  padding-right: 40px;
}

h1 current-blog-number {
  font-size: 4rem;
  font-weight: bold;
  background: -webkit-linear-gradient(#bfb9ca, #9c27b0);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.section-name {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 200px;
  height: 60px;
  background: linear-gradient(#673ab7, #9c27b0);
  z-index: 1;
  position: absolute;
  top: -30px;
}
.section-name h1 {
  margin: 0;
}
</style>
