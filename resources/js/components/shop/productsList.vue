<template>
  <div class="contenedor">

    <div v-if="this.loading" class="container text-center contieneCargador">
      <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>

    <!-- BUSCADOR Y BORRAR CHATS-->
    <div class="contieneBuscadorLogoAntes sinFondo">
        <div class="contenedor  buscaProductos">
          <b-form-input autocomplete="off" v-model="search" @keyup="filteredList()" id="input-small" class="col-xs-8" :placeholder="$ml.get('shop').search" :autofocus="true"></b-form-input>
          <b-icon  font-scale="2" @click="openFilter()" icon="filter" aria-hidden="true" class="icon"></b-icon>
        </div>

    </div>

    <div v-if="!this.loading" class="">

      <b-carousel
      id="carousel-no-animation"
      style="text-shadow: 0px 0px 2px #000"
      no-animation
      indicators
      controls
      img-height="300"
      class="carouselShop"
    >

      <b-carousel-slide
        v-for="(image) in this.images" :key="image.id"
      >
      <a class="allA"  :href="image.sizes.Big">
        <img
        :src="image.sizes.Big"
        alt=""
        class="carouselShop"
          >
      </a>
    </b-carousel-slide>


    </b-carousel>

    </div>

    <div v-if="this.loading == false && this.searching == false" class="ContieneProductos down-4">
      <div v-for="(product) in this.products" :key="product.id" class="container productList down-2 aparecer">
        <router-link :to="'/shop/'+product.id" class="row noLink">
          <div class="col-4 contieneImProduct">
            <img class="productImagelist" v-if="product.images[0]" :src="product.images[0].sizes.NotSmall" alt="">
          </div>
          <div class="col-8">
            <div class="row">
              <div class="col-12">
                <h5>{{product.name}}</h5>
              </div>
              <div class="col-12">
                <p class="descriptionProduct">{{product.description}}</p>
              </div>
              <div class="col-12 atBottom">
                <strong>{{$ml.get('shop').price}}   {{product.price}} €</strong>
              </div>
            </div>
          </div>
          <div class="lineaSeparadora grisacea down-2"></div>
        </router-link>
      </div>
      <div v-if="this.products.length == 0" class="Empty text-center">
        <img src="/iconos/empty-tag.png" alt="">
        <h5>{{$ml.get('shop').noProducts}}</h5>
      </div>
    </div>

    <div v-else class="container text-center contieneCargador">
      <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>

    <div class="PantallaFiltro" v-bind:class="{filering:filering}">
      <div class="contieneFiltros">
        <div class="container contenedor">
          <div class="row">
            <div class="col-md-6">
              <div class="form-check form-check-inline">
                <input class="form-check-input" v-model="expensive" @change="check('expensiveFirst')" type="checkbox" id="expensiveFirst" value="expensiveFirst">
                <label class="form-check-label" for="expensiveFirst">{{$ml.get('shop').expensiveFirst}}</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-check form-check-inline">
                <input class="form-check-input" v-model="cheaper" @change="check('cheapestFirst')" type="checkbox" id="cheapestFirst" value="cheapestFirst">
                <label class="form-check-label" for="cheapestFirst">{{$ml.get('shop').cheapestFirst}}</label>
              </div>
            </div>
          </div>
          <div  class="row down-2">
            <div class="col-12">
              <h5>Categorias: </h5>
            </div>
          </div>

          <div class="row contieneCates">
            <div v-for="(category) in this.categories" :key="category.id" class="col-md-4">
              <div class="form-check form-check-inline down-15">
                <input class="form-check-input " type="checkbox" :id="category.name" v-model="filters.categories"  :value="category.id">
                <label class="form-check-label" :for="category.name">{{category.name}}</label>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="botonFiltroAbajo row">
          <div class="col-12 flex">
              <button @click="openFilter()"  class="btn btn-primary boton botonFiltro">
                  {{$ml.get('shop').filter}}
                  <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              </button>
          </div>
      </div>


    </div>




  </div>

</template>

<script>


export default {
  data() {
    return{
      loading:false,
      searching:false,
      search:"",
      filering:false,
      categories: [],
      cheaper:false,
      expensive:false,
      filters: {
        orderBy:'default',
        categories: [],

      },
      images: [],
      products:[],
      in:[]

    }
  },
  mounted() {
    this.getInfo()

  },
  methods: {
    getInfo() {
      if(this.searching) {
        return false;
      }
      var self = this;
      axios.post('/api/products', this.filters,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
      // then
      .then(function (response) {
        console.log(response)
        if(response.data.rc == 1) {
          self.images = response.data.data.business.images
          self.products = self.in = response.data.data.products
          self.categories = response.data.data.categories
        }
        if(response.data.rc == 13) {
          self.$router.push('/login')
        }

      })
      .catch(() => alert('Server Error'))
      // finally
      .finally(() => this.loading = false)


    },
    check(value) {
      if(value == this.filters.orderBy) {
        this.filters.orderBy = "default"
        return
      }
      this.filters.orderBy = value
      if(this.expensive) {
        this.cheaper = false;
      } else {
        this.expensive = false;
      }


    },

    filteredList() {
      this.products = this.in,
      this.products = this.products.filter(product => {
        return product.name.toLowerCase().includes(this.search.toLowerCase())
      })
    },
    searchFromFilter() {
      if(this.searching) {
        return false;
      }
      this.searching = true;
      var self = this;
      axios.post('/api/products', this.filters,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
      // then
      .then(function (response) {
        console.log(response)
        if(response.data.rc == 1) {
          self.products = self.in = response.data.data.products
        }
        if(response.data.rc == 13) {
          self.$router.push('/login')
        }

      })
      .catch(() => alert('Server Error'))
      // finally
      .finally(function () {
        self.searching = false
        self.filteredList()

      })
    },
    openFilter() {
      this.filering = !this.filering;
      if(!this.filering) {
        this.searchFromFilter();
      }
    }
  }
};
</script>
