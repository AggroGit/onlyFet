<template>
  <div unselectable="on" class="conntieneLogoPropina unselectable linkeable text-left">
    <div  @click="sendPropina()" class="left iconPost euro">€</div>
    <div  v-if="this.sending" class="contienePantallaCompletaDark noFlex aparecer">
      <stripe-add-visa v-if="this.haveToAdd" class="aparecer sombreado down-5 maxVisa"></stripe-add-visa>
      <b-icon v-if="this.sending" font-scale="3" @click="onClickOutside()" style="color:#ffff;" icon="x" aria-hidden="true" class="aparecer icon Cerrar"></b-icon>

      <div v-if="this.haveToAdd == false" class="ContienePropinaSend">
        <h5 class="text-left">{{this.$ml.get('propina').title}}</h5>
        <form class=""  @submit.stop.prevent="send()" >
          <div class="form-group row flex contieneBotonesPropina">
            <button type="button" class="btn sombreado" @click="updateCant(5)"  v-bind:class="{ seleccionado: form.quantity == 5 }" name="button">5€</button>
            <button type="button" class="btn sombreado" @click="updateCant(10)" v-bind:class="{ seleccionado: form.quantity == 10 }" name="button">10€</button>
            <button type="button" class="btn sombreado" @click="updateCant(20)" v-bind:class="{ seleccionado: form.quantity ==20 }" name="button">20€</button>
            <button type="button" class="btn sombreado" @click="updateCant(50)" v-bind:class="{ seleccionado: form.quantity == 50 }" name="button">50€</button>

          </div>
          <div class="form-group row">
            <entrada v-model="form.quantity" :label="$ml.get('propina').label+' €'" :name="'price1'" :autocomplete="'none'" :inputmode="'numeric'" :type="'number'" :min='1' :max="'400'" :step="'1'" :autofocus="true" :required="true"></entrada>
          </div>
          <div class="form-group row parriba">
            <div class="col-md-12 contieneInput">
                <label for="post" class="entrada detextarea" >{{$ml.get('propina').mensaje}}</label>
                <textarea ref="content" v-model="form.message" rows="5"  name="post" class="form-control" autocomplete="off" autofocus="true"></textarea>
            </div>
          </div>
          <div class="form-group row down-2">
              <div class="col-md-12 offset-md-12">
                  <button type="submit" class="btn btn-primary boton">
                      {{$ml.get('propina').send}}
                      <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  </button>
              </div>
          </div>
        </form>
      </div>
      <div v-if="this.loading" class="container text-center contieneCargador">
        <div class="spinner-border cargador cargaBlanco" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>



export default {
  props: {
      chat_id: {
        default:false
      },
      otherUser: {
        default: false
      },
      type: {
        default: "propina"
      }
  },
  data() {
    return {
      auth: this.$store.state.auth,
      sending: false,
      loading:false,
      haveToAdd: false,
      form: {
        quantity: null,
        message: null,
        type: null
      }
    }
  },
  mounted() {
    console.log(window.name);
  },
  methods: {
    updateCant(cant) {
      this.form.quantity = cant
    },
    sendPropina() {
      if(this.auth.card_last_four == null || this.auth.card_brand == null) {

        alert(this.$ml.get('stripe').addVisa)
        this.haveToAdd = true
      }
      this.sending = true;

    },
    onClickOutside(event) {
      this.form.quantity = null
      this.form.message = null
      this.sending = false;

    },
    send() {
      if(this.loading) {
        return true
      }
      this.loading = true
      this.form.type = this.type
      var self = this
      axios.post('/api/propina/'+self.otherUser.id, self.form, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(response => {
        console.log(response)
        if(response.data.rc == 1) {
          self.sending = false
          return true;
        }
        if(response.data.rc == 210) {
          alert(self.$ml.get('propina').cantRecive)
          return true;
        }

      })
      .catch(err => {
        alert('Error')
      })
      .finally(response => {
        self.loading = false
        // self.sending = false
      })
    }
  }
};
</script>
