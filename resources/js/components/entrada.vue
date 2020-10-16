<template>
  <div class="col-md-12 contieneInput">
      <input  @change="checkEmpty()" v-model="inputVal" :id="name" :name="name" :type="type"  class="form-control" :required="required" :autocomplete="autocomplete" :autofocus="autofocus">
      <label :for="this.name" class=" entrada" v-bind:class="{ noUp: empty }">{{this.label}}</label>
  </div>
</template>

<script>



export default {
  props: {
    value :{
      default: "",
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
      if(this.value.length !== 0) {
        this.empty = true
      } else {
        this.empty = false
      }
    }
  }
};
</script>
