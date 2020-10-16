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
                      <form  @submit.stop.prevent="register()" >

                          <div class="form-group row">
                              <entrada v-model="form.name" :label="'name'" :name="'name'" :autocomplete="'name'" :type="'text'" :autofocus="true" :required="true"></entrada>
                          </div>

                          <div class="form-group row">
                              <entrada v-model="form.email" :label="'email'" :name="'email'" :autocomplete="'email'" :type="'email'"  :required="true"></entrada>
                          </div>

                          <div class="form-group row">
                              <entrada v-model="form.password" :label="'Contraseña'" :name="'password'" :type="'password'" autocomplete="new-password" :required="true"></entrada>
                          </div>


                          <div class="form-group row">
                              <entrada v-model="form.passwordRepeat" :label="'Repetir Contraseña'" :name="'password_confirmation'" :type="'password'" autocomplete="new-password" :required="true"></entrada>
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

                            <div class="form-group row ">
                                <div class="col-md-12 offset-md-12">
                                    <button type="submit" class="btn btn-primary boton">
                                        {{$ml.get('auth').register}}
                                        <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                          </form>


                          <div class="form-group row ">
                              <div class="col-md-12 offset-md-12">
                                 <router-link to="/login">
                                    <button  class="btn btn-primary boton">
                                        {{$ml.get('auth').changelogin}}
                                        <!-- <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> -->
                                    </button>
                                </router-link>
                              </div>
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


                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>

// import { MLBuilder } from 'vue-multilanguage'

export default {
  data(){
    return {
      loading:false,
      error:false,
      exists:false,
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
    console.log(window.name);
    // si ya hay usuario lo echamos
    if(this.$store.state.auth) {
      this.$router.push('home')
    }
  },
  methods: {

    checkForUp(event) {
      console.log(event)
    },
    register() {
      //
      this.loading=true
      //
      var self = this;
      axios
      .post('/api/register',this.form)
      // then
      .then(function (response) {
        self.interpretate(response)
      })
      // finally
      .finally(() => this.loading = false)
    },

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
        if(response.data.rc == 2) {
          this.exists = true;
          return true;
        }
        this.error = true;
      }

    }






  }
};
</script>
