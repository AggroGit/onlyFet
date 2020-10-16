<template>

    <div>
      <b-navbar toggleable="lg" style="background:#ffffff" type="light" variant="light" class="sombreado navbar">


        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-navbar-brand type="light" href="#">
          <img src="/logo-mini.png" alt="">
        </b-navbar-brand>
          <b-navbar-brand type="light" href="#">{{this.name}}</b-navbar-brand>

        <b-collapse id="nav-collapse" is-nav>
          <!-- <b-navbar-nav>
            <b-nav-item to='/home'>Home</b-nav-item>
            <b-nav-item to='/chats'>Chats</b-nav-item>
          </b-navbar-nav> -->

          <!-- Right aligned nav items NO LOGGED-->
          <!-- <b-navbar-nav v-if="this.$store.state.auth == false" left>
            <b-nav-item-dropdown  :text="'Opciones'" left>
              <b-dropdown-item to="/login">Login</b-dropdown-item>
              <b-dropdown-item to="/register">register</b-dropdown-item>
            </b-nav-item-dropdown>
          </b-navbar-nav> -->
          <!-- Right aligned nav items LOGGED-->
          <!-- <b-navbar-nav v-else left>
            <b-nav-item-dropdown  :text="this.$store.state.auth.name" left>
              <b-dropdown-item to="/profile">{{this.$store.state.auth.name}}</b-dropdown-item>
              <b-dropdown-item @click="logout()" >Sign Out</b-dropdown-item>
            </b-nav-item-dropdown>
          </b-navbar-nav> -->
        </b-collapse>
      </b-navbar>
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
