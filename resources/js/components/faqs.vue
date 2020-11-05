<template>
  <div class="container">

    <div class="row down-4">
      <div class="col-md-12">
        <p>¿Necesitas ayuda? Escríbenos a ayuda@onlyfet.com indicando tu nombre de usuario y nos pondremos en contacto contigo tan pronto como nos sea posible.
Consulta el apartado de FAQs.</p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class=" down-2">
          <b-button class="faqsButton" v-b-toggle:1>
          <span class="when-open"></span><span class="when-closed"></span> ¿Cómo se realizan los pagos?
        </b-button>
        <b-collapse id="1" class="down-1">
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia recusandae quaerat ea id consequatur debitis facere numquam minima et voluptatum! Ab suscipit ut praesentium distinctio molestias est inventore recusandae sunt!
        </b-collapse>
        </div>
      </div>

      <div class="col-md-12">
        <div class=" down-2">
          <b-button class="faqsButton" v-b-toggle:2>
          <span class="when-open"></span><span class="when-closed"></span> ¿Cómo se realiza una suscripción?
        </b-button>
        <b-collapse id="2" class="down-1">
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia recusandae quaerat ea id consequatur debitis facere numquam minima et voluptatum! Ab suscipit ut praesentium distinctio molestias est inventore recusandae sunt!
        </b-collapse>
        </div>
      </div>

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
