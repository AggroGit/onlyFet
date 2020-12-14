<template>
    <div class="container contenedor">

      <div v-if="this.loading" class="container text-center contieneCargador">
        <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>

      <div v-if="this.loading == false" class="ContieneProductos down-4">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h4>{{$ml.get('shop').cart}} ({{this.orders.length}})</h4>
            </div>
          </div>
        </div>
        <div v-for="(order,index) in this.orders" :key="order.id" class="container productList down-2 aparecer">
          <div  class="row ">
            <div class="col-4 contieneImProduct">
                <router-link :to="'/shop/'+order.product.id" class="row noLink">
                  <img class="productImagelist" v-if="order.product.images.length>0" :src="order.product.images[0].sizes.NotSmall" alt="">
                </router-link>
            </div>
            <div class="col-8">
              <div class="row">
                <div class="col-12">
                  <h5>{{order.product.name}}</h5>
                </div>
                <div class="col-12">
                  <p class="descriptionProduct">{{order.product.description}}</p>
                </div>
                <div class="col-12 atBottom">
                  <strong>{{$ml.get('shop').price}}   {{order.price}} €</strong>
                </div>
                <div class="contieneIconoBorrarProduct">
                  <b-icon @click="eliminar(order.id)" icon="trash" class="icon" scale="1.4"></b-icon>
                </div>
              </div>
            </div>
            <div v-if="index !== orders.length-1" class="lineaSeparadora grisacea down-2"></div>
          </div>
        </div>
        <div v-if="this.orders.length == 0" class="Empty text-center">

          <img src="/iconos/empty-tag.png" alt="">
          <h5>{{$ml.get('shop').noProducts}}</h5>
        </div>
      </div>

      <div class="row down-4">
        <div class="col-md-12">
          <p><strong>{{$ml.get('shop').direction}}:</strong> {{auth.direction}}</p>
        </div>
      </div>

      <div class="row down-2">
        <div class="col-md-12 contienePrecios">
          <div class="">
            <strong>{{$ml.get('shop').totalPriceOrders}}:</strong> <span>{{this.price_orders}} €</span>
          </div>
          <div class="">
            <strong>{{$ml.get('shop').sendingCost}}:</strong> <span>{{this.sending_price}} €</span>
          </div>
          <div class="">
            <strong>{{$ml.get('shop').totalPrice}}:</strong> <span><strong>{{this.total_price}} €</strong></span>
          </div>
          <div class="contieneMasterCard">
            <b-icon font-scale="1.5" class="iconMaster" icon="credit-card"></b-icon>  <span>{{auth.card_brand}} {{auth.card_last_four}}</span>
          </div>
        </div>
      </div>
      <br>
      <br>
      <br>

      <div class="container">
        <div class="row down-3">
            <div class="col-12 downed">
                <button @click="buy()"  class="btn btn-primary boton aparecer">
                    {{$ml.get('shop').buy}}
                    <span v-if="this.buying" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
      </div>



    </div>
</template>

<script>



export default {
  data() {
    return {
      orders:[],
      loading:true,
      data:null,
      auth:this.$store.state.auth,
      total_price:null,
      price_orders:null,
      sending_price:null,
      buying:false,
      idsToRemove:[],

    }
  },
  created() {
    this.getCarrito();
  },
  methods : {
    getCarrito() {
      var self = this;
      axios.post('/api/cart', null,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
      // then
      .then(function (response) {
        console.log(response)
        if(response.data.rc == 1) {
          self.orders = response.data.data.orders
          self.price_orders = response.data.data.price_orders
          self.sending_price = response.data.data.sending_price
          self.total_price = response.data.data.total_price
        }
        if(response.data.rc == 2) {

        }
        if(response.data.rc == 6) {

        }
      })
      // finally
      .finally(() => this.loading = false)
    },
    buy() {
      if(this.buying) {
        return
      }
      this.buying = true
      var self = this
      axios.post('/api/buy', null,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })

      // then
      .then(function (response) {
        console.log(response)
        if(response.data.rc == 1) {
          self.$store.state.numProducts = 0
          self.getCarrito();
          self.$router.push('/shop/history')
          return true;
        }
        if(response.data.rc == 211) {
          alert(self.$ml.get('shop').haveAddDirection);
          self.$router.push('/profile/edit#direction')
        }
        if(response.data.rc == 201) {
          alert(self.$ml.get('shop').problemwithyourVisa);
        }
        if(response.data.rc == 205) {
          alert(self.$ml.get('shop').noMinPrice);
        }

      })
      // finally
      .finally(() => this.buying = false)


    },
    eliminar(id) {
      if(confirm(this.$ml.get('shop').sureRemoving)) {
        var self = this
        this.idsToRemove = id
        axios.post('/api/cart/remove', {
          idsToRemove:self.idsToRemove
        },
        {
           headers:{
              Authorization: `Bearer `+ this.$store.state.token
           }
         })

        // then
        .then(function (response) {
          if(response.data.rc == 1) {
            self.$store.state.numProducts = self.$store.state.numProducts-1
            self.getCarrito();
            return true;
          }


        })
        // finally
        .finally(() => self.idsToRemove = null)
      }
    }


  }
};
</script>
