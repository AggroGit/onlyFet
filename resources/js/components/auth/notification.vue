<template>
  <router-link :to="Url()">
    <div v-show="elementVisible"  class="alert alert-warning alert-dismissible fade show notification notificationn sombreado" role="alert">
      <strong>{{data.title}}</strong> {{data.body}}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </router-link>
</template>

<script>



export default {
  props: ['data'],
  data() {
    return {
      elementVisible:true
    }
  },
  mounted() {
  },
  created() {
    setTimeout(() => this.elementVisible = false, 3500)
  },
  methods: {
    Url() {
      if(this.data.type == "chat") {
        return "/chats/"+this.data.data
      }
      if(this.data.type == "publication") {
        return "/post/"+this.data.data
      }
      if(this.data.type == "like") {
        return "/post/"+this.data.data
      }
      if(this.data.type == "comment") {
        return "/post/"+this.data.data
      }
      if(this.data.type == "propina") {
        return "/profile/propina"
      }
      if(this.data.type == "suscription") {
        return "/user/"+this.data.data
      }
      if(this.data.type == "auction") {
        return "/auction/"+this.data.data
      }
      else {
        return "/novedades/suscriptions"
      }
    }
  }
};
</script>
