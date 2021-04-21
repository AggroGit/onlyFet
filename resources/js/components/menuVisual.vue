<template>
  <div class="contieneMenuVisual">
    <div class="menuVisual">
      <router-link to="/">
        <b-icon  icon="house-fill" style="color:#9C9C9C" font-scale="2.5"></b-icon>
      </router-link>
      <router-link to="/novedades/suscriptions">
        <span v-if="auth.other_notis>=1" class="aparecer notiBola">{{auth.other_notis}}</span>
        <b-icon  icon="bell-fill" style="color:#9C9C9C" font-scale="2.5"></b-icon>
      </router-link>
      <router-link v-if="auth.wantToBeInfluencer" to="/post/create">
        <b-icon  icon="plus-square-fill" style="color:#9C9C9C" font-scale="2.5"></b-icon>
      </router-link>
      <router-link class="noLink" to="/chats">
        <span v-if="auth.chat_notis>=1" class="aparecer notiBola">{{auth.chat_notis}}</span>
        <b-icon  icon="chat-fill" style="color:#9C9C9C" font-scale="2.5"></b-icon>
      </router-link>
      <router-link to="/profile">
        <b-icon  icon="person-fill" style="color:#9C9C9C" font-scale="2.8"></b-icon>
      </router-link>
    </div>
  </div>
</template>

<script>



export default {
  data() {
    return {
      auth: this.$store.state.auth,
    }
  },
  mounted() {
    this.connectToChannel();
  },
  methods: {
    connectToChannel() {
      this.$store.state.authChannel
      .listen('BoubleNotifications', (e) => {
          this.auth.chat_notis = e.chat_notis
          this.auth.other_notis = e.other_notis
      })
      .here((users) => {

      })
      .joining((user) => {

      })
      .leaving((user) => {

      })

    }
  }
};
</script>
