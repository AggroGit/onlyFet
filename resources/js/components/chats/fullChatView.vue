<template>
    <div class="container-fluid">
      <div class="row">
        <!-- LISTA -->
        <!-- <div class="col-md-4">
          <chatsList :full="true"></chatsList>
        </div> -->
        <!-- CHAT -->
        <!-- <div class="col-md-8">
            <chatView></chatView>
        </div> -->
        <input-tag v-model="tags" :validate="validar"  :allow-duplicates="false" >asd</input-tag>
        <p v-for="tag in tags"  :key="tag">{{tag}}</p>
        <br>
        <br>
        <button
          v-for="lang in $ml.list"
          :key="lang"
          @click="$ml.change(lang)"
          v-text="lang"
          />
        <p v-text="$ml.get('myMessage')" />
        <p>{{$ml.get('myMessage')}}</p>
      </div>
    </div>
</template>

<script>


import { MLBuilder } from 'vue-multilanguage'

export default {
  data() {
  return {
    tags:[],
    my:['juan','gerard']


    };
  },
  mounted() {
    console.log(window.name);
    // console.log(this.$language)

  },
  computed: {
    mlmyMessage () {
      return new MLBuilder('msg').with('f', 2).with('l', 406)
    }
  }
  ,methods: {
    validar(e) {
      console.log(e)
      return "asd";

    },
    myMessage() {
      return
    },
    mlmyMessage () {
      return new MLBuilder('msg').with('f', this.friends).with('l', 406)
    }
  }
};
</script>
