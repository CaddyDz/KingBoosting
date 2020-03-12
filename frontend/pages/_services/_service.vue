<template>
	<div>
		<div class="slider" fluid :style="{background: `url(${service.bg_img}) top/cover`}">
			<v-row justify="center" align="center">
				<v-col md="8" style="text-align: center;">
					<p>{{ service.name }}</p>
					<br />
					{{ service.description }}
				</v-col>
			</v-row>
			<services-slider :service="service" />
		</div>
		<v-container>
			<v-row>
				<v-col md="8">
					<stepper />
				</v-col>
				<v-col md="4"></v-col>
			</v-row>
		</v-container>
		<how-to-buy />
	</div>
</template>

<script>
import Stepper from "~/components/Stepper";

export default {
	name: "service",
	components: {
		Stepper
	},
	data() {
		return {
			slug: "",
			service: {}
		};
	},
	methods: {
		slideChange(e) {
			this.slug = "/" + e;
			this.getService();
		},
		getService() {
			this.$axios
				.get(`/services${this.slug}`)
				.then(response => {
					this.service = response.data;
				})
				.catch(error => {
					this.$store.commit("notification/open", {
						text: this.$i18n.t("Something went wrong"),
						mode: "error"
					});
				});
		}
	},
	mounted() {
		this.slug = this.$route.path;
		this.getService();
	}
};
</script>
<style scoped>
  h1 {
    margin-top: 20px;
  }

  h3 {
    /* margin-top: 5px; */
  }

  .customer-voice-container {
	margin-top: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .have-linear-gradient {
    background: -webkit-linear-gradient(#673ab7, #9c27b0);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .title {
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: 20px;
  }

  .title-txt {
    margin-left: 20px;
  }

  .title .title-id {
    color: #181818;
  }

  .title-id {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(#673ab7, #9c27b0);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .futures {
	  display: flex;
	  flex-direction: row;
	  align-items: center;
	  justify-content: space-between;
  }
  .futures .future-name > p {
	  align-self: flex-start;
  }

  .bg {
	background-size: 'cover';
  }

	.right-cards-title {
	  z-index: 1 ;
	  margin-left: -13px;
	}

	.shield-icon {
		padding-top: 20px;
	}
	


</style>
