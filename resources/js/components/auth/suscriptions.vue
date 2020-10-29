<template>
    <div class="container down-2 aparecer">
      <div class="row justify-content-center">
        <div class="col-md-12 text-center">
          <h2>{{$ml.get('auth').suscriptions}}</h2>
        </div>
      </div>


      <form  @submit.stop.prevent="edit()" >
        <div class="form-group down-2 row justify-content-center">
          <div class="col-6 ">
              <label >Perfil privado</label>
          </div>
          <div class="col-6 text-right">
            <toggle-button v-if="auth.stripe_reciver_id !== null" v-model="form.influencer"/>
            <p class="informativoGris" v-else><stripe-express></stripe-express>Para poder ser influencer debes antes configurar Stripe</p>
          </div>
          <div class="col-12">
            <p class="informativoGris">* Requerirá pagar suscripción </p>
          </div>
            <!-- <entradaText v-model="form.content" @change="detectPeople()" :rows="4" :label="$ml.get('post').post" :name="'name'" autocomplete="off" :type="'text'" :autofocus="true" :required="true"></entradaText> -->
        </div>

        <div v-if="form.influencer" class="down-3 aparecer">
            <div class="row ">
              <div class="col-8">
                <label>{{$ml.get('stripe').sus1}}</label>
              </div>
              <div class="col-4">
                <div class="form-group row">
                    <entrada v-model="form.suscriptions.month1" :label="$ml.get('auth').price" :name="'price1'" :autocomplete="'none'" :inputmode="'numeric'" :type="'number'" :min='1' :max="'400'" :step="'1'" :autofocus="true" :required="true"></entrada>
                </div>
              </div>
            </div>

            <div class="row ">
              <div class="col-8">
                <label>{{$ml.get('stripe').sus2}}</label>
              </div>
              <div class="col-4">
                <div class="form-group row">
                    <entrada v-model="form.suscriptions.month3" :label="$ml.get('auth').price" :name="'price2'" :autocomplete="'none'" :inputmode="'numeric'" :type="'number'" :min='1' :max="'400'" :step="'1'" :autofocus="true" :required="true"></entrada>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-8">
                <label>{{$ml.get('stripe').sus3}}</label>
              </div>
              <div class="col-4">
                <div class="form-group row">
                    <entrada v-model="form.suscriptions.month6" :label="$ml.get('auth').price" :name="'price3'" :autocomplete="'none'" :inputmode="'numeric'" :type="'number'" :min='1' :max="'400'" :step="'1'" :autofocus="true" :required="true"></entrada>
                </div>
              </div>
            </div>

            <div class="row ">
              <div class="col-8">
                <label>{{$ml.get('stripe').sus4}}</label>
              </div>
              <div class="col-4">
                <div class="form-group row">
                    <entrada v-model="form.suscriptions.month12" :label="$ml.get('auth').price" :name="'price4'" :autocomplete="'none'" :inputmode="'numeric'" :type="'number'" :min='1' :max="'400'" :step="'1'" :autofocus="true" :required="true"></entrada>
                </div>
              </div>
            </div>

            <div class="row down-2">
              <div class="col-12">
                <p class="informativoGris">
                  {{$ml.get('stripe').conditionsSuscriptions}}
                </p>
              </div>
            </div>




      </div>

      <div class="row down-2">
        <div class="col-md-12">
          <div v-if="this.error" class="alert alert-danger">
            <strong>{{$ml.get('auth').prError}}</strong> {{$ml.get('stripe').problem}}
          </div>
        </div>
      </div>


      <div class="form-group row down-4">
          <div class="col-md-12 ">
              <button type="submit" class="btn btn-primary boton">
                  {{$ml.get('auth').change}}
                  <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              </button>
          </div>
      </div>

      </form>






    </div>
</template>

<script>



export default {
  data() {
    return {
      loading:false,
      error:false,
      auth: this.$store.state.auth,
      form: {
        influencer:this.$store.state.auth.influencer,
        suscriptions: {
          month1: null,
          month3:null,
          month6:null,
          month12:null,

        }
      }
    }
  },
  created() {
    this.init();
  },
  methods: {

    init() {
      if(this.auth.plans !== null) {
        this.form.suscriptions.month1 = this.priceOf('month1');
        this.form.suscriptions.month3 = this.priceOf('month3');
        this.form.suscriptions.month6 = this.priceOf('month6');
        this.form.suscriptions.month12 = this.priceOf('month12');
      }
    },
    priceOf(name) {
      for (var i = 0; i < this.auth.plans.length; i++) {
        if(this.auth.plans[i].payForEvery == name) {
          return this.auth.plans[i].price
        }

      }
    },
    edit() {
      if(this.loading) {
        return true;
      }
      if(this.auth.influencer == true && this.form.influencer == false) {
        if(!confirm(this.$ml.get('stripe').sureRemoveSuscriptions)) {
          this.loading = false;
          return true;
        }
      }
      if(this.auth.influencer && this.form.influencer == true) {
        if(!confirm(this.$ml.get('stripe').confirmChange)) {
          this.loading = false;
          return true;
        }
      }
      this.loading = true;
      var self = this
      axios.post('/api/auth/influencer', this.form, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(response => {
        if(response.data.rc == 1) {
          self.$store.state.auth = response.data.data
          alert(self.$ml.get('stripe').successSuscriptions)
          self.$router.push('/profile')
        } else {
          self.error = true
        }

      })
      .finally(response => {
        self.loading = false
      })
    }
  }
};
</script>
