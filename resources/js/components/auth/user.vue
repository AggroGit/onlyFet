<template>
      <div class="container-fluid">
        <div v-if="this.loading" class="container text-center contieneCargador">
          <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>

        <!-- CACEBZERA FOTO  -->
        <div v-if="!this.loading" class="row justify-content-center down-2 contenedor">
          <div class="col-6 text-center">
            <div class="ProfileImgCon">
              <avatar :conection="true" class="ProfileImgCon" :us="this.user" :size="'Small'"></avatar>
            </div>
          </div>
          <div class="col-6 ContieneDatosperfil">
            <div class="contieneNombreYOpciones">
              <h4 class="">{{user.name}}</h4>
              <div class="contieneOpcionesUser ">
                  <a class="twitter-share-button"
                      target="_blank" :href="'https://twitter.com/intent/tweet?text=See on OnlyFet '+this.user.nickname+' - OnlyFet&url='+this.currentUrl"
                      data-size="large">
                      <b-icon style="color: black;" class="left " icon="share-fill" font-scale="1.5"></b-icon>
                  </a>
                  <fav v-if="auth.id !== user.id" :user = "this.user"/>
                  <propina  v-if="user.influencer && auth.id !== user.id" :otherUser="user"></propina>
              </div>
            </div>

              <p><strong>@</strong>{{this.user.nickname}}
              </p>
              <p v-html="this.user.description">
              </p>

            <router-link to='/request/influecer' v-if="!this.user.influencer && this.user.id == this.auth.id && this.auth.stripe_reciver_id == null" class="rojo" href="">{{$ml.get('auth').startWiningMoney}}</router-link>

          </div>
        </div>






        <div v-if="!this.loading" class="row justify-content-center contieneEnrutador sombreadoInferior contenedor mt-5">

            <div class="col-4 text-center">
              <router-link class="noLink enrutadorMen" :to="'/user/'+this.user.nickname+'/wall'">
                Muro
              </router-link>
              <div v-if="this.$route.name == 'wall'"  class="lineaSeparadora aparecerIzquierda"></div>

            </div>

            <div class="col-4 text-center">
              <router-link class="noLink enrutadorMen" :to="'/user/'+this.user.nickname+'/pics'">
                Fotos
              </router-link>
              <div v-if="this.$route.name == 'pics'"  class="lineaSeparadora aparecerDerecha"></div>
            </div>

            <div class="col-4 text-center">
              <router-link class="noLink enrutadorMen" :to="'/user/'+this.user.nickname+'/videos'">
                Vídeos
              </router-link>
              <div v-if="this.$route.name == 'videos'"  class="lineaSeparadora aparecerDerecha"></div>
            </div>
        </div>

        <div v-if="this.suscribing" class="contienePantallaCompletaDark aparecer">
          <div class="container text-center contieneCargador">
            <div class="spinner-border cargador cargaBlanco" style="width: 3rem; height: 3rem;" role="status">
              <span class="sr-only">Loading...</span>
            </div>
            <!-- <h3>Suscribiendo</h3> -->
          </div>
        </div>
        <div class="row">
        <div class="container contenedor">
          <div v-if="!this.loading" class="row justify-content-center aparecer">


            <div v-if="this.user.influencer && this.user.canSee == false" class="col-md-12 down-2">
              <stripe-add-visa :redirect="'/profile/cards'" v-if="this.hasToPay" class="aparecer sombreado"></stripe-add-visa>
              <div class="ContieneSuscripcionesButton" v-if="this.hasToPay !== true">
                <b-dropdown v-if="this.user.influencer && this.user.plans.length !==0 && this.user.id!==this.auth.id" size="lg" id="dropdown-1" :text="$ml.get('auth').suscriptions" class="col-md-12 text-right">
                  <b-dropdown-item @click="seleccionar('month1')">
                    <span v-if="this.priceOfer('month1') !== null " class="tachado grisaceo">{{this.priceOfer('month1')}}€ </span>
                    {{this.priceOf('month1')}}€ - 30 {{$ml.get('stripe').days}}
                  </b-dropdown-item>
                  <b-dropdown-item @click="seleccionar('month3')">
                    <span v-if="this.priceOfer('month3') !== null " class="tachado grisaceo">{{this.priceOfer('month3')}}€ </span>
                    {{this.priceOf('month3')}}€ - 3 {{$ml.get('stripe').months}}
                  </b-dropdown-item>
                  <b-dropdown-item @click="seleccionar('month6')">
                    <span v-if="this.priceOfer('month6') !== null " class="tachado grisaceo">{{this.priceOfer('month6')}}€ </span>
                    {{this.priceOf('month6')}}€ - 6 {{$ml.get('stripe').months}}
                  </b-dropdown-item>
                  <b-dropdown-item @click="seleccionar('month12')">
                    <span v-if="this.priceOfer('month12') !== null " class="tachado grisaceo">{{this.priceOfer('month12')}}€ </span>
                    {{this.priceOf('month12')}}€ - 12 {{$ml.get('stripe').months}}
                  </b-dropdown-item>
                </b-dropdown>
                <b-dropdown v-if="this.user.influencer && this.user.id==this.auth.id" size="lg" id="dropdown-1" :text="$ml.get('auth').suscriptions" class="m-md-2">
                  <b-dropdown-item to="/profile/suscriptions">{{$ml.get('auth').confSusciptions}}</b-dropdown-item>
                </b-dropdown>
              </div>

            </div>


            <div v-else class="col-md-12 down-2">
              <div class="ContieneSuscripcionesButton">
                <b-dropdown v-if="this.user.influencer && this.user.id==this.auth.id" size="lg" id="dropdown-1" :text="$ml.get('auth').suscriptions" class="m-md-2">
                  <b-dropdown-item to="/profile/suscriptions">{{$ml.get('auth').confSusciptions}}</b-dropdown-item>
                </b-dropdown>

              </div>
            </div>

            <!-- Si hay subasta activa -->
            <AuctionCurrent v-if="this.user.current_auctions.length > 0 && this.user.canSee" :user="this.user"></AuctionCurrent>

            </div>

        </div>


        </div>


        <div class="row justify-content-center aparecer">
          <div v-if="auth == false" class="col-md-6 ">
             <router-link to="/login">
                <button  class="btn btn-primary boton">
                    {{$ml.get('auth').changelogin}}
                    <!-- <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> -->
                </button>
            </router-link>
          </div>
        </div>

        <div  v-if="!this.loading && this.auth !==false" class="row justify-content-center aparecer ">
          <router-view :wall="this.user.id"></router-view>
        </div>

      </div>
