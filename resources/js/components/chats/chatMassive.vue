<template>
    <div class="container contenedor">
      <div class="row">
        <div v-if="this.loading" class="container text-center contieneCargador aparecer">
          <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
          </div>
          <h3 class="down-4">{{$ml.get('chat').sending}}</h3>
        </div>
        <div v-if="!this.loading" class="col-md-12 mt-5">
          <h1>Enviar mensaje masivo</h1>
          <div v-if="this.success" class="col-md-12 mt-3">
            <div class="alert alert-success">
              <strong>{{this.$ml.get('chat').success}}</strong>: {{this.newMessage}}
            </div>
          </div>
        </div>
      </div>
      <div v-if="!this.success" class="inputFixed">
        <div v-if="!this.loading" class=" col-md-12 ContieneEntradaChat">
          <b-form-input  autofocus v-model="newMessage" @keyup.enter="sendMessage()" autocomplete="off" id="input-small" class="col-xs-8 entradaTextoChat ContieneEntradaChatInput" :placeholder="$ml.get('chat').entrada"></b-form-input>
          <div class="ContieneIconosEnviar">
            <!-- <b-icon font-scale="1.7" style="color:#383d41;" icon="credit-card" aria-hidden="true" class="icon"></b-icon> -->
            <sendPics :massive="true"/>
            <!-- <b-icon font-scale="1.7" @click="$refs.file.click()" style="color:#383d41;" icon="camera-fill" aria-hidden="true" class="icon"></b-icon> -->
            <b-icon @click="sendMessage()" font-scale="1.7" style="color: #F20505;" icon="arrow-up-right-circle-fill" aria-hidden="true" class="icon iconshadow"></b-icon>
          </div>
        </div>
      </div>
    </div>
</template>

<script>



export default {
  data() {
    return {
      auth: this.$store.state.auth,
      loading:false,
      newMessage:"",
      success:false
    }
  },
  methods: {

    sendMessage() {
      this.loading = true;
      var self = this;
      if(this.newMessage !== null) {
        axios.post('/api/chat/massive/on', {
          message: this.newMessage
        },
        {
          headers:{
             Authorization: `Bearer `+ this.$store.state.token
          }
        })
        .finally(() => {
          self.loading = false
          self.success=true
        })

      }

    },

  }

};
</script>
