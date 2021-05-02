<template>
    <div v-if="auth.wantToBeInfluencer && auth.influencer == false" class="container contenedor mt-5 mb-3">
      <router-link v-if="!auth.updoadRequiredPics" to="/post/create">
        <div class="alert alert-danger">
          <!-- PRICES -->
          {{this.$ml.get('verification').postPending}} {{auth.numUploadedPosts}}/10
        </div>
      </router-link>
      <div v-if="!auth.verified" class="alert alert-warning">
        <!-- VERIFICATION -->
        {{this.$ml.get('verification').pending}}
      </div>

      <router-link v-if="!auth.prices_added && auth.stripe_created" to="/profile/suscriptions">
        <div class="alert alert-warning">
          <!-- PRICES -->
          {{this.$ml.get('verification').pricePending}}
        </div>
      </router-link>

      <router-link v-if="auth.verified && auth.stripe_created == false" to="/profile">
        <div class="alert alert-warning">
          <!-- PRICES -->
          {{this.$ml.get('stripe').info.login}}
        </div>
      </router-link>



    </div>
</template>
<script>



export default {
  data() {
    return {
      auth: this.$store.state.auth

    }
  },
  mounted() {
    console.log(window.name);
  }
};
</script>
