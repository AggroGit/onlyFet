<template>
  <div class="container">
    <!-- Imagen logo -->


    <div class="row justify-content-center">
      <div class="col-md-4 logoLogin">
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
                            <entrada v-model="form.email" :label="$ml.get('auth').email" :name="'email'" :type="'email'" :autofocus="true" autocomplete="email" :required="true"></entrada>
                          </div>

                          <div class="form-group row">
                            <entrada v-model="form.password" :label="$ml.get('auth').password" :name="'password'" :type="'password'" :autocomplete="'current-password'" :required="true"></entrada>
                          </div>

                          <!-- <div class="form-group row">
                              <div class="col-md-12">
                                  <div class="form-check">
                                      <input class="form-check-input" type="checkbox" required name="remember" id="remember" >
                                      <label class="form-check-label" for="remember">
                                          {{this.$ml.get('auth').legalTerms}}
                                      </label>
                                  </div>
                              </div>
                          </div> -->

                          <div class="row">
                            <div class="">
                              <div v-if="this.error" class="alert alert-danger">
                                <strong>{{this.$ml.get('auth').prError}}</strong> {{this.$ml.get('auth').error}}
                              </div>
                              <div class="col-md-12 text-left down-1" to="/login">
                                <p>{{this.$ml.get('auth').forget}} <a class="rojo" @click="openForget()" href="#">{{this.$ml.get('auth').recu}}</a></p>
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

                          <div class="form-group row ">
                              <router-link to="/register" class="col-md-12 offset-md-12">
                                  <button class="btn btn-primary boton">
                                      {{$ml.get('auth').changeRgister}}
                                      <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                  </button>
                              </router-link>
                          </div>



                      </form>


                      <div  class="form-group row contieneSeparadorRRSS down-2">
                          <div class="separadorRRSS"></div>
                      </div>

                      <!-- FACEBOOK -->
                      <div class="form-group row ">
                          <a href="/login/facebook" class="col-md-12 offset-md-12">
                              <button class="btn btn-primary boton facebook">
                                  {{$ml.get('auth').socialFacebook}}
                                  <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                              </button>
                          </a>
                      </div>

                      <!-- Google -->
                      <div  class="form-group row ">
                          <a disabled href="/login/google" class="col-md-12 offset-md-12">
                              <button class="btn btn-primary boton Google">
                                  {{$ml.get('auth').socialGoogle}}
                                  <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                              </button>
                          </a>
                      </div>









                  </div>
              </div>
          </div>
      </div>


      <div v-if="this.forget" class="contienePantallaCompletaDark aparecer">
        <div class="container text-center contieneCargador aparecer">
          <div class="ContienecONTRAfoRGET">
              <label for="forgetEmail">{{$ml.get('auth').email}}</label>
              <input name="forgetEmail"  v-model="form.email" type="email">
              <button @click="forgetPass()" class="btn btn-primary boton">
                  {{$ml.get('auth').recu}}
                  <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              </button>
          </div>
          <!-- <h3>Suscribiendo</h3> -->
        </div>
      </div>


  </div>
</template>

<script>



export default {
  data(){
    return {

      //FACEBOOK
      FB: {},
      model: {},
      scope: {},

      loading:false,
      error:false,
      forget:false,
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
      this.$router.push('/')
    }
  },
  methods: {


    handleSdkInit({ FB, scope }) {
       this.FB = FB
       this.scope = scope
       FB.login(function(response) {
            // handle the response
            console.log(response)
        }, {
            scope: 'email',
            return_scopes: true
        });
     },











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
        this.$router.push('/')

      } else {
        this.error = true;
      }

    },
    openForget() {
      this.forget = !this.forget
    },
    forgetPass() {
      this.loading=true
      //
      var self = this;
      axios
      .post('/api/forget',{
        email: this.form.email
      })
      // then
      .then(function (response) {
        alert(self.$ml.get('auth').recoverEmail)
      })
      .catch(function (response) {
        alert('Server Error')
      })
      // finally
      .finally(() => this.loading = false,self.forget = false)

    },





  }
};
</script>
