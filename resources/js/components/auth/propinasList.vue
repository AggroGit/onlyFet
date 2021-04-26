<template>
    <div class="container-fluid mt-4">
      <div v-if="this.loading" class="container text-center contieneCargador">
        <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
      <div v-else class="container contenedor">
        <div class="row">
          <div class="col-md-12">
            <h4>{{$ml.get('propina').payments}}</h4>
            <loginButton/>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="propina mt-5" v-for="propina in this.propinas.slice().reverse()"  :key="propina.id">
              <div class="row">
                <div class="col-7">
                  <h5>{{$ml.get('propina').title}}</h5>
                  <p>{{$ml.get('propina')[propina.type]}}</p>
                  <p v-if="propina.message !== null">{{$ml.get('propina').message}} : {{propina.message}}</p>
                </div>
                <div class="col-5 text-right bigAvat">
                  <avatar class="" :us="propina.from"/>
                </div>
              </div>
              <div class="row text-center mt-3">
                <span class="col-4 propinPrice">{{propina.price_sended}} €</span>
                <span class="col-4 datePrice" >{{$store.state.date(propina.created_at)}}   {{$store.state.time(propina.created_at)}}</span>
                <p class="col-4">{{status(propina)}}</p>
              </div>

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
      loading:true,
      propinas:[]
    }
  },
  mounted() {
    this.getPropins()
  },
  methods: {
    getPropins() {
      axios.post('/api/auth/propinas', null, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(response => {
        if(response.data.rc == 1) {
          this.propinas = response.data.data
        }
      })
      .finally(response => {
        this.loading = false;
      })
    },
    status(propina) {
      var tr = this.$ml.get('propina')
      if(propina.failed) {
        return tr.failed;
      }
      if(propina.sended) {
        return tr.sended
      } else {
        return tr.pending
      }
    }
  }

};
</script>
