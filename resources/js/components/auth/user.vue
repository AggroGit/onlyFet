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
            <p><strong>@</strong>{{user.nickname}}
              <br>
              La suya no fue la descripción del camino a la tierra prometida, sino una visión antiutópica de dimensiones casi orwellianas.
            </p>

            <!-- <p><strong>{{$ml.get('auth').phone}}: </strong>{{user.phone_number}}</p>
            <p><strong>{{$ml.get('auth').email}}: </strong> {{user.email}}</p>
            <p><strong>{{$ml.get('auth').name}}: </strong> {{user.name}}</p> -->
          </div>
        </div>






        <div class="row justify-content-center contieneEnrutador sombreadoInferior">

            <div class="">
              <router-link class="noLink" :to="'/user/'+user.nickname+'/wall'">
                Muro
              </router-link>
              <div v-if="this.$route.name == 'wall'"  class="lineaSeparadora aparecerIzquierda"></div>

            </div>

            <div class="">
              <router-link class="noLink" :to="'/user/'+user.nickname+'/pics'">
                Fotos
              </router-link>
              <div v-if="this.$route.name == 'pics'"  class="lineaSeparadora aparecerDerecha"></div>
            </div>

            <div class="">
              <router-link class="noLink" :to="'/user/'+user.nickname+'/videos'">
                Vídeos
              </router-link>
              <div v-if="this.$route.name == 'videos'"  class="lineaSeparadora aparecerDerecha"></div>
            </div>
        </div>



        <div class="row">
          <div class="col-md-12 down-2">
            <div class="ContieneSuscripcionesButton">
              <b-dropdown size="lg" id="dropdown-1" text="Suscripciones" class="m-md-2">
                <b-dropdown-item to="/profile/cards">8€ - 30 días</b-dropdown-item>
                <b-dropdown-item to="/profile/cards">20€ - 3 meses</b-dropdown-item>
                <b-dropdown-item to="/profile/cards">30€ - 6 meses</b-dropdown-item>
              </b-dropdown>
            </div>
          </div>

        </div>


        <div class="row aparecer">

          <router-view :wall="user.id"></router-view>
        </div>

      </div>
</template>

<script>



export default {
  data() {
    return {
      user:null,
      loading:true
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
    }
  }
};
</script>
