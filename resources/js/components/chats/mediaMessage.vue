<template>
    <div v-if="message.images.length !== 0 || message.videos.length !==0" class="container">
      <!-- Mensaje gratis -->
      <div v-if="!message.forPay || message.user.id == $store.state.auth.id" class="row">
        <!-- Videos -->
        <div v-if="message.videos.length == 1" class="col-12 mt-1">
          <vue-core-video-player :key="message.videos[0].id" :src="message.videos[0].Path" poster="" ></vue-core-video-player>
        </div>
        <div v-else v-for="(video) in message.videos" :key="video.id" class="col-12 mt-1">
          <vue-core-video-player  :src="video.Path" :key="video.id" poster=""></vue-core-video-player>
        </div>
        <br>
        <!-- Imagenes -->
        <div v-for="(image) in message.images" :key="image.id" :class="getClassImage()+' mt-1'">
          <router-link  :to="message.chat_id+'/'+image.name" class="imageChat">
            <img class="img-fluid" :src="image.sizes.NotSmall" alt="">
          </router-link>
        </div>
      </div>






      <div v-else class="row" >
        <!-- Mensaje de pago -->
        <!-- Videos -->
        <div v-if="message.videos.length >= 1" class="col-12 mt-1">
          <img class="imageChat img-fluid" src="/iconos/video.png" alt="">
        </div>

        <br>
        <!-- Imagenes -->
        <div v-for="(image) in message.images" :key="image.id" :class="getClassImage()+' mt-1'">
            <img class="imageChatHidded" :src="image.sizes.Hidden" alt="">
        </div>
      </div>
      <!-- DESBLOQUEADOR -->
      <div v-if="message.forPay" class="row">
        <button v-if="auth.id !== message.user.id" type="button" class="btn btn-primary boton" @click="unlockImage()" name="button">
          <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          🔒 {{this.$ml.get('chat').unlockMessage}} {{message.price}} €
        </button>
      </div>
      <p class="mt-2">{{message.message}}</p>
      <stripe-add-visa v-if="hasToAddVisa"/>

    </div>
</template>

<script>



export default {
  props:['message'],
  data() {
    return {
      auth: this.$store.state.auth,
      loading:false,
      hasToAddVisa:false
    }
  },
  created() {

  },
  methods: {
    getClassImage() {
      if(this.message.images.length>1) {
        return "col-6"
      } else {
        return "col-12"
      }
    },
    unlockImage() {
      if(this.loading)
        return true

      var self = this;
      this.loading=true
      axios.post('/api/chat/'+this.message.chat_id+'/message/'+this.message.id+'/unlock', null, {
        headers:{
           Authorization: `Bearer `+ self.$store.state.token
        }
      })
      // then
      .then(function (response) {
        if(response.data.rc == 1) {
          self.message.forPay = false
          self.message.message = "📸 🗝 🔓"
        }
        else if(response.data.rc == 200) {
          self.hasToAddVisa = true
        }
        else {
          alert('error')
        }

      })
      .catch(err => {
        alert('Server Error')
        console.log('error->')
        console.log(err)
      })
      // finally
      .finally(() => this.loading = false)
    }
}
};
</script>
