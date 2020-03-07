<template>
	<div>
		<selector :step="steps[0]" />
		<slider :step="steps[2]" />
		<options :step="steps[3]" />
	</div>
</template>

<script>
export default {
	props: ["service"],
	data() {
		return {
			steps: [
				{ id: 1, title: "Select Your Current League" },
				{ id: 2, title: "Select Your Number Of Wins" },
				{ id: 2, title: "Select Your Number Of Wins" },
				{ id: 3, title: "Checkout" }
			]
		};
	}
};
</script>
