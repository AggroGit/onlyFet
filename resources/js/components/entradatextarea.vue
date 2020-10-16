<template>
  <div class="col-md-12 contieneInput">
      <label :for="this.name" class="entrada detextarea" v-bind:class="{ noUp: empty }">{{this.label}}</label>
      <textarea  @change="checkEmpty()" v-model="inputVal" :rows="rows" :id="name" :name="name" :type="type"  class="form-control" :required="required" :autocomplete="autocomplete" :autofocus="autofocus"></textarea>
  </div>
</template>

<script>



export default {
  props: {
    value :{
      default: "",
    },
    rows: {
      default:3,
    },
    type: {
      default:"text"
    },
    required: {
      default:false
    },
    autocomplete:{
      default: false
    },
    autofocus: {
      default: false
    },
    label: {
      default: "Valor"
    },
    name: {
      default: "name"
    }
  },
  data(){
    return {
      empty:false,
    }
  },
  computed: {
    inputVal: {
      get() {
        return this.value;
      },
      set(val) {
        this.$emit('input', val);
      }
    }
  },
  mounted() {
    console.log(window.name);
  },
  methods: {
    checkEmpty() {
      return false;
    }
  }
};
</script>
