<template>
  <div class="">

    <!-- BUSCADOR Y BORRAR CHATS-->
    <div class="contieneBuscadorLogoAntes">
        <div class="contenedor contieneBuscadorLogo">
          <b-form-input autocomplete="off" v-model="search" @keyup="filteredList()" id="input-small" class="col-xs-8" :placeholder="$ml.get('chat').lookUser"></b-form-input>
          <b-icon  font-scale="2" @click="Remove()" icon="trash" v-bind:class="{removing:removing}" aria-hidden="true" class="icon"></b-icon>
        </div>

    </div>

    <div v-if="this.loading" class="container text-center contieneCargador">
      <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>

    <div v-if="!this.loading" class="col-md-12 down-3">
      <div v-if="this.chats.length == 0" class="Empty text-center">
        <img src="/iconos/empty-tag.png" alt="">
        <h5>{{$ml.get('chat').nochat}}</h5>
      </div>
      <b-list-group class=" contenedor aparecer down-3">
          <b-list-group-item v-for="(chat) in this.chats" :key="chat.id"   :to="getUrlChat(chat)" class="flex-column align-items-start chatListItem" v-bind:class="{remvongg:removing}">
              <avatar :conection="true" :us="chat.otherUser"></avatar>
              <span class="mr-auto">{{chat.otherUser.name}}</span>

              <div v-if="removing" class="custom-control elimichat">
                <input class="form-check-input bigger" type="checkbox" id="chatremove" name="idremove" :ref="chat" @change="SelectChat(chat)" :value="chat.id">
              </div>

              <div v-if="chat.lastMessage" class="justify-content-between lastMessageList">
                <p  class="mb-1">{{chat.lastMessage.message}}</p>
                <p class="hourchat">{{chat.lastMessage.fecha}}</p>
                <div v-if="chat.lastMessage.read === false && chat.lastMessage.user_id !== $store.state.auth.id" class="noLeido"></div>
              </div>
          </b-list-group-item>
      </b-list-group>
    </div>
  </div>

</template>

<script>



export default {
  props:['chat','full'],
  data() {
    return{
      chats:[],
      list:[],
      search:null,
      idsToRemove:[],
      loading: true,
      removing:false,
      in:[]
    }
  },
  mounted() {
    this.getChats()
    console.log('keu')
    console.log(this)
  },
  methods: {


    getUrlChat(chat){

      if(this.removing == false) {
        if(this.full) {
          return "/full/chats/"+chat.id
        }
        return "chats/"+chat.id
      }

    },


    getChats() {
      // call
      axios.post('/api/chats', null, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(response => {
        if(response.data.rc == 1) {
          console.log(response)
          this.chats = this.in = response.data.data;
        }
      })
      .finally(response => {
        this.loading = false;
      })
    },


    // format better the time
    time(time) {
      var date = new Date(time);
      return date.getHours() +":"+date.getMinutes()
    },
    Remove() {
      if(this.removing) {
        if(this.idsToRemove.length > 0) {
          var r = confirm("¿Deseas eliminar "+this.idsToRemove.length+' chats?');
          if(r) {
            var self = this;
            this.loading = true,
            axios.post('/api/chats/remove', {
              ids:self.idsToRemove
            }, {
              headers:{
                 Authorization: `Bearer `+ this.$store.state.token
              }
            })
            .then(response => {
              if(response.data.rc == 1) {
                console.log(response)
                this.chats  = this.in = response.data.data;
              }
            })
            .finally(response => {
              this.loading = false;
            })
          }


        }
      }
      this.removing = !this.removing
      this.idsToRemove = [];
      console.log(this.$refs.chat)
    },
    SelectChat(chat) {
      if(event.target.checked) {
        this.idsToRemove.push(chat.id)
      } else {
        this.idsToRemove = this.idsToRemove.filter(item => item !== chat.id)
      }
    },
    filteredList() {
      this.chats = this.in,
      this.chats = this.chats.filter(chat => {
        return chat.otherUser.name.toLowerCase().includes(this.search.toLowerCase())
      })


    }
  }
};
</script>
