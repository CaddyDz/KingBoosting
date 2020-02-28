<template>
  <div class="blogPosts-container">
      <div class="section-name">
        <h1>
          Blogs
        </h1>
      </div>
      <v-row no-gutters>
          <v-col
            class="description-col"
            align-self="center"
          >
            <h1 class="headline mb-1" >
              <current-blog-number> #{{currentSlide+1}} </current-blog-number> 
              {{blogPosts[currentSlide].title}}
            </h1>
            <p class="description-txt">
              {{blogPosts[currentSlide].description}}
            </p>
            <v-btn color="#673ab7" @click="handelPostClick" >READ MORE</v-btn>
            <v-btn color="#673ab7" outlined @click="handelBlogClick" >GO TO BLOG</v-btn>
          </v-col>
          <v-col >
              <v-carousel
                cycle
                show-arrows-on-hover
                hide-delimiter-background
                hide-delimiters
                height="300"
                class="carousel"
                v-on:change="handelChange"
                progress
                progress-color="#673ab7"
              >
                  <v-carousel-item 
                    v-for="(item,i) in blogPosts" 
                    :key="i" :src="item.src"
                  >
                  </v-carousel-item>

              </v-carousel>
          </v-col>
      </v-row>
  </div>
</template>

<script>
    export default {
      methods:{
        handelChange(e){
          this.currentSlide = e;
        },
        handelPostClick(){

        },
        handelBlogClick(){
          
        }

      },
      data() {
          return {
              currentSlide : 0,
              blogPosts:[
                {
                  title: "The Most Common Mistakes in Low ELO",
                  description: "It takes some time to get the hang of League of Legends. Climbing the ranks is not meant to be smooth sailing by any means, but if you find yourself always working hard and getting nowhere it can make you want to give up. Despite analyzing what the professionals are doing and reviewing statistics over and over again,",
                  src : '/img/blogPosts/Mistakes-blog.jpg'
                },
                {
                  title:"How Data Drives Champion Balance",
                  description: "After interviewing a friend who works for the Riot LoL Champion Balance Team that consistently makes strategic changes to balance the game by reading and analyzing various data, I'd like to share with you all a few keys to understanding the correlations between data and champion balance,",
                  src : '/img/blogPosts/Champion-Balance-blog.png'
                },
                {
                  title:"Tarzaned - The booster",
                  description: "Most people who make a career in League of Legends world are mostly those nice content creators, professional players or entertainers. Some however, follow a totally different path. And such path was taken by Tarzaned, famous NA booster who has reached the #1 spot on the NA ladder multiple times, that is in season 6, 7, and 8. He is mostly known",
                  src : '/img/blogPosts/Tarzaned-The-booster-blog.png'
                }
              ]
          }
      }
    }

</script>

<style>
  .blogPosts-container{
    background-image: url('/img/blogPosts/blog-bg.webp');
    height: 80vh;
    display: flex;
    align-items: center;
    background-color: #141618;
    position: relative;
  }
  .carousel{
    width: 40vw;
  }
  .description-col{
    margin-left: 70px;
  }
  .description-txt{
    padding-right: 40px;
  }
    
  h1 current-blog-number {
    font-size: 4rem;
    font-weight: bold;
    background: -webkit-linear-gradient(#673ab7, #9c27b0);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  .section-name{
    display: flex;
    align-items: center;
    justify-content: center;
    width: 200px;
    height: 60px;
    background: linear-gradient(#673ab7, #9c27b0);
    z-index: 1; 
    position: absolute ;
    top: -30px;
  }
  .section-name h1 {
    margin: 0;
  }
</style>