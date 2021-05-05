<template>

    <div class="container down-2 aparecer">
      <!-- CACEBZERA FOTO  -->
      <div class="row justify-content-center ">
        <div class="col-xs-6">
          <div class="ProfileImg down-2">
            <img v-if="this.auth.image" :src="this.auth.image.sizes.NotSmall" alt="">
            <img v-else src="/default.png" alt="">
          </div>
        </div>
        <div class="col-xs-6 ContieneDatosperfil">
          <h4> <router-link class="noLink" :to="'/user/'+auth.nickname">{{auth.name}}</router-link> </h4>
          <br>
          <p><strong>{{$ml.get('auth').nickname}}: </strong>{{auth.nickname}}</p>
          <!-- <p><strong>{{$ml.get('auth').phone}}: </strong>{{auth.phone_number}}</p> -->
          <p><strong>{{$ml.get('auth').email}}: </strong> {{auth.email}}</p>
          <p><strong>{{$ml.get('auth').name}}: </strong> {{auth.name}}</p>

        </div>

      </div>

      <div class="row justify-content-center down-2">
          <div class="col-md-6">
             <p><strong>{{$ml.get('auth').description}}: </strong> <p v-html="auth.description"></p>
          </div>
      </div>



      <!-- EDITAR -->
      <div class="row justify-content-center">
          <div class="col-md-6">
             <router-link to="/profile/edit">
                <button  class="btn btn-secondary boton">
                    {{$ml.get('auth').edit}}
                </button>
            </router-link>
          </div>
      </div>
      <!-- Creditcards -->
      <div class="row justify-content-center">
          <div class="col-md-6">
             <router-link to="/profile/cards">
                <button  class="btn btn-secondary boton">
                    {{$ml.get('stripe').cards}}
                </button>
            </router-link>
          </div>
      </div>

      <div class="row justify-content-center mt-3">
        <div class="col-md-6">
          <div class="separadorRRSS"></div>
        </div>
      </div>

      <div v-if="auth.verified && auth.stripe_created == false && loading == false" class="row justify-content-center">
          <div class="col-md-6">
            <a target="_blank" :href="stripe_url">
               <button  class="btn btn-primary boton">
                   {{$ml.get('stripe').create}}
               </button>
           </a>
          </div>
      </div>



      <!-- Became a infuencer -->
      <div v-if="!auth.wantToBeInfluencer" class="row justify-content-center">
          <div class="col-md-6">
             <router-link to="/request/influencer">
                <button  class="btn btn-primary boton btn-terciario">
                    {{$ml.get('auth').becameInfluencer}}
                </button>
            </router-link>
          </div>
      </div>

      <!-- STRIPE -->
      <!-- <div v-if="!auth.influecer" class="row justify-content-center">
          <div class="col-md-6">
             <stripe-express></stripe-express>
          </div>
      </div> -->

      <!-- STRIPE -->
      <div class="row justify-content-center">
          <div v-if="auth.wantToBeInfluencer && auth.stripe_created" class="col-md-6">
             <router-link v-if="auth.wantToBeInfluencer && auth.stripe_created" to="/profile/suscriptions">
                <button  class="btn btn-primary boton">
                    {{$ml.get('auth').confSuscriptions}}
                </button>
            </router-link>

          </div>
          <div v-if="auth.wantToBeInfluencer && auth.stripe_created==false" class="col-md-6">
            <b-button   v-b-tooltip.hover.bottom="$ml.get('auth').uHaveToStripe" class="btn btn-primary boton stripe">
                  {{$ml.get('auth').confSuscriptions}}
            </b-button>
          </div>
      </div>
    </div>
</template>

<script>



export default {
  data() {
    return {
      auth: this.$store.state.auth,
      text:"create",
      stripe_url: null,
      loading:true,
    }
  },
  created() {
    console.log(window.Echo)
    this.haveToStripe()
  },
  methods: {
    haveToStripe() {
      var self = this
      axios.post('/api/stripe/url', null,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
      .then(function (response) {
        if(response.data.rc == 1) {
          self.stripe_url = response.data.data
        }
      })
      .finally(function (response) {
        self.loading = false
      })
    }
  }


};
</script>
