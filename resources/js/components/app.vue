<template>
    <div>
      <!-- LOADER -->
      <div v-if="this.loading" class="container text-center contieneCargador">
        <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>

      <!-- NAVBAR -->
      <navBar v-if="!this.loading"  class="navbarsUP sombreado" :auth="this.auth"></navBar>
      <!-- THE NOTIS OF THE STATE OF VALIDATION -->
      <validationNotis v-if="!this.loading && this.$store.state.auth"/>
      <!-- THE VIEW -->
      <div v-if="!this.loading" >
        <router-view v-bind:class="{ 'entero': this.$store.state.entero }" :key="$route.params.nickname"  v-bind:data="this"></router-view>
      </div>
      <!-- THE VIEW -->
      <!-- <div v-if="!this.loading" v-bind:class="{ 'entero': this.$store.state.entero }">
        <router-view v-bind:data="this"></router-view>
      </div> -->
      <!-- NOTIFICATIONS -->
      <notifications v-if="!this.loading && this.$store.state.auth"></notifications>
      <menuVisual v-if="!this.loading"/>

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
      // idioma de la app

      this.initLang();


    },
    // comprueba si existe token
    checkToken() {
      var token = Vue.$cookies.get('token');
      if(token == null || token == undefined) {
        return false;
      }
      return token;
    },
    entero() {
      if(this.$store.state.entero) {
        return "contenedor"
      }
    },
    // a no haber un usuario logeado o registrado deberá coger un dioma predeterminado
    initLang() {
      // el primero de todos es el idioma del navagdor
      var userLang = navigator.language || navigator.userLanguage;
      userLang = userLang.toLowerCase()
      if(userLang == "es" || userLang == "ES" || userLang == "es-es") {
        userLang = "es"
      } else {
        userLang = "en"
      }
      this.$ml.change(userLang)
      console.log('LANGUAGE -> '+userLang)
      //
    },
    // add the user info
    addUser: (data,self) => {
      // add the user info
      self.$store.state.auth = data;
      // the num of products
      self.$store.state.numProducts = data.numProducts;
      // init conection
      self.$store.state.initConection(self.$store.state.token);
      // global
      window.user = data;
      // start the user connection
      // self.$store.state.authChannelConnect();
      self.$store.state.authChannel = window.Echo.join(appCode+'.User.'+user.id);//+self.$store.state.auth.id);
      self.$store.state.appchannel = window.Echo.join(appCode+'.App');//+self.$store.state.auth.id);
      // IDIOMA DE LA APP
      self.$ml.change(data.lang)

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
