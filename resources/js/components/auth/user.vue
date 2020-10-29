<template>
      <div class="container-fluid">
        <div v-if="this.loading" class="container text-center contieneCargador">
          <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
                    </div>
        </div>



        <!-- CACEBZERA FOTO  -->
        <div v-if="!this.loading" class="row justify-content-center down-2">
          <div class="col-6 text-center">
            <div class="ProfileImg">
              <img v-if="this.user.image" :src="this.user.image.sizes.NotSmall" alt="">
              <img v-else src="/default.png" alt="">
            </div>
          </div>
          <div class="col-6 ContieneDatosperfil">
            <h4>{{user.name}}</h4>
            <br>
            <p><strong>@</strong>{{this.user.nickname}}
              <br>
              {{this.user.description}}
            </p>

            <!-- <p><strong>{{$ml.get('auth').phone}}: </strong>{{user.phone_number}}</p>
            <p><strong>{{$ml.get('auth').email}}: </strong> {{user.email}}</p>
            <p><strong>{{$ml.get('auth').name}}: </strong> {{user.name}}</p> -->
          </div>
        </div>






        <div v-if="!this.loading" class="row justify-content-center contieneEnrutador sombreadoInferior">

            <div class="">
              <router-link class="noLink" :to="'/user/'+this.user.nickname+'/wall'">
                Muro
              </router-link>
              <div v-if="this.$route.name == 'wall'"  class="lineaSeparadora aparecerIzquierda"></div>

            </div>

            <div class="">
              <router-link class="noLink" :to="'/user/'+this.user.nickname+'/pics'">
                Fotos
              </router-link>
              <div v-if="this.$route.name == 'pics'"  class="lineaSeparadora aparecerDerecha"></div>
            </div>

            <div class="">
              <router-link class="noLink" :to="'/user/'+this.user.nickname+'/videos'">
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
        {{this.user.canSee}}

        <div v-if="!this.loading" class="row">
          <div v-if="this.user.influencer && this.user.canSee==false" class="col-md-12 down-2">
            <stripe-add-visa :redirect="'/profile/cards'" v-if="this.hasToPay" class="aparecer sombreado"></stripe-add-visa>
            <div class="ContieneSuscripcionesButton">

              <b-dropdown v-if="this.user.influencer && this.user.plans && this.user.id !== this.auth.id && this.user.canSee == false" size="lg" id="dropdown-1" :text="$ml.get('auth').suscriptions" class="m-md-2 text-right">
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

        </div>


        <div v-if="!this.loading" class="row aparecer">
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

    }
  },
  created() {
    this.getUser();
    console.log(window.name);
  },
  methods: {
    getUser() {
      var self = this
      axios.post('/api/user/'+this.$route.params.nickname, null,
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
