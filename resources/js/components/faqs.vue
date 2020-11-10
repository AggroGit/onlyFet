<template>
  <div class="container">
    <div v-if="this.loading" class="container text-center contieneCargador">
      <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>

    <div class="row down-4">
      <div class="col-md-12">
        <p>¿Necesitas ayuda? Escríbenos a ayuda@onlyfet.com indicando tu nombre de usuario y nos pondremos en contacto contigo tan pronto como nos sea posible.
Consulta el apartado de FAQs.
<br>
<br>
Need help? Write to us at help@onlyfet.com indicating your username and we will get in touch with you as soon as possible. Check the FAQs section.
</p>
      </div>
    </div>

    <div v-if="!this.loading" class="row">
      <div v-for="(faq) in this.faqs" :key="faq.id" class="col-md-12">
        <div class=" down-2">
          <b-button class="faqsButton" v-b-toggle="'da-'+faq.id">
          <span class="when-open"></span><span class="when-closed"></span> {{faq.title}}
        </b-button>
        <b-collapse :id="'da-'+faq.id+''" class="down-1">
          {{faq.content}}
        </b-collapse>
        </div>
      </div>
    </div>







  </div>

</template>

<script>



export default {
  data() {
    return {
      loading:true,
      faqs:[]
    }
  },
  created() {
    this.getFaqs();
  },
  methods: {
    getFaqs() {
      axios.post('/api/faqs', null, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(response => {
        if(response.data.rc == 1) {
          console.log(response)
          this.faqs = response.data.data;
        }
      })
      .finally(response => {
        this.loading = false;
      })
    },
  }
};
</script>
