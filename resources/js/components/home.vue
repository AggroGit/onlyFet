<template>
      <div class="container-fluid">
        <div class="row justify-content-center contieneEnrutador sombreado">

            <div class="">
              <router-link class="noLink" to="/home/news">
                Novedadess
              </router-link>
              <div v-if="this.$route.name == 'news'"  class="lineaSeparadora aparecerIzquierda"></div>

            </div>

            <div class="">
              <router-link class="noLink" to="/home/suscriptions">
                Suscripciones
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
