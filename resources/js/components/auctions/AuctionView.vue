<template>
    <div class="container-fluid contenedor">

      <!-- LOADING -->
      <div v-if="this.loading" class="container text-center contieneCargador">
        <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
      <div v-if="!this.loading && this.auction.status == 'open'" class="">
        <!-- TIEMPO -->
        <div v-if="!this.loading" class="row justify-content-center down-4">
          <div class="col-6">
            <h4>{{this.$ml.get('auction').limitTime}}</h4>
          </div>
          <div class="col-6 text-right">
            <countdown :time="this.remaining*1000">
                <template slot-scope="props">{{ props.days }} d, {{ props.hours }} h, {{ props.minutes }} m, {{ props.seconds }} s.</template>
          </countdown>
          </div>
        </div>

        <!-- Imagenes  -->
        <div v-if="!this.loading" class="row justify-content-center">
          <div v-for="(image) in this.auction.images" :key="image.id"  class=" col-6 down-4">
            <div class="ContieneImgGrid" @click="ir(image)">
              <img class="imgGrid"  :src="image.sizes.Med" alt="">
            </div>
          </div>
        </div>

        <div v-if="!this.loading" class="row justify-content-center down-4">
          <div class="col-12 text-center">
            <h4>{{auction.title}}</h4>
          </div>
          <div class="col-12 down-2">
            <p>{{auction.description}}</p>
          </div>
        </div>

        <div v-if="!this.loading" class="row justify-content-center down-4">
          <div class="col-6">
            <strong>
                <p>{{this.$ml.get('auction').maxCurrent}}</p>
            </strong>

          </div>
          <div class="col-6 text-right">
            <strong>
                <p>{{auction.current_auction}} €</p>
            </strong>
          </div>
          <div class="col-12">
            <p class="grisaceo">{{this.$ml.get('auction').auctionInfo}}</p>
          </div>
          <div class="separadorRRSS down-2"></div>

          <div class="col-12 down-2">
            <h2 v-if="this.auth.id == this.auction.current_id" class=" aparecer text-center">{{this.$ml.get('auction').maxUs}}</h2>
            <h4 v-else class=" aparecer text-center">{{this.$ml.get('auction').NoMaxUs}}</h4>
          </div>
        </div>

        <form  @submit.stop.prevent="bideUp()" >
          <div v-if="!this.loading" class="row justify-content-center down-2">
            <entrada v-model="form.price" :min="this.auction.current_auction+1" :type="'number'" :label="this.$ml.get('auction').cuntBideUp"></entrada>
          </div>

          <div class="form-group row down-1">
              <div class="col-md-12 offset-md-12">
                  <button type="submit" class="btn btn-primary boton">
                      {{$ml.get('auction').bideUpMore}}
                      <span v-if="this.bideUping" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  </button>
              </div>
          </div>
        </form>

        <div class="row down-2" >
          <div class="col-md-12">
            <div v-if="this.error" class="alert alert-danger">
              <strong>{{$ml.get('auth').prError}}</strong> {{this.errorMessage}}
            </div>
          </div>
        </div>
    </div>

    <div  v-if="!this.loading && this.auction.status !== 'open'">
      <h2 class="text-center down-2">{{this.$ml.get('auction').finishedAuction}}</h2>
    </div>

    </div>
</template>

<script>



export default {

  data() {
    return {
      loading:true,
      auction:null,
      bideUping: false,
      errorMessage:"",
      error:false,
      remaining:null,
      auth: this.$store.state.auth,
      form : {
        price:1
      }
    }
  },
  beforeRouteLeave (to, from, next) {
    this.unsuscribe();
    next()
  },
  created() {
    this.getAuction()
  },
  mounted() {
    console.log(window.name);
  },
  methods: {
    unsuscribe() {
      console.log('quit suscribe')
      Echo.leave('Auction.'+this.chat)
      // this.channel.pusher.unsubscribe('presence-'+appCode+'.Chat.'+this.chat);
    },

    bideUp() {
      var self = this;
      this.bideUping = true;
      //
      axios.post('/api/auction/'+self.auction.id+'/bidup', this.form, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(function (response) {

        if(response.data.rc == 1) {

        }
        if(response.data.rc == 1602) {
          self.error = true
          self.errorMessage = self.$ml.get('auction').notUser
        }
      })
      .catch(function (error) {
        alert('error')
      })
      .finally(function (response) {
        self.bideUping = false;
      })


    },
    ir(image) {
      this.$router.push('/post/'+image.post_id);
    },

    getAuction() {
      if(this.auction == null) {
        var auction_id = this.$route.params.auction_id
        var self = this;
        axios.post('/api/auction/'+auction_id, null, {
          headers:{
             Authorization: `Bearer `+ this.$store.state.token
          }
        })
        .then(function (response) {
            console.log(response)
          if(response.data.rc == 1) {
            self.auction = response.data.data
            self.remaining = response.data.data.remainingSeconds
            self.listenAuctionSocket();
          } else {
            self.$router.push('/');
          }
        })
        .catch(function (error) {
          alert('error')
        })
        .finally(function (response) {
          self.loading = false;
        })
      }
    },
    listenAuctionSocket() {
      var self = this;
      this.channel = Echo.join(appCode+'.Auction.'+this.auction.id)
      .listen('BidUpEvent', (auction) => {
          console.log(auction)
          self.auction = auction.auction
      })

    }
  }
};
</script>