</template>

<script>



export default {
  data() {
    return {
      user:null,
      hasToPay:null,
      loading:true,
      suscribing:false,
      auth:this.$store.state.auth,
      currentUrl: window.location.href

    }
  },
  created() {
    this.getUser();
    console.log(window.name);
  },
  methods: {
    getUser() {
      var self = this
      var publi = "";
      if(this.auth == false) {
        publi = "/public"
      }
      axios.post('/api/user/'+this.$route.params.nickname+publi, null,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
      // then
      .then(function (response)  {
        console.log(response)
        if(response.data.rc == 1) {
          self.user = response.data.data.user
          // console.log(self.user)
        }
        else if(response.data.rc == 13) {
          self.$router.push('/login')
        }
        else {
          alert('error desconocido')
        }
      })
      .catch(err => {
        self.error = true;
        console.log(err)
      })
      // finally
      .finally(() => {
        self.loading = false

      })
    },
    seleccionar(name) {
      if(this.suscribing) {
        return true;
      }
      this.suscribing = true;
      var id = this.IdOf(name)
      if(this.auth.card_last_four == null) {
        alert(this.$ml.get('stripe').addVisa)
        this.hasToPay = true;
        this.suscribing = false;
        return true;
      }
      // hacemos la llamada
      var self = this
      axios.post('/api/suscribe/'+id, null,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
      // then
      .then(function (response)  {
        console.log(response)
        if(response.data.rc == 1) {
          alert(self.$ml.get('stripe').successSuscription+self.user.name)
          window.location.reload();
        }
        else if(response.data.rc == 13) {
          self.$router.push('/login')
        }
        else if(response.data.rc == 207) {
          alert(self.$ml.get('stripe').problem)
        }
        else {
          alert('error')
        }
      })
      .catch(err => {
        self.error = true;
        console.log(err)
      })
      // finally
      .finally(() => {
        self.suscribing = false


      })



    },
    priceOf(name) {
      for (var i = 0; i < this.user.plans.length; i++) {
        if(this.user.plans[i].payForEvery == name) {
          return this.user.plans[i].price
        }

      }
    },
    IdOf(name) {
      for (var i = 0; i < this.user.plans.length; i++) {
        if(this.user.plans[i].payForEvery == name) {
          return this.user.plans[i].id
        }

      }
    },
    priceOfer(name) {
      for (var i = 0; i < this.user.plans.length; i++) {
        if(this.user.plans[i].payForEvery == name) {
          return this.user.plans[i].oldPrice
        }

      }
    },
  }
};
</script>
