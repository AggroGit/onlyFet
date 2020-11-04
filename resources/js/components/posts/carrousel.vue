<template>
  <div class="carrousel">
    <b-carousel
      id="carousel-1"
      :interval="40000"
      v-model="slide"
      controls
      indicators
      background="#ababab"
      img-width="1024"
      img-height="480"
      style="text-shadow: 1px 1px 2px #333;transition:all 0.4s ease;"
      @sliding-start="onSlideStart"
      @sliding-end="onSlideEnd"
    >
      <!-- Text slides with image -->
      <!-- <b-carousel-slide
        caption="First slide"
        text="Nulla vitae elit libero, a pharetra augue mollis interdum."
        img-src="https://picsum.photos/1024/480/?image=52"
      ></b-carousel-slide> -->
      <!-- Slides with custom text -->
      <!-- <b-carousel-slide img-src="https://picsum.photos/1024/480/?image=54">
        <h1>Hello world!</h1>
      </b-carousel-slide> -->

      <!-- Slides with image only -->
      <!-- <b-carousel-slide img-src="https://picsum.photos/1024/480/?image=58"></b-carousel-slide> -->

      <!-- Slides with img slot -->
      <b-carousel-slide v-for="(media) in this.post.videos" :key="media.id" img-blank img-alt="Blank image">
        <template #img>
        <img
          @click="premium()"
          class="imagenCompleta"
          :src="'/iconos/video.png'"
          v-if="!post.user.canSee"
          alt="image slot"
        >

        <video-player
        class="video"
                         ref="videoPlayer"
                         v-else
                         :options="giveMeOptions(media)"
                         :playsinline="true"
                         @play="onPlayerPlay($event)"
                         @pause="onPlayerPause($event)"
                         @ended="onPlayerEnded($event)"
                         @loadeddata="onPlayerLoadeddata($event)"
                         @waiting="onPlayerWaiting($event)"
                         @playing="onPlayerPlaying($event)"
                         @timeupdate="onPlayerTimeupdate($event)"
                         @canplay="onPlayerCanplay($event)"
                         @canplaythrough="onPlayerCanplaythrough($event)"
                         @ready="playerReadied"
                         @statechanged="playerStateChanged($event)"
                          oncontextmenu="return false;"
                         >
                          >

         </video-player>
        <!-- <video-player v-else>

         </video-player> -->
        <!-- <video v-else width="100%" height="100%" autoplay>
          <source :src="media.Path" type="video/mp4">
          Your browser does not support the video tag.
        </video> -->
      </template>
      </b-carousel-slide>
      <!-- Note the classes .d-block and .img-fluid to prevent browser default image alignment -->
      <b-carousel-slide v-for="(media) in this.post.images" :key="media.id" >
        <template #img>
          <router-link class="allA" :image="media" :to="this.giveMeUrl(media,post)">
            <img
              class="imagenCompleta"
              :src="media.sizes.Big"
              v-if="post.user.canSee || post.user.id == auth.id"
              alt="image slot"
            >
            <img
              class="imagenCompleta"
              :src="media.sizes.Hidden"
              v-else
              alt="image slot"
            >
            </router-link>
        </template>
      </b-carousel-slide>

      <!-- Slide with blank fluid image to maintain slide aspect ratio -->

    </b-carousel>

    <!-- <p class="mt-4">
      Slide #: {{ slide }}<br>
      Sliding: {{ sliding }}
    </p> -->
  </div>
</template>

<script>
  export default {
    props:['post'],
    data() {
      return {
        slide: 0,
        auth:this.$store.state.auth,
        sliding: null,





      }
    },
    mounted() {

console.log('AQUI')
console.log(this.post)
    },
    premium() {
      alert(this.$ml.get('stripe').onlyPremium)
    },
    methods: {
      onSlideStart(slide) {
        this.sliding = true
      },
      onSlideEnd(slide) {
        this.sliding = false
      },
      alertar() {

      },
      giveMeUrl(media,post) {
        if(post.user.canSee == false) {

        }
        return null

      },
      giveMeOptions(media) {
        return {

            height: '360',
            autoplay: true,
            muted: true,
            language: 'en',
            playbackRates: [0.7, 1.0, 1.5, 2.0],
            sources: [{
              type: "video/mp4",//+media.format,
              // mp4
              src: media.path,
              // webm
              // src: "https://cdn.theguardian.tv/webM/2015/07/20/150716YesMen_synd_768k_vp8.webm"
            }],
            // poster: "https://surmon-china.github.io/vue-quill-editor/static/images/surmon-1.jpg",

        }



         /*{
          autoplay: true,
          // muted: true,
          language: this.$store.state.auth.lang,
          sources: [{
            type: "video/"+media.format,
            // mp4
            src: media.path,
            // webm
            // src: "https://cdn.theguardian.tv/webM/2015/07/20/150716YesMen_synd_768k_vp8.webm"
          }],
          poster: "/iconos/video.png",
        }

*/






      },







      onPlayerPlay(player) {
        // console.log('player play!', player)
      },
      onPlayerPause(player) {
        // console.log('player pause!', player)
      },
      onPlayerEnded(player) {
        // console.log('player ended!', player)
      },
      onPlayerLoadeddata(player) {
        // console.log('player Loadeddata!', player)
      },
      onPlayerWaiting(player) {
        // console.log('player Waiting!', player)
      },
      onPlayerPlaying(player) {
        // console.log('player Playing!', player)
      },
      onPlayerTimeupdate(player) {
        // console.log('player Timeupdate!', player.currentTime())
      },
      onPlayerCanplay(player) {
        // console.log('player Canplay!', player)
      },
      onPlayerCanplaythrough(player) {
        // console.log('player Canplaythrough!', player)
      },
      // or listen state event
      playerStateChanged(playerCurrentState) {
        // console.log('player current update state', playerCurrentState)
      },
      // player is ready
      playerReadied(player) {
        // seek to 10s
        console.log('example player 1 readied', player)
        player.currentTime(10)
        // console.log('example 01: the player is readied', player)
      }

    }
  }
</script>
