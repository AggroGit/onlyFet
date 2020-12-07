<template>
      <div class="card sombreado">
        <div class="card-header">
          <b-icon icon="credit-card" aria-hidden="true"></b-icon> Añadir método de pago
        </div>
        <div class="card-body tarjeta">
          <div id="card-element" class="tarjeta"></div>

          <button  class="btn btn-primary boton" id="card-button">
              {{$ml.get('stripe').add}}
              <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          </button>
        </div>
      </div>
</template>

<script>



export default {
  props: {
    redirect: {
      default: "/profile",

    }
  },
  data() {
    return {
      auth: this.$store.state.auth,
      loading: false,


    }
  },
  mounted() {
    const stripe = Stripe('pk_test_uJokSRQ3iQnavXztyfmoigfy');

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');

    cardButton.addEventListener('click', async (e) => {
        if(this.loading) {
          return true;
        }
        // console.log(cardElement)
        const { paymentMethod, error } = await stripe.createPaymentMethod(
            'card', cardElement, {
                billing_details: { name: this.auth.name }
            }
        );

        if (error) {
          alert('Error')
        } else {
          this.loading = true;
          console.log(paymentMethod)
          axios.post('/api/stripe/add', {
            id:paymentMethod.id
          },
         {
           headers:{
              Authorization: `Bearer `+ this.$store.state.token
           }
         })
         .then(response => {
           console.log(response)
           if(response.data.rc == 1) {
             // this.auth = response.data.data;
             alert(this.$ml.get('post').nowCan)
             window.location.reload()

           } else {
             alert(this.$ml.get('stripe').problem)

           }


         })
         .catch(response => {
           alert("Vaya, parece que hay algun problema con el servidor")
         })
         .finally(response => {
           this.loading = false;
         })

        }
    });
  }
};
</script>
