<template>
    <div>
      <!-- LOADER -->
      <div v-if="this.loading" class="container text-center contieneCargador">
        <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>

      <!-- NAVBAR -->
      <!-- <navBar v-if="!this.loading" :auth="this.auth"></navBar> -->
      <!-- THE VIEW -->
      <div v-if="!this.loading" class="contenedor">
        <router-view   :key="$route.fullPath" v-bind:data="this"></router-view>
      </div>
      <!-- NOTIFICATIONS -->
      <notifications v-if="!this.loading && this.$store.state.auth"></notifications>


    </div>
</template>



<script>

export default {
  // los valores que entran de fuera
  props:['app_code'],
  // los que inicializamos nosotros
  data() {
    return {
      loading:true,
      auth:false,
      d:false
    }
  },
  mounted() {
     this.initApp();
     window.appCode = this.app_code;


  },
  methods:{
    initApp() {
      var token;
      // miramos si hay token en cookie
      if(token = this.checkToken()) {
        // verificamos
        this.validateToken(token);
        return true;
      } else {
        // sino login o dejamos pasar sin logear
        this.$store.state.auth = false;
        this.loading = false;
      }



    },
    // comprueba si existe token
    checkToken() {
      var token = Vue.$cookies.get('token');
      if(token == null || token == undefined) {
        return false;
      }
      return token;
    },
    // add the user info
    addUser: (data,self) => {
      // add the user info
      self.$store.state.auth = data;
      // init conection
      self.$store.state.initConection(self.$store.state.token);
      // global
      window.user = data;
      // start the user connection
      // self.$store.state.authChannelConnect();
      self.$store.state.authChannel = window.Echo.join(appCode+'.User.'+user.id);//+self.$store.state.auth.id);
      self.$store.state.appchannel = window.Echo.join(appCode+'.App');//+self.$store.state.auth.id);
      // IDIOMA DE LA APP
      self.$ml.change(user.lang)

    },
    // validate the token with server
    validateToken :function(token){
      var self = this
      axios.post('/api/auth', null, {
        headers:{
           Authorization: `Bearer `+ token
        }
      })
      .then(response => {
        if(response.data.rc == 1) {
          self.addUser(response.data.data,self)
        } else {
          return false;
        }
      })
      .finally(function (response) {
        self.loading = false;
      })
    },








  }
};
</script>
