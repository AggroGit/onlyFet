<template>
    <div class="container-fluid entero">
      <div v-if="this.loading" class="container text-center contieneCargador aparecer">
        <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
      <img v-if="!this.loading" class="adaptativa" :src="this.image.sizes.Full" alt="">
    </div>
</template>

<script>



export default {
  data(){
    return {
      loading:true,
      image:null,
    }
  },
  beforeRouteLeave (to, from, next) {
    this.$store.state.entero = false
    // alert('desc')
    next()
  },
  mounted() {
    var self = this
    this.$store.state.entero = true
    axios.post('/api/'+this.$route.params.post_id+'/image/'+this.$route.params.name, null, {
      headers:{
         Authorization: `Bearer `+ this.$store.state.token
      }
    })
    .then(response => {
      //
      console.log(response)
      if(response.data.rc == 1) {
        self.image  = response.data.data
        return true
      }
      if(response.data.rc == 13) {
        self.$router.push('/login')
      }
      else {
        self.$router.push('/home');
      }

    })
    .finally(response => {
      self.loading = false;
    })
  }
};
</script>
