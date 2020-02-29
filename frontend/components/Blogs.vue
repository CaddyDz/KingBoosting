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
				<v-btn color="#fd905c">READ MORE</v-btn>
				<v-btn color="#fd905c" outlined>GO TO BLOG</v-btn>
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
					progress-color="#fd905c"
				>
					<v-carousel-item v-for="n in articlesCount" :key="n" :src="article.image"></v-carousel-item>
				</v-carousel>
			</v-col>
		</v-row>
	</div>
</template>

<script>
export default {
	data() {
		return {
			currentSlide: 1,
			articlesCount: 0,
			article: {}
		};
	},
	methods: {
		handleChange(e) {
			axios
				.get(`/api/blog/${e + 1}`)
				.then(response => {
					this.article = response.data;
				})
				.catch(errors => {
					console.log(errors.response.data.errors);
				});
		}
	},
	mounted() {
		axios
			.get("/api/blog/first")
			.then(response => {
				this.article = response.data;
			})
			.catch(errors => {
				console.log(errors.response.data.errors);
			});
		axios
			.get("/api/blog/count")
			.then(response => {
				this.articlesCount = response.data;
			})
			.catch(errors => {
				console.log(errors.response.data.errors);
			});
	}
};
</script>

<style>
.blogPosts-container {
	background-image: url("/img/blogPosts/blog-bg.webp");
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
	background: -webkit-linear-gradient(#d45151, #fd905c);
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
	background: linear-gradient(#d45151, #fd905c);
	z-index: 1;
	position: absolute;
	top: -30px;
}
.section-name h1 {
	margin: 0;
}
</style>
