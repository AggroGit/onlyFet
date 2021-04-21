<template>
  <div class="container-fluid contenedor">
    <!-- BUSCADOR Y -->
    <div class="row down-4">
      <div class="contieneBuscadorLogoAntes MainBuscador">
          <div class="contenedor contieneBuscadorLogo MainBuscador">
            <b-form-input :autocomplete="'off'" type="text" v-model="search" @keyup="filter()" id="input-small" class="col-xs-8" :placeholder="$ml.get('main').search"></b-form-input>
            <b-icon  font-scale="2" @click="OrderByClick()" icon="filter" aria-hidden="true" class="icon"></b-icon>
          </div>
      </div>
    </div>

    <div class="row down-3">
      <div class="col-md-12">
        <h4 v-if="this.option == 'populars'">{{$ml.get('main').popu}}</h4>
        <h4 v-if="this.option == 'news'">{{$ml.get('main').news}}</h4>
        <h4 v-if="this.option == 'mySuscriptions'">{{$ml.get('main').susc}}</h4>
        <h4 v-if="this.option == 'favs'">{{$ml.get('main').favs}}</h4>
      </div>
    </div>
    <!-- NO USERS -->
    <div v-if="!this.loading" class="col-md-12 down-3">
      <div v-if="this.profiles.length == 0 && this.searching == false" class="Empty text-center">
        <img src="/iconos/empty-tag.png" alt="">
        <h5>{{$ml.get('main').nop}}</h5>
      </div>
    </div>
    <!-- LOADER -->
    <div v-if="this.loading" class="container text-center contieneCargador">
      <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
    <!-- LISTADO -->
    <div v-if="!this.loading" class="row ContienePerfilesMain" >
      <div v-for="(user) in this.profiles" :key="user.id" class="col-6 aparecer">
        <router-link :to="'/user/'+user.nickname" class="PerfilMain  noLink">
          <div class="PerfilMainImagen">
            <img v-if="user.image == null" src="/default.png" alt="">
            <img v-else :src="user.image.sizes.Med" alt="">
          </div>
          <p class="NombreMain down-1">@{{user.nickname}}</p>
        </router-link>
      </div>
    </div>

    <!-- DESPLEGABLE BUSCADOR -->
    <div v-if="this.selecting" @click="OrderByClick()" class="contienePantallaCompletaDark aparecer">
      <div class="container text-center contieneCargador aparecer">
        <div class="ContieneOpcionesMain">
          <div class="OpcionMain linkeable" @click="OrderByClick('populars')">{{$ml.get('main').popu}}</div>
          <div class="OpcionMain linkeable" @click="OrderByClick('news')">{{$ml.get('main').news}}</div>
          <div class="OpcionMain linkeable" @click="OrderByClick('mySuscriptions')">{{$ml.get('main').susc}}</div>
          <div class="OpcionMain linkeable" @click="OrderByClick('favs')">{{$ml.get('main').favs}}</div>
        </div>
        <!-- <h3>Suscribiendo</h3> -->
      </div>
    </div>


  </div>
</template>

<script>



export default {
  data() {
    return {
      selecting: false,
      loading: true,
      profiles: [],
      in: [],
      option : "populars",
      total:2,
      current:1,
      scrolling:false,
      search: null,
      searching:false,
      auth: this.$store.state.auth,

    }
  },
  created() {
    this.getProfiles(true);

  },
  methods: {
    getProfiles(force) {

      // if(this.searching) {
      //   return true;
      // }
      this.searching = true;
      var self = this
      this.loading = true;
      // if(force) {
      this.profiles = []
      this.search = ""
      //   this.total = 1,
      //   this.current = 1,
      //   this.scrolling = false;
      // }
        this.searching = true
        var url = "/api/main/users"
        if(this.auth == false) {
          url = "/api/main/nologged/users"
        }
        axios.post(url, {
          orderBy: self.option,
          search: self.search
        },
         {
           headers:{
              Authorization: `Bearer `+ this.$store.state.token
           }
         })
         .then(response => {
           console.log(response)
           if(response.data.rc == 1) {
             this.profiles = this.in = response.data.data.data
           // response.data.data.data.forEach(element => this.profiles.push(element));
             this.total = response.data.data.last_page
           }
           if(response.data.rc == 13) {
             // response.data.data.data.forEach(element => this.profiles.push(element));
             // this.total = response.data.data.last_page
             this.$router.push('/login')
           }

         })
         .catch(response => {
           alert('Vaya, parece que hay un error con el servidor')
         })
         .finally(response => {
            self.loading = false
            self.searching = false
         })
         this.loading = false;

     },
     OrderByClick(select) {
       if(select !== undefined) {
         this.option = select
         this.current = 0;
         this.total = 2
         this.profiles = [];
         this.getProfiles(true);

         return true;
       }
        this.selecting = !this.selecting
     },
     scroll () {
     window.onscroll = () => {
       let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight
       if (bottomOfWindow) {
        this.scrolledToBottom = true // replace it with your code
        if(this.scrolling== false)
        {
           this.getProfiles()
           alert('lamo')
        }

       }
     }
   },
   filter() {
     this.profiles = this.in
     this.profiles = this.profiles.filter(profile => {
       return profile.nickname.toLowerCase().includes(this.search.toLowerCase())
     })
   }
  }
};
</script>
