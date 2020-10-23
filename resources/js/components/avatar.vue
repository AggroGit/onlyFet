<template>
  <router-link :to="this.route()" class="">
    <b-avatar class="mr-3" :src="this.image()"></b-avatar>
    <div v-if="this.conection && !this.connected" class="conection-dot"></div>
    <div v-if="this.conection && this.connected" class="conection-dot connected"></div>
  </router-link>

</template>

<script>



export default {
  props:{
    us:{
      default:false
    },
    conection :{
      default:false
    }
  },
  data() {
    return {
      connected:false
    }

  },
  mounted() {
this.initConnect();

  },
  created() {

  }
  ,
  methods: {
    image() {
      if(this.us.image) {

        return this.us.image.sizes.VerySmall
      } else {
        return ""
      }
    },
    initConnect() {

      if(this.conection) {
        // eventos al entrar o salir
        this.$store.state.appchannel
        .here((users) => {
          console.log('estan')
          console.log(users)
          users.forEach((user, i) => {
            if(user.id == this.us.id) {
              this.connected = true;
            }
          })
        })
        .leaving((user) => {
          if(user.id == this.us.id) {
            this.connected = false;
          }

        })
        .joining((user) => {
          if(user.id == this.us.id) {
            this.connected = true;
          }
        })
        // comprobamos si dentro de éste canal esta el usuario
        var members =   this.$store.state.appchannel.subscription.members;
        this.connected = this.$store.state.isUserInChannel(this.us,this.$store.state.appchannel)

      }


    },
    route() {
      if(this.us !== false) {
        return "/user/"+this.us.nickname
      }
    }

  }
}

</script>
