<template>
	<div class="why-us">
		<h1 class="text-center">Why Us</h1>
		<ul>
			<v-container>
				<li v-for="(item ,index) in whyUsData" :key="index">
					<v-row>
						<v-col md="2 d-flex justify-center align-center">
							<v-icon>mdi-star</v-icon>
						</v-col>
						<v-col md="10">
							<h2>{{ item.title }}</h2>
							<p>{{ item.description }}</p>
						</v-col>
					</v-row>
				</li>
			</v-container>
		</ul>
	</div>
</template>

<script>
export default {
	data: () => ({
		whyUsData: []
	}),
	methods: {
		fetchWhyUsData() {
			axios
				.get("/api/priorities")
				.then(Response => (this.whyUsData = Response.data));
		}
	},
	mounted() {
		this.fetchWhyUsData();
	}
};
</script>

<style scoped>
.why-us {
	border-top: 5px solid gray;
	background-color: #000;
	padding: 50px 0;
}

h1 {
	margin-bottom: 50px;
}

ul {
	list-style-type: none;
	background-color: #212224;
}

h2 {
	color: #c7836e;
}

p {
	color: #999a9c;
}
</style>
