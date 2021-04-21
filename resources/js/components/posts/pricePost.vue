<template>
      <div class="com-md-12">
        <form  @submit.stop.prevent="lock()" >
          <div class="form-group row">
            <entrada v-model="form.price" :label="$ml.get('propina').label+' €'" :autocomplete="'none'" :key="data.id" :inputmode="'numeric'" :type="'number'" :min='1' :max="'400'" :step="'1'" :autofocus="false" :required="true"></entrada>
          </div>
          <!--  -->
            <button type="submit" class="btn btn-primary boton botonParriba">
                {{$ml.get('propina').send}}
                <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>
        </form>
      </div>
</template>

<script>



export default {
  props: ['data'],
  data() {
    return {
      auth: this.$store.state.auth,
      loading:false,
      haveToAdd: false,
      form: {
        price: null,
      }
    }
  },
  mounted() {
    console.log(window.name);
  },
  methods: {
    lock() {
      if(this.loading)
        return true;

      var self = this
      this.loading = true;
      axios.post('/api/post/'+self.data.id+'/private', self.form, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(response => {
        if(response.data.rc == 1) {
          alert(self.$ml.get('post').successPrivate)
          self.$router.push('/post/'+self.data.id)
        } else {
          alert('Error');
        }
      })
      .catch(response => {
        alert('Error');
      })
      .finally(response => {
        self.loading = false;
      })

    }
  }

};
</script>
