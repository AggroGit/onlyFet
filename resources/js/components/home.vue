<template>
      <div class="container-fluid">
        <div class="row justify-content-center contieneEnrutador sombreado">

            <div class="col-4 text-center">
              <router-link class="noLink enrutadorMen" to="/novedades/news">
                {{$ml.get('menu').news}}
              </router-link>
              <div v-if="this.$route.name == 'news'"  class="lineaSeparadora aparecerIzquierda"></div>

            </div>

            <div class="col-4 text-center enrutadorMen">
              <router-link class="noLink enrutadorMen" to="/novedades/suscriptions">
                {{$ml.get('auth').suscriptionsAndNotis}}
              </router-link>
              <div v-if="this.$route.name == 'suscriptions'"  class="lineaSeparadora aparecerDerecha"></div>
            </div>
        </div>

        <div class="row aparecer">
          <router-view></router-view>
        </div>

      </div>
</template>

<script>



export default {
  mounted() {
    console.log(window.name);
  }
};
</script>
