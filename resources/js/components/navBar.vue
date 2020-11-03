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
        <div class="MenuMob">
          <div class="ContienePerfilNav down-3">
            <div class="ContieneOpcionCerrar">
              <b-icon font-scale="1.7" style="color:black;" icon="x" aria-hidden="true" class="icon"></b-icon>
            </div>
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
          </div>
          <div class="ContieneElementosMenu">
            <b-nav-item to='/'>Home</b-nav-item>
            <b-nav-item to='/novedades'>Novedades</b-nav-item>
            <b-nav-item to='/post/create'>Publicar</b-nav-item>
            <b-nav-item to='/chats'>Chats</b-nav-item>
          </div>
        </div>


        <div class="contieneLogoMenu">
          <img src="/logo-mini.png" alt="logo">
        </div>

      </nav>
  </div>

</template>

<script>



export default {

  data() {
    return {
      name: 'OnlyFet',
      auth:  this.$store.state.auth
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
      window.user = false;
      Echo.disconnect();
      Echo = false;
      window.authChannel = false;
      this.$router.push({ path: '/login' })

    }
  }

};
</script>
