<template>
    <div @click="fav()" class="linkeable">
      <b-icon v-if="user.is_fav" style="color: black;" class="left " icon="star-fill" font-scale="1.5"></b-icon>
      <b-icon v-else style="color: black;" class="left " icon="star" font-scale="1.5"></b-icon>
    </div>
</template>

<script>



export default {
  props: ['user'],
  methods: {
    fav() {
      this.user.is_fav = !this.user.is_fav
      axios.post('/api/fav/'+this.user.id, null, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(response => {
      })
      .catch(response => {
        alert('ERROR FAV')
      })

    }

  }


};
</script>
