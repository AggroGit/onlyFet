<template>

    <div class="container down-2 aparecer">
      <!-- CACEBZERA FOTO  -->
      <div class="row justify-content-center ">
        <div class="col-xs-6">
          <div class="ProfileImg">
            <img v-if="this.auth.image" :src="this.auth.image.sizes.NotSmall" alt="">
            <img v-else src="/default.png" alt="">
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
      <!-- EDITAR -->
      <div class="row justify-content-center">
          <div class="col-md-6">
             <router-link to="/profile/edit">
                <button  class="btn btn-primary boton">
                    {{$ml.get('auth').edit}}
                </button>
            </router-link>
          </div>
      </div>
      <!-- STRIPE -->
      <div class="row justify-content-center">
          <div class="col-md-6">
             <stripe-express></stripe-express>
          </div>
      </div>

      <!-- Creditcards -->
      <div class="row justify-content-center">
          <div class="col-md-6">
             <router-link to="/profile/cards">
                <button  class="btn btn-primary boton">
                    {{$ml.get('stripe').cards}}
                </button>
            </router-link>
          </div>
      </div>




    </div>
</template>

<script>



export default {
  data() {
    return {
      auth: this.$store.state.auth
    }
  },
  created() {

  }


};
</script>
