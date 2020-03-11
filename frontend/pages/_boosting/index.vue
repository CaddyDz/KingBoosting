<template>
  <div>
    <div class="slider" fluid :style="{background: `url(${service.bg_img}) top/cover`}">
      <v-row justify="center" align="center">
        <v-col md="8" style="text-align: center;">
          <h1>{{ service.name }}</h1>
          <br />
          {{ service.description }}
        </v-col>
      </v-row>
      <services-slider :service="service" />
    </div>
    <v-container>
      <v-row>
        <v-col md="8">
          <nuxt-child :service="service">
            <!-- <stepper /> -->
          </nuxt-child>
        </v-col>
        <v-col md="4">
          <div class="title">
            <div class="title-id">
              <h2>V</h2>
            </div>
            <h3 class="right-cards-title">erified Reviews</h3>
          </div>
          <v-card raised class="mb-4">
            <div class="customer-voice-container">
              <v-icon size="90" class="shield-icon have-linear-gradient">mdi-message-text-outline</v-icon>
              <span class="display-1 font-weight-bold">
                {{ $t('Customer') }}
                <span class="have-linear-gradient">{{ $t('Voice') }}</span>
              </span>
              <span class="headline font-weight-bold have-linear-gradient">Excellent</span>
              <span class="d-block">
                4.77 Rating
                <span class="grey--text">4006 Review</span>
              </span>
            </div>
			<v-divider style="margin-top:20px"></v-divider>
            <v-list style="max-height: 300px"
						class="scroll-y overflow-y-auto">
					<v-list-item
						v-for="user in users"
						:key="user.id"
					>
						<v-list-item-content>
							<v-list-item-title >lorem ipsum ?! ....</v-list-item-title>
				            <v-list-item-subtitle v-html="user.name"></v-list-item-subtitle>
						</v-list-item-content>
					</v-list-item>

            </v-list>
          </v-card>
		  <div class="title">
            <div class="title-id">
              <h2>O</h2>
            </div>
            <h3 class="right-cards-title">ur Features</h3>
          </div>
		  <v-card :style="{background: `url(${service.bg_img}) top/cover`}" class="bg">
			  <v-container>
				<div v-for="future in futures" :key="future.id" class="futures"  >
					<v-icon color="#673ab7">mdi-check</v-icon>
					<p class="future-name">{{future.name}}</p>
					<p :style="{color:'#9c27b0'}">{{future.coast}}</p>
				</div>
			  </v-container>
		  </v-card>
        </v-col>
      </v-row>
    </v-container>
    <how-to-buy />
  </div>
</template>

<script>
  import Stepper from "~/components/Stepper";

  export default {
    components: {
      // Stepper
    },
    data() {
      return {
        slug: "",
		service: {},
		futures: [{name: "Private VPN",
			id: 1,
			coast: "INCLUDED"},
          	{name: "Spell Order Select",
			id: 2,
			coast: "Free"},
			{name: " Appear Offline",
			id: 3,
			coast: "Free"},
			{name: " Order Tracking & Scheduling",
			id: 4,
			coast: "GUARANTEED"},
			{name: " Finish in time",
			id: 5,
			coast: " Free"},
			{name: "Chat with Booster",
			id: 6,
			coast: "Free"},
		  	{name: " 24/7 Premium Support",
			id: 7,
			coast: "INCLUDED"}
		],
        users: [{
            name: "bob",
            id: 1
          },
          {
            name: "bob",
            id: 2
          }, {
            name: "bob",
            id: 3
          }, {
            name: "bob",
            id: 4
          }, {
            name: "bob",
            id: 5
          }, {
            name: "bob",
            id: 6
          }, {
            name: "bob",
            id: 7
          }, {
            name: "bob",
            id: 8
          }, {
            name: "bob",
            id: 9
          }, {
            name: "bob",
            id: 10
          }, {
            name: "bob",
            id: 11
          }, {
            name: "bob",
            id: 12
          }, {
            name: "bob",
            id: 13
          }, {
            name: "bob",
            id: 14
          }, {
            name: "bob",
            id: 15
          }, {
            name: "bob",
            id: 16
          },
        ]
      };
    },
    methods: {
      slideChange(e) {
        this.slug = "/" + e;
        this.getService();
      },
      getService() {
        // console.log(`i will call with ${this.slug}`)
        this.$axios
          .get(`/services${this.slug}`)
          .then(response => {
            this.service = response.data;
          })
          .catch(error => {
            console.log(error);
          });
      }
    },
    mounted() {
      this.slug = this.$route.path;
      this.getService();
    }
  };

</script>
<style>
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
