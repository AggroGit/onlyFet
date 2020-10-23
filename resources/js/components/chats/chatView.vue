<template>
  <div class="">
    <!-- LOADER -->
    <div v-if="this.loading" class="container text-center contieneCargador">
      <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
    <!-- CEBEZERA -->
    <div v-if="!this.loading" class="col-md-12 ContieneCabezeraChat sombreado container">
      <avatar :conection="true" :us="otherUser" ></avatar>
      <span class="mr-auto">{{otherUser.name}} </span>

      <b-dropdown id="dropdown-right" right text="Right align" variant="primary" class="m-2">
        <template v-slot:button-content>
          <b-icon icon="three-dots-vertical" aria-hidden="true"></b-icon>
        </template>
        <b-dropdown-item href="#">Bloquear</b-dropdown-item>
        <b-dropdown-item href="#">Reportar</b-dropdown-item>
      </b-dropdown>
    </div>

    <!-- MENSAJES -->
    <div v-if="!this.loading" class="ContieneMensajes" ref="messageDisplay">

      <div v-for="(message) in this.messages" :key="message.id" class="Mensaje" v-bind:class="ifisMe(message)">
        <div class="globoMensaje">
          <div v-if="message.image" class="imageChat">
            <img :src="message.image.sizes.NotSmall" alt="">
          </div>
          <p>{{message.message}}</p>
        </div>


        <small>{{$store.state.time(message.created_at)}}</small>
      </div>

      <!-- <div class="containsTyping">
        <div></div>
        <div></div>
        <div></div>
      </div> -->

    </div>


    <!-- INPUT DEL CHAT -->
    <div class="inputFixed">
      <div v-if="!this.loading" class=" col-md-12 ContieneEntradaChat">
        <b-form-input autofocus v-model="newMessage" @keyup.enter="sendMessage()" @keyup ="sayImWraiting()" autocomplete="off" id="input-small" class="col-xs-8 entradaTextoChat" :placeholder="$ml.get('chat').entrada"></b-form-input>
        <div class="ContieneIconosEnviar">
          <input type="file" ref="file" style="display: none" accept="image/x-png,image/gif,image/jpeg,image/jpg" @change="sendImage">
          <b-icon font-scale="1.7" style="color:#383d41;" icon="credit-card" aria-hidden="true" class="icon"></b-icon>
          <b-icon font-scale="1.7" @click="$refs.file.click()" style="color:#383d41;" icon="camera-fill" aria-hidden="true" class="icon"></b-icon>
          <b-icon @onclick="sendMessage()" font-scale="1.7" style="color: #20F0C8;" icon="arrow-up-right-circle-fill" aria-hidden="true" class="icon iconshadow"></b-icon>
        </div>
      </div>
    </div>

  </div>
</template>

<script>

import { MLBuilder } from 'vue-multilanguage'

export default {
  // valores que entran
  props:['chat_id'],
  // valores que inicializamos
  data() {
    return {
      chat: null,
      loading:true,
      messages:[],
      newMessage:"",
      channel:false,
      otherUser:false,
    }

  },
  created() {
    console.log('CREATED')
    this.checkChatId();
    this.initChat();
    this.initChannel();
    this.channel.pusher.subscribe('presence-'+appCode+'.Chat.'+this.chat);



  },
  mounted() {
    console.log('MPUNTED')
  },
  beforeRouteLeave (to, from, next) {
    console.log("ADIOS  ")
    Echo.leave('97.Chat.1')
    this.channel.pusher.unsubscribe('presence-'+appCode+'.Chat.'+this.chat);
    // alert('desc')
    next()
  },
  computed: {
    mlchat () {
      return new MLBuilder('chat')
    }
  },
  methods: {
    alertar() {
      alert('hola')
    },

    // check if the chat comes from url or selected
    checkChatId() {
      if(this.chat_id === undefined) {
        this.chat = this.$route.params.id
      }
      else {
        this.chat = this.chat_id
      }

    },
    ScrollBottom(){
      var messagewindow = this.$refs.messageDisplay;
      messagewindow.scrollTop  = messagewindow.scrollHeight+300;
    },
    // prepara lo necesario del chat
    initChat() {
      var self = this;
      axios.post('/api/chat/'+this.chat+'/messages', null, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(function (response) {
        if(response.data.rc == 1) {
          console.log(response)
          self.messages = response.data.data.messages.data.slice().reverse()
          self.otherUser = response.data.data.otherUser

        }
      })
      .catch(function (error) {
        console.log(error);
      })
      .then(function () {
        self.loading = false;
        setTimeout(function() {
             self.ScrollBottom();
        }, 100);
      });
    },
    // if message its from me or not
    ifisMe(message) {
      if(message.user.id == user.id)
      return "fromMe"
    },

    sendMessage() {
      if(this.newMessage !== null) {
        axios.post('/api/chat/'+this.chat+'/send', {
          message: this.newMessage
        },
        {
          headers:{
             Authorization: `Bearer `+ this.$store.state.token
          }
        })
        this.newMessage = ""


      }

    },
    initChannel() {
      console.log('conectado a'+appCode+'.Chat.'+this.chat)
      this.channel = Echo.join(appCode+'.Chat.'+this.chat)
      .listen('MessageEvent', (e) => {
          console.log(e)
          // console.log(this.messages)
          this.messages.push(e.message)
          var self = this
          setTimeout(function() {
               self.ScrollBottom();
          }, 100);
      })
      .here((users) => {
          console.log('here')
          console.log(users)
      })
      .joining((user) => {
          console.log('joining')
          console.log(user.name);
      })
      .leaving((user) => {
          console.log('leaving')
          console.log(user);
      })
      .listenForWhisper('typing', (e) => {
        alert('escribe  ')
        console.log(e);
      });



    },
    sayImWraiting() {
      this.channel.whisper('typing', {
        user_id: user.id
      });
    },

    sendImage(event) {
      let formData = new FormData(); // instantiate it
      // suppose you have your file ready
      formData.set('image', event.target.files[0]);
      // add some data you collected from the input fields
      formData.set('message', "📸 "+this.newMessage);

      axios.post('/api/chat/'+this.chat+'/send', formData,
      {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(response => {
        if(response.data.rc == 0) {
          alert(response.data.data.image)
        }
      })
      this.newMessage = ""
    },
  }
};
</script>
