<template>
  <div class="container">
    <!-- Imagen logo -->


    <div class="row justify-content-center">
      <div class="col-md-3 logoLogin">
        <img class="img-fluid" src="/logo.png" alt="">
      </div>
    </div>

    <!-- Formulario login -->
      <div class="row justify-content-center">
          <div class="col-md-6">
              <div class="card">
                  <div class="card-body">
                      <form  @submit.stop.prevent="login()" >
                          <div class="form-group row">
                            <entrada v-model="form.email" :label="'email'" :name="'email'" :type="'email'" :autofocus="true" autocomplete="email" :required="true"></entrada>
                          </div>

                          <div class="form-group row">
                            <entrada v-model="form.password" :label="'Contraseña'" :name="'password'" :type="'password'" :autocomplete="'current-password'" :required="true"></entrada>
                          </div>

                          <div class="form-group row">
                              <div class="col-md-12">
                                  <div class="form-check">
                                      <input class="form-check-input" type="checkbox" required name="remember" id="remember" >
                                      <label class="form-check-label" for="remember">
                                          Politica de privacidad
                                      </label>
                                  </div>
                              </div>
                          </div>

                          <div class="row">
                            <div class="col-md-12">
                              <div v-if="this.error" class="alert alert-danger">
                                <strong>Vaya!</strong> Parece que el usuario no existe o tienes mal los datos
                              </div>
                              <div class="col-md-12 text-center" to="/login">
                                <a class="btn btn-link" >
                                  Forgot Your Password?
                                </a>
                              </div>
                            </div>
                          </div>

                          <div class="form-group row ">
                              <div class="col-md-12 offset-md-12">
                                  <button type="submit" class="btn btn-primary boton">
                                      {{$ml.get('auth').login}}
                                      <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                  </button>
                              </div>
                          </div>


                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>



export default {
  data(){
    return {
      loading:false,
      error:false,
      form: {
        email:null,
        password:null
      }
    }
  },
  mounted() {
    console.log(window.name);
    // si ya hay usuario lo echamos
    if(this.$store.state.auth) {
      this.$router.push('home')
    }
  },
  methods: {

    // call to login sesion
    login() {
      //
      this.loading=true
      //
      var self = this;
      axios
      .post('/api/login',this.form)
      // then
      .then(function (response) {
        self.interpretate(response)
      })
      .catch(function (response) {
        alert('Server Error')
      })
      // finally
      .finally(() => this.loading = false)
    },

    // interpretate reponses
    interpretate(response) {
      // si es correcto ponemos el token en cookie
      if(response.data.rc == 1) {
        // datos del usuario
        this.$store.state.auth = response.data.data.user
        // cookie
        this.$store.state.putTokenInCookie(response.data.data.access_token)
        // in our
        this.$store.state.token = response.data.data.access_token
        // put user info globally
        window.user = response.data.data.user;
        // init conection
        this.$store.state.initConection(response.data.data.access_token);
        // start the user connection
        // this.$store.state.authChannelConnect();
        this.$store.state.authChannel = window.Echo.join(appCode+'.User.'+user.id);//+self.$store.state.auth.id);
        this.$store.state.appchannel = window.Echo.join(appCode+'.App');//+self.$store.state.auth.id);
        //
        this.$router.push('home')

      } else {
        this.error = true;
      }

    }





  }
};
</script>
