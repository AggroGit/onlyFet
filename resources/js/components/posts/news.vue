<template>
    <div class="contenedor">
      <!-- {{this.current}}/{{this.total}} -->
      <div v-if="this.loading" class="container text-center contieneCargador">
        <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
      <post v-for="(post) in this.posts" :key="post.id" :post_id="post.id" :datos="post" ></post>
      <div v-if="this.scrolling" class="container text-center contieneCargador">
        <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
      <div v-if="this.current >= this.total" class="container text-center aparecer down-5">
        <h5>{{$ml.get('post').noposts}}</h5>
      </div>
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
      total:1,
      scrolling:false


    }
  },
  created() {
  },
  mounted() {
    if(this.wall === false) {
      this.getPosts();
    } else {
      this.getPostsUser();
    }
    this.scroll();
  },
  methods: {
    getPosts() {
      var self = this
      console.log(self.current)
      // alert('hey')
      if(self.current<self.total && self.scrolling==false)
        self.scrolling = true
        axios.post('/api/news', {
          page: self.current
        }, {
          headers:{
             Authorization: `Bearer `+ this.$store.state.token
          }
        })
        .then(response => {
          if(response.data.rc == 1) {
            response.data.data.data.forEach(element => this.posts.push(element));
            this.total = response.data.data.last_page
            console.log(response.data.data.data)
          }
        })
        .finally(response => {
          this.loading = false;
          this.current++;
          this.scrolling = false
        })
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
    getPostsUser() {
      console.log('KASCIAIWDISNAISIOSANDSANDLKNSADSADI SCAIOSNC')
      var self = this
      console.log(self.current)
      // alert('hey')
      if(self.current<self.total && self.scrolling==false)
        self.scrolling = true
        axios.post('/api/user/'+self.wall+'/posts', {
          page: self.current
        }, {
          headers:{
             Authorization: `Bearer `+ this.$store.state.token
          }
        })
        .then(response => {
          if(response.data.rc == 1) {
            response.data.data.data.forEach(element => this.posts.push(element));
            this.total = response.data.data.last_page
            console.log(response.data.data.data)
          }
        })
        .finally(response => {
          this.loading = false;
          this.current++;
          this.scrolling = false
        })
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
