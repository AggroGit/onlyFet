<template>
          <div class="col-md-12">
              <router-link :to="'/auction/'+this.user.current_auctions[0].id" type="submit" class="btn btn-primary boton entrarPuja">
                <h5>
                  {{$ml.get('auction').enterCurrentAuction}}
                </h5>
              </router-link>
          </div>
</template>

<script>



export default {
  props:['user'],
  mounted() {
  }
};
</script>
