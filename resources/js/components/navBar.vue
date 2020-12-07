 <template>

    <div>

      <!-- <b-navbar toggleable="lg"  type="light" class="sombreado navbar blanco" toggle-class="blanco">


        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-navbar-brand to="/" type="light" class="center" href="#">
          <img src="/logo-mini.png" alt="">
        </b-navbar-brand>

        <b-collapse id="nav-collapse" is-nav>
          <b-navbar-nav>
            <b-nav-item to='/'>Home</b-nav-item>
            <b-nav-item to='/novedades'>Novedades</b-nav-item>
            <b-nav-item to='/post/create'>Publicar</b-nav-item>
            <b-nav-item to='/chats'>Chats</b-nav-item>
          </b-navbar-nav>

          <b-navbar-nav v-if="this.$store.state.auth == false" left>
            <b-nav-item-dropdown  :text="'Opciones'" left>
              <b-dropdown-item to="/login">Login</b-dropdown-item>
              <b-dropdown-item to="/register">register</b-dropdown-item>
            </b-nav-item-dropdown>
          </b-navbar-nav>
          <b-navbar-nav v-else left>
            <b-nav-item-dropdown  :text="this.$store.state.auth.name" left>
              <b-dropdown-item to="/profile">{{this.$store.state.auth.name}}</b-dropdown-item>
              <b-dropdown-item @click="logout()" >Sign Out</b-dropdown-item>
            </b-nav-item-dropdown>
          </b-navbar-nav>
        </b-collapse>
      </b-navbar> -->
      <nav class="contenedor menuSuperior ">
        <!-- <div class="ContienePerfilNav">
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
              <p><strong>{{$ml.get('auth').email}}: </strong> {{auth.email}}</p>
              <p><strong>{{$ml.get('auth').name}}: </strong> {{auth.name}}</p>

            </div>
          </div>
        </div> -->
        <div class="MenuMob" v-bind:class="{ open: open }" @click="AbrirCerrar()">
          <div class="ContienePerfilNav container down-3">
            <div class="ContieneOpcionCerrar" @click="AbrirCerrar()">
              <b-icon @click="AbrirCerrar()" font-scale="2" style="color:black;" icon="x" aria-hidden="true" class="icon"></b-icon>
            </div>
            <div  v-if="$store.state.auth !== false"  class="row justify-content-center menuu">
              <div class="col-4">
                <router-link :to="'/user/'+$store.state.auth.nickname" class="ProfileImg down-2">
                  <img v-if="$store.state.auth.image" :src="$store.state.auth.image.sizes.NotSmall" alt="">
                  <img v-else src="/default.png" alt="">
                </router-link>
              </div>
              <div class="col-8 ContieneDatosperfil">
                <h4> <router-link class="noLink" :to="'/user/'+auth.nickname">{{$store.state.auth.name}}</router-link> </h4>
                <br>
                <p><strong>{{$ml.get('auth').nickname}}: </strong>{{$store.state.auth.nickname}}</p>
                <p><strong>{{$ml.get('auth').email}}: </strong> {{$store.state.auth.email}}</p>
                <p><strong>{{$ml.get('auth').name}}: </strong> {{$store.state.auth.name}}</p>
                <router-link class="rojo" to="/profile">{{$ml.get('auth').editProfile}}</router-link>
              </div>
            </div>
          </div>
          <div class="ContieneElementosMenu ">
            <b-nav-item to='/'>{{$ml.get('menu').home}}</b-nav-item>
            <b-nav-item to='/novedades'>{{$ml.get('menu').news}}</b-nav-item>
            <b-nav-item to='/post/create'>{{$ml.get('menu').publi}}</b-nav-item>
            <b-nav-item class="" to='/profile'>{{$ml.get('menu').profile}}</b-nav-item>
            <b-nav-item class="" to='/faqs'>{{$ml.get('menu').faqs}}</b-nav-item>
            <b-nav-item class="" to='/auction/create'>{{$ml.get('auction').createAuction}}</b-nav-item>
            <b-nav-item to='/chats'>{{$ml.get('menu').chats}}</b-nav-item>
            <b-nav-item v-if="this.$store.state.auth !==false" @click="logout()">{{$ml.get('menu').logout}}</b-nav-item>
            <b-nav-item v-if="this.$store.state.auth ===false"  to='/login'>{{$ml.get('menu').login}}</b-nav-item>
            <b-nav-item v-if="this.$store.state.auth ===false"  to='/register'>{{$ml.get('menu').register}}</b-nav-item>
          </div>
        </div>

        <div @click="AbrirCerrar()" class="onlyMob ContieneMenuAbrir">
          <b-icon font-scale="2" style="color:black;" icon="list" aria-hidden="true" class="icon"></b-icon>
        </div>
        <router-link to="/" class="contieneLogoMenu">
          <img class="logoMewnu" src="/logo_2611.png" alt="logo">
        </router-link>

      </nav>
  </div>

</template>

<script>



export default {

  data() {
    return {
      name: 'OnlyFet',
      auth:  this.$store.state.auth,
      open: false
    }

  },

  methods: {

    logout() {
      axios.post('/api/logout', null, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(function (response) {

        console.log(response);
      })
      this.$store.state.auth = false
      this.$store.state.quitTokenInCookie();
      this.auth = false;
      window.user = false;
      Echo.disconnect();
      Echo = false;
      window.authChannel = false;
      this.$router.push({ path: '/login' })

    },
    AbrirCerrar(){
      this.open = !this.open;
    }
  }

};
</script>
