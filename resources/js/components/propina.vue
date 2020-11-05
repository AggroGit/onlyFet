<template>
  <div>
    <b-icon font-scale="1.7" @click="sendPropina()" style="color:#383d41;" icon="credit-card" aria-hidden="true" class="icon"></b-icon>
    <div  v-if="this.sending" class="contienePantallaCompletaDark noFlex aparecer">
      <stripe-add-visa v-if="this.haveToAdd" class="aparecer sombreado down-5 maxVisa"></stripe-add-visa>
      <b-icon v-if="this.sending" font-scale="3" @click="onClickOutside()" style="color:#ffff;" icon="x" aria-hidden="true" class="aparecer icon Cerrar"></b-icon>

      <div v-if="this.haveToAdd == false" class="ContienePropinaSend">
        <form  @submit.stop.prevent="send()" >
          <div class="form-group row">
            <entrada v-model="form.quantity" :label="$ml.get('propina').label+' €'" :name="'price1'" :autocomplete="'none'" :inputmode="'numeric'" :type="'number'" :min='1' :max="'400'" :step="'1'" :autofocus="true" :required="true"></entrada>
          </div>
          <div class="form-group row ">
            <div class="col-md-12 contieneInput">
                <label for="post" class="entrada detextarea" >{{$ml.get('propina').mensaje}}</label>
                <textarea ref="content" v-model="form.description" rows="5"  name="post" class="form-control" autocomplete="off" autofocus="true"></textarea>
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
        message: ""
      }
    }
  },
  mounted() {
    console.log(window.name);
  },
  methods: {
    sendPropina() {
      if(this.auth.card_last_four == null || this.auth.card_brand == null) {

        alert(this.$ml.get('stripe').addVisa)
        this.haveToAdd = true
      }
      this.sending = true;


    },
    onClickOutside(event) {
      this.sending = false;
    },
    send() {
      if(this.loading) {
        return true
      }
      this.loading = true
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
