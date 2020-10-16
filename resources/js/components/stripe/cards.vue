<template>
    <div class="container aparecer down-2">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <button v-if="!this.adding && !this.loading" @click="addVisa()" type="submit" class="btn btn-primary boton">
              {{$ml.get('stripe').add}}
              <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          </button>

          <stripe-add-visa  v-if="this.adding"></stripe-add-visa>

          <div v-if="this.loading" class="container text-center contieneCargador">
            <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </div>

          <div v-if="this.cards.length == 0 && !this.loading" class="Empty text-center">
            <img src="/iconos/empty-tag.png" alt="">
            <h5>{{$ml.get('stripe').noCards}}</h5>
          </div>


          <div v-for="(card) in this.cards" :key="card.id" class="card down-2">
            <div class="card-header">
              <b-icon icon="credit-card" aria-hidden="true"></b-icon>
              {{card.card.brand}}
            </div>
            <div class="card-body">
              <div class="left">
                ********* {{card.card.last4}}
              </div>
              <div class="right">
                caduca el
                {{card.card.exp_month}}/{{card.card.exp_year}}
              </div>

            </div>
          </div>

        </div>
      </div>




      <!-- </div> -->

    </div>
</template>

<script>



export default {
  data() {
    return {
      cards: [],
      loading:true,
      adding: false
    }
  },
  mounted() {
      this.listCards();
  },
  created() {
    //
  },
  methods: {
    listCards() {
      this.loading = true;
      axios.post('/api/stripe/cards', null,
       {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
       .then(response => {
         console.log(response)
         if(response.data.rc == 1) {
           this.cards = response.data.data
         }
       })
       .finally(response => {
         this.loading = false;
       })

    },
    addVisa() {
      this.adding = true;
    }
  }
};
</script>
