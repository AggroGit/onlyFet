<template>
  <a  :href="this.urlStripe">
    {{this.auth}}
     <b-button  v-b-tooltip.hover.bottom="$ml.get('stripe').info[text]" class="btn btn-primary boton stripe">
         <!-- {{$ml.get('auth').stripe}} -->
         <span v-if="this.stripeLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         <p v-else>{{$ml.get('stripe')[text]}}</p>
     </b-button>
  </a>
</template>

<script>


export default {
  data() {
    return {
      stripeLoading: true,
      urlStripe:"",
      text:"create",
      auth: this.$store.state.auth
    }
  },
  created() {
    this.getUrlStripe()
  },
  methods: {
    getUrlStripe() {
      var self = this
      axios.post('/api/stripe/url', null,
       {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
       .then(response => {
         console.log(response)
         if(response.data.rc == 1) {
           self.urlStripe = response.data.data
         }
         if(response.data.rc == 809) {
           self.urlStripe = response.data.data.url
           self.text = "login"
         }

       })
       .catch(response => {

       })
       .finally(response => {
          self.stripeLoading = false
       })
      }

  }
};
</script>
