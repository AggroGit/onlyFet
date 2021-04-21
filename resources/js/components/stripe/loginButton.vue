<template>
    <div class="container-fluid">
      <b-button v-if="this.loading" v-b-tooltip.hover.bottom="$ml.get('stripe').infoLogin" class="btn btn-primary boton botonStripe">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      </b-button>

      <a  target="_blank" v-else :href="this.url">
        <b-button  class="boton btn btn-primary botonStripe">
          {{this.$ml.get('stripe').balance}}
        </b-button>
      </a>
    </div>
</template>
<script>



export default {
  data() {
    return {
      loading: true,
      url:null
    }
  },
  mounted() {
    this.stripe();
  },
  methods: {
    stripe() {
      axios.post('/api/stripe/url/login', null, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(response => {
        if(response.data.rc == 1) {
          console.log(response)
          this.url = response.data.data.url;
        }
      })
      .catch(er=>{

      })
      .finally(response => {
        this.loading = false;
      })
    }
  }
};
</script>
