<template>
  <div class="contieneMenuVisual">
    <div class="menuVisual">
      <router-link to="/">
        <b-icon  icon="house-fill" style="color:#9C9C9C" font-scale="2.5"></b-icon>
      </router-link>
      <router-link to="/novedades/suscriptions">
        <b-icon  icon="bell-fill" style="color:#9C9C9C" font-scale="2.5"></b-icon>
      </router-link>
      <router-link v-if="auth.wantToBeInfluencer" to="/post/create">
        <b-icon  icon="plus-square-fill" style="color:#9C9C9C" font-scale="2.5"></b-icon>
      </router-link>
      <router-link to="/chats">
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
    console.log(window.name);
  }
};
</script>
