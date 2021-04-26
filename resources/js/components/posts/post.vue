<template>
    <div class="container contenedor esPost down-4">

      <div v-if="this.loading" class="container text-center contieneCargador aparecer">
        <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>

      <div v-if="this.exist==false" class="container text-center contienevacio aparecer">
        <img src="/iconos/empty-tag.png" alt="">
        <h5 class="down-2">{{$ml.get('post').nopost}}</h5>
      </div>

      <div v-if="!this.loading && this.exist" class="card post sobreadoPlus aparecer">
        <div class="cabecerapoST">
          <avatar :conection="true" :us="data.user"></avatar>
            <div class="contieneDetPost">
              <p>{{data.user.name}}</p>
              <p class="thersAuctionNow aparecer" v-if="this.wantSeeAuctions && data.user.current_auctions.length>0">{{$ml.get('auction').currentAuctingUser}}</p>
              <p>{{data.fecha}}</p>
          </div>
          <div v-if="this.data.user.id == this.$store.state.auth.id" class="opcionesPost">
            <b-dropdown id="dropdown-right" right text="Right align" variant="primary" class="m-2 dropdown">
              <template v-slot:button-content class="no-border">
                <b-icon v-if="!removing" icon="three-dots-vertical" aria-hidden="true"></b-icon>
                <span v-if="removing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              </template>
              <b-dropdown-item  @click="remove()" href="#">{{$ml.get('post').remove}}</b-dropdown-item>
              <b-dropdown-item v-if="this.data.private==false && this.data.user.id == this.auth.id"  @click="openPrice()" href="#">{{$ml.get('post').makePrivate}}</b-dropdown-item>
            </b-dropdown>
          </div>

        </div>
        <div class="contieneImgPubli">
          <carousel :post="this.data"></carousel>
        </div>
        <div class="card-body">
          <p v-html="this.interpretateContent()"></p>
          <p v-if="this.data.hastags.length >0 && this.data.hastags[0].text !==''" class="hastags">
            <span   v-for="(hastag) in this.data.hastags" :key="hastag.id" >#{{hastag.text}} </span>
          </p>
        </div>
        <div v-if="this.data.user.canSee" class="contieneLikeComents">
          <div class="likes" @click="like()">
            <b-icon  v-if="this.liked" style="color: red;" icon="heart-fill" font-scale="1.5"></b-icon>
            <b-icon v-else style="color: black;" icon="heart-fill" font-scale="1.5"></b-icon>
            {{this.data.numLikes}}
          </div>
          <div  class="comments">
            <router-link v-if="this.data.canSee" class="noLink"  :to="'/post/'+this.data.id+'/coments'">
              {{this.data.numComments}} {{$ml.get('post').comments}}
            </router-link>
          </div>
        </div>
        <div v-if="this.data.user.canSee" class="separador"></div>
        <div v-if="this.data.canSee"  class="contieneOpcion">
          <router-link v-if="this.data.user.canSee" :to="'/post/'+this.data.id+'/coments'" class="noLink"  name="button">
            <b-icon style="color: black;" class="left" icon="chat-left" font-scale="1.5"></b-icon>
          </router-link>
          <div v-if="this.data.private && this.data.user.id == this.auth.id" class="flex">
            <b-icon  style="color: black; margin-left:10px; margin-top:-1px" class="left" icon="lock" font-scale="1.6"></b-icon>
            <p>{{this.data.price}} €</p>
          </div>
          <!-- <div class=" left iconPost euro ml-3">€</div> -->
          <propina :type="'publication'" class="propinaInPost" :otherUser="this.data.user" v-if="this.data.user.id !== this.$store.state.auth.id"></propina>

          <b-icon style="color: black;" class="left iconPost " icon="euro-sign" font-scale="1.5"></b-icon>
          <a class="twitter-share-button"
              :href="'https://twitter.com/intent/tweet?text='+this.data.content+' - OnlyFet&url='+this.currentUrl+'/post/'+this.data.id"
              data-size="large">
              <b-icon style="color: black;" class="right " icon="share-fill" font-scale="1.5"></b-icon>
          </a>
        </div>


        <div v-if="this.data.private && this.data.canSee == false" class="contieneOpcion contieneDesblokPost">
          <b-icon  icon="lock-fill" font-scale="2.5" aria-hidden="true"></b-icon>
          <p>{{this.data.price}} €</p>
          <button @click="unlock()" class="btn btn-primary ml-4">
              {{this.$ml.get('post').unlock}}
              <span v-if="this.loadingUnlock" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          </button>
        </div>
        <pricePost :data="this.data" v-if="this.lock" />

      </div>
    </div>
</template>

<script>



export default {
  props: {
    post_id:{
      default:false
    },
    datos:{
      default:null
    },
    wantSeeAuctions: {
      default:false
    }


  },
  data() {
    return{
      post:null,
      id:null,
      loading:true,
      loadingUnlock: false,
      exist:true,
      data:null,
      removing:false,
      liked:false,
      auth: this.$store.state.auth,
      currentUrl: window.location.hostname,
      setPrice:true,
      lock: false

    }

  },
  created() {

    this.checkPost();
  },
  methods: {
    checkPost(){
      if(this.post_id === false) {
        this.id = this.$route.params.post_id
        // debemos obtener la info del post
        this.getPost();
      } else {
        console.log(this.datos)
        this.id = this.post_id
        this.data = this.datos
        this.loading = false
        this.liked = this.datos.haveLiked
      }

    },
    unlock() {
      if(this.loadingUnlock)
        return true

      this.loadingUnlock = true
      var self = this;
      axios.post('/api/post/'+this.id+'/pay', null,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
       .then(function (response)  {
         if(response.data.rc == 1) {
            self.data.canSee = true
           }
           else {
             alert('Error')
           }
         })
         .catch(err => {
           self.error = true;
         })
         // finally
         .finally(() => self.loadingUnlock = false)

    },
    openPrice() {
      this.lock = true;
    },
    getPost() {
      var self = this
      axios.post('/api/post/'+this.id, null,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
       .then(function (response)  {
         if(response.data.rc == 1) {
           self.data=response.data.data
           self.liked = response.data.data.haveLiked

         }
         if(response.data.rc == 1500) {
           self.exist = false

         }
         if(response.data.rc == 13) {
           self.$router.push('/login')
         }
       })
       .catch(err => {
         self.error = true;
       })
       // finally
       .finally(() => self.loading = false)

    },
    interpretateContent() {
      var text = this.data.content
      var todo = text.split(" ");
      todo.forEach((palabra, i) => {
        if(palabra.includes("@")) {
          var p = palabra.split('@')[1]
          p = p.replace(',','')
          p = p.replace('.','')
          todo[i] = "<a href=/user/"+p+">"+p+"</a>"
        }
      });
      return todo.join(' ');

    },
    remove() {
      this.removing = true
      var self = this
      axios.post('/api/post/'+this.id+'/remove', null,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
       .then(function (response)  {
         if(response.data.rc == 1) {
           self.exist = false

         }
         if(response.data.rc == 1500) {
           self.exist = false
         }
         if(response.data.rc == 13) {
           self.$router.push('/login')
         }
       })
       .catch(err => {
         alert('Error')
       })
       // finally
       .finally(() => self.removing = false)
    },
    like() {
      var self = this
      axios.post('/api/post/'+self.data.id+'/like', {
        // page: self.current
      }, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(response => {
      })
      .finally(response => {

      })
      this.liked = !this.liked
      if(this.liked) {
        this.data.numLikes = this.data.numLikes+1
      } else {
        this.data.numLikes = this.data.numLikes-1
      }


    }
  }
};
</script>
