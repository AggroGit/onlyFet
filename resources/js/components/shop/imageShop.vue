<template>
    <div class="container-fluid entero">
      <img v-if="!this.loading" class="adaptativa" :src="this.image.sizes.Full" alt="">
    </div>
</template>

<script>



export default {
  mounted() {
    console.log(window.name);
  }
};
</script>
