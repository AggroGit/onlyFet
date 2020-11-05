<template>
    <div class="container contenedor down-4">
      <div v-if="this.loading" class="container text-center contieneCargador">
        <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>


      <div v-if="!this.loading" class="row contenedor">
        <!-- <div class="container"> -->
          <div v-for="(image) in this.posts" :key="image.id"  class=" col-6 down-4">
            <div class="ContieneImgGrid" @click="ir(image)">
              <!-- <router-link   :to="'/'+image.post_id+'/image/'+image.name"> -->
              <img v-if="user.canSee || user.id == auth.id"   class="imgGrid"  :src="image.sizes.Med" alt="">
            <!-- </router-link> -->
              <img
                class="imgGrid"
                :src="image.sizes.Hidden"
                v-else
                alt="image slot"
              >
            </div>

          </div>
        <!-- </div> -->
        <div v-if="this.posts.length == 0" class="Empty text-center">
          <img src="/iconos/empty-tag.png" alt="">
          <h5>{{$ml.get('post').noposts}}</h5>
        </div>

      </div>







      <!-- {{this.current}}/{{this.total}} -->

      <!-- <post v-for="(post) in this.posts" :key="post.id" :post_id="post.id" :datos="post" ></post>
      <div v-if="this.scrolling" class="container text-center contieneCargador">
        <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
      <div v-if="this.current >= this.total" class="container text-center aparecer down-5">
        <h5>{{$ml.get('post').noposts}}</h5>
      </div> -->
    </div>
</template>
<script>



export default {
  props:{
    wall: {
      default:false
    }

  },
  data() {
    return {
      loading:true,
      posts:[],
      current:1,
      user:null,
      auth:this.$store.state.auth,
      total:1,
      scrolling:false


    }
  },
  created() {
    // alert('hola ma⁄ma')
  },
  mounted() {
    this.getPosts();
    this.scroll();
  },
  methods: {
    getPosts() {
      var self = this
      console.log(self.current)
      // alert('hey')
      if(self.current<self.total && self.scrolling==false)
        self.scrolling = true
        axios.post('/api/images/'+this.$route.params.nickname, {
          page: self.current
        }, {
          headers:{
             Authorization: `Bearer `+ this.$store.state.token
          }
        })
        .then(response => {
          if(response.data.rc == 1) {
            this.user = response.data.data.user
            response.data.data.images.data.forEach(element => this.posts.push(element));
            this.total = response.data.data.images.last_page
            console.log(response.data.data.data)
          }
        })
        .finally(response => {
          this.loading = false;
          this.current++;
          this.scrolling = false
        })
      },
      ir(image) {
        this.$router.push('/post/'+image.post_id);
      },

      scroll () {
      window.onscroll = () => {
        let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight
        if (bottomOfWindow) {
         this.scrolledToBottom = true // replace it with your code
         if(this.scrolling== false)
         {
            this.getPosts()
         }

        }
      }
    },


      scroll () {
      window.onscroll = () => {
        let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight
        if (bottomOfWindow) {
         this.scrolledToBottom = true // replace it with your code
         if(this.scrolling== false)
         {
            this.getPosts()
         }

        }
      }
    }

    }
  }
</script>
