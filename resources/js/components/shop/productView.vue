<template>
    <div class="contenedor">


      <div v-if="this.loading" class="container text-center contieneCargador">
        <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>

      <div v-if="!this.loading" class="contenedor">

        <b-carousel
        id="carousel-no-animation"
        style="text-shadow: 0px 0px 2px #000"
        no-animation
        indicators
        controls
        img-height="400"
        class=""
      >


        <b-carousel-slide
          v-for="(image) in this.product.images" :key="image.id"
          :img-src="image.sizes.Big"
          img-height="400"
          @click="abrirImagen(image)"
          class="ImagenProducto"
        ></b-carousel-slide>


      </b-carousel>

      <div class="container down-2">
        <div class="row">
          <div class="col-12">
            <h4>{{product.name}}</h4>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            {{product.description}}
          </div>
        </div>
        <div class="row down-3">
          <div class="col-12">
            <strong>{{$ml.get('shop').price}} {{product.price}} €</strong>
          </div>
          <br>
          <br>
          <br>
        </div>

        <div class="row down-3">
            <div class="col-12 downed">
                <button @click="addToCart()"  class="btn btn-primary boton aparecer">
                    {{$ml.get('shop').addCart}}
                    <span v-if="this.adding" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>

      </div>

      </div>
    </div>
</template>

<script>



export default {
  data() {
    return {
      product:null,
      loading:true,
      adding:false

    }
  },
  mounted() {
    this.getproduct();
  },
  methods: {
    getproduct() {
      var self = this;
      axios.post('/api/product/'+this.$route.params.product_id, null, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(function (response) {
        console.log(response)
        if(response.data.rc == 1) {
          self.product = response.data.data
        }
      })
      .catch(function (error) {
        alert('server error');
      })
      .then(function () {
        self.loading = false;

      });
    },
    addToCart() {
      if(this.adding) {
        return
      }
      this.adding = true;
      var self = this;
      axios.post('/api/product/'+this.$route.params.product_id+'/add', null, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(function (response) {
        if(response.data.rc == 1) {
          self.$store.state.numProducts = self.$store.state.numProducts+1;
          self.$router.push('/shop/cart')
        }
        if(response.data.rc == 13) {
          alert(self.$ml.get('shop').haveLoggin)
          self.$router.push('/login')
        }
        if(response.data.rc == 211) {
          alert(self.$ml.get('shop').haveAddDirection)
          self.$router.push('/profile/edit')
        }
      })
      .catch(function (error) {
        alert('server error');
      })
      .then(function () {
        self.adding = false;

      });

    }
  },
  abrirImagen(imagen) {
    alert(imagen.id)

  }


};
</script>
