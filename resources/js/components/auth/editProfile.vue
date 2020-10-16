<template>
    <div class="container down-2 aparecer">
      <!-- CACEBZERA FOTO  -->
      <div class="row justify-content-center">
        <div class="col-xs-6">

          <div class="ProfileImg">
            <picture-input ref="image" accept="image/jpeg,image/jpg,image/png,png,jpg,jpeg"  :v-model="image"  @change="onChange"  :prefill="profileImage()"></picture-input>
          </div>
          <div class="contieneIconoEditar" @click="onChange">
            <b-icon v-if="!this.loadingImage" icon="pencil-fill"></b-icon>
            <span v-if="this.loadingImage" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          </div>

        </div>
        <div class="col-xs-6 ContieneDatosperfil">
          <h4>{{auth.name}}</h4>
          <br>
          <p><strong>{{$ml.get('auth').phone}}: </strong>{{auth.phone_number}}</p>
          <p><strong>{{$ml.get('auth').email}}: </strong> {{auth.email}}</p>
          <p><strong>{{$ml.get('auth').name}}: </strong> {{auth.name}}</p>
        </div>
      </div>





      <!-- INFO -->
      <div class="row down-5  justify-content-center">
        <div class="col-md-6">
          <form  @submit.stop.prevent="edit()" >

              <div class="form-group row">
                  <entrada v-model="form.name" :label="$ml.get('auth').name" :name="'name'" :autocomplete="'name'" :type="'text'" :autofocus="true" :required="false"></entrada>
              </div>

              <div class="form-group row">
                  <entrada v-model="form.email" :label="$ml.get('auth').email" :name="'email'" :autocomplete="'email'" :type="'email'"  :required="false"></entrada>
              </div>

              <div class="form-group row">
                  <entrada v-model="form.password" :label="$ml.get('auth').newPass" :name="'password'" :type="'password'" autocomplete="new-password" :required="false"></entrada>
              </div>


              <div class="form-group row">
                  <entrada v-model="form.passwordRepeat" :label="$ml.get('auth').RepPassword" :name="'password_confirmation'" :type="'password'" autocomplete="new-password" :required="false"></entrada>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div v-if="this.error" class="alert alert-danger">
                    <strong>{{$ml.get('auth').prError}}</strong> {{$ml.get('auth').error}}
                  </div>
                  <div v-if="this.exists" class="alert alert-danger">
                    <strong>{{$ml.get('auth').prError}}</strong> {{$ml.get('auth').exist}}
                  </div>
                </div>
              </div>

                <div class="form-group row ">
                    <div class="col-md-12 offset-md-12">
                        <button type="submit" class="btn btn-primary boton">
                            {{$ml.get('auth').change}}
                            <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
              </form>




        </div>

      </div>
    </div>
</template>

<script>



export default {
  data() {
    return {
      image:false,
      loadingImage:true,
      loading:false,
      error:false,
      exists:false,
      auth: this.$store.state.auth,
      test:"",
      form: {
        email:null,
        password:null,
        passwordRepeat:null,
        name:null,
      }
    }

  },
  mounted() {
      this.loadingImage = false;
  },

  methods: {
    profileImage() {
      if(this.auth.image) {
        return this.auth.image.sizes.Med;
      }
      return null;
    },
    onChange(e) {
      this.loadingImage = true;
      var image = this.$refs['image'].file
      var formData = new FormData();
	     formData.append('image', image);
       axios.post('/api/auth/edit', formData,
      {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(response => {
        console.log(response)
        if(response.data.rc == 0) {
          if(response.data.data.image) {
            alert(response.data.data.image)
          } else {
            alert('parece que hay un problema con la imagen')
          }

        }
      })
      .catch(response => {
        alert('Parece que ha habido un error al subir la imagen')
      })
      .finally(response => {
        this.loadingImage = false;
      })

      console.log(this.$refs['image'])
    },
    edit() {
      if(this.form.password !== "" ){
        if(this.form.password !== this.form.passwordRepeat){
          alert('Las contraseñas no coinciden')
          return false;
        }
      }
      this.loading = true;
      var self = this;
      axios.post('/api/auth/edit', this.form,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
      // then
      .then(function (response) {
        console.log(response)
        if(response.data.rc == 1) {
          self.$store.state.auth = response.data.data
          self.$router.push('/profile')
          return true
        }
        if(response.data.rc == 2) {
          self.exists = true
          return true
        }
      })
      // finally
      .finally(() => this.loading = false)

    }
  }
};
</script>
