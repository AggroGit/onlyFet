<template>
  <div class="fondoGris">
    <div v-if="this.loading" class="container text-center contieneCargador">
      <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>



    <div v-if="!this.loading" class="container contenedor">

      <div class="historyElement down-2 container aparecer">
        <div class="row">
          <div class="col-6">
            {{$ml.get('shop').needHelp}}
          </div>
          <div class="col-6 text-right">
            <a class="noLink" :href="'mailto:'+$ml.get('shop').correoAyudaShop">  {{$ml.get('shop').correoAyudaShop}}</a>
          </div>
        </div>
      </div>

      <div v-for="(purchase) in this.purchases" :key="purchase.id" class="historyElement down-2 aparecer">
        <div>
            <h5>{{$ml.get('shop').ordered_at}}: {{purchase.fecha}}</h5>
        </div>
        <div v-for="(order) in purchase.orders" :key="order.id" >
          <div v-if="order.product" class="contienePedido">
            <span><strong>{{order.product.name}}</strong></span>
            <span>{{$ml.get('shop').price}} {{order.product.price}}€</span>
          </div>
        </div>
        <div class="contienePedido">
          <span>{{$ml.get('shop').sendingCost}}:  {{purchase.sending_price}}€</span>
        </div>
        <div class="contienePedido down-1">
          <span>{{$ml.get('shop').totalPrice}}:  {{purchase.total_price}}€</span>
        </div>
      </div>
    </div>
  </div>

</template>

<script>



export default {
  data() {
    return {
      loading:true,
      purchases: []
    }

  },
  mounted() {
    this.getHistory();
  },
  methods :{
    getHistory() {
      var self = this
      axios.post('/api/history', null,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })

      // then
      .then(function (response) {
        console.log(response)
        if(response.data.rc == 1) {
          self.purchases = response.data.data
        } else {
          alert('Some Error')
        }


      })
      // finally
      .finally(() => this.loading = false)
    }

  }
};
</script>
