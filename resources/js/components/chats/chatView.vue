<template>
  <div class="">
    <!-- LOADER -->
    <div v-if="this.loading" class="container text-center contieneCargador">
      <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
    <!-- CEBEZERA -->
    <div v-if="!this.loading" class="col-md-12 ContieneCabezeraChat sombreado ">
      <div class="contenedorChatHead">
        <avatar :conection="true" :us="otherUser" ></avatar>
        <span class="mr-auto">{{otherUser.name}} </span>
        <b-dropdown id="dropdown-right" right text="Right align" variant="secondary" class="m-2">
          <template v-slot:button-content>
            <b-icon icon="three-dots-vertical" aria-hidden="true"></b-icon>
          </template>
          {{chatData}}
          <b-dropdown-item >pruebas</b-dropdown-item>

          <b-dropdown-item v-if="chatData.open" @click="block()">{{$ml.get('chat').block}}</b-dropdown-item>
          <b-dropdown-item v-if="chatData.open" @click="report()">{{$ml.get('chat').report}}</b-dropdown-item>
          <b-dropdown-item v-if="chatData.open == false && chatData.currentHaveBlocked" @click="unblock()">{{$ml.get('chat').unBlock}}</b-dropdown-item>
        </b-dropdown>
      </div>
    </div>

    <!-- MENSAJES -->
    <div v-if="!this.loading" class="ContieneMensajes contenedor" ref="messageDisplay">
      <div v-for="(message) in this.messages" :key="message.id" class="Mensaje" v-bind:class="ifisMe(message)">
        <div v-if="message.auction ==null" class="globoMensaje">
          <router-link  :to="chat+'/'+message.image.name" v-if="message.image" class="imageChat">
            <img :src="message.image.sizes.NotSmall" alt="">
          </router-link>
          <p>{{message.message}}</p>
        </div>
        <!-- Auction -->
        <div v-else class="AuctionInMessage sobreadoPlus">
          <div class="container">
            <div class="row">
              <div class="col-5">

                <img class="ImgAuctionMessage" v-if="message.auction.images.length >=1" :src="message.auction.images[0].sizes.NotSmall"  alt="">
              </div>
              <div class="col-7">
                <div class="row">
                  <div class="col-12">
                    <h5>{{$ml.get('auction').congratsYouWinAuction}}</h5>
                    <p>
                      {{$ml.get('auction').max}} : {{message.auction.final_price }}€
                    </p>
                      <p>
                        {{$ml.get('auction').detailSend}}
                      </p>


                  </div>
                </div>
              </div>
              </div>
          </div>
        </div>
        <small>{{message.fecha}}</small>
      </div>
    </div>


    <!-- INPUT DEL CHAT -->
    <div class="inputFixed">
      <div v-if="!this.loading" class=" col-md-12 ContieneEntradaChat">
        <b-form-input  autofocus v-model="newMessage" @keyup.enter="sendMessage()" autocomplete="off" id="input-small" class="col-xs-8 entradaTextoChat ContieneEntradaChatInput" :placeholder="$ml.get('chat').entrada"></b-form-input>
        <div class="ContieneIconosEnviar">
          <input type="file" ref="file" style="display: none" accept="image/x-png,image/gif,image/jpeg,image/jpg" @change="sendImage">
          <!-- <b-icon font-scale="1.7" style="color:#383d41;" icon="credit-card" aria-hidden="true" class="icon"></b-icon> -->
          <propina :chat_id="this.chat" :otherUser="this.otherUser" class="icon"></propina>
          <b-icon font-scale="1.7" @click="$refs.file.click()" style="color:#383d41;" icon="camera-fill" aria-hidden="true" class="icon"></b-icon>
          <b-icon @click="sendMessage()" font-scale="1.7" style="color: #F20505;" icon="arrow-up-right-circle-fill" aria-hidden="true" class="icon iconshadow"></b-icon>
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
      chatData: null,
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
    this.unsuscribe();
    console.log("YA SE HA IDO  ")
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
    unsuscribe() {
      console.log('quit suscribe')
      Echo.leave('Chat.'+this.chat)
      // this.channel.pusher.unsubscribe('presence-'+appCode+'.Chat.'+this.chat);
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
          self.chatData = response.data.data

          if(response.data.data.open == false) {
            self.messages = false;
          }
        }
        if(response.data.rc == 102) {
          alert(self.$ml.get('chat').notAvailable)
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
    unblock() {
        this.loading = true
        var self = this
        axios.post('/api/chat/'+self.chat+'/unblock', null,
        {
           headers:{
              Authorization: `Bearer `+ this.$store.state.token
           }
         })
         .then(function (response)  {
           console.log(response)
           if(response.data.rc == 1) {
             window.location.reload();

           } else {
             alert('error')
           }

         })
         .catch(err => {
           alert('Error')
         })
         // finally
         .finally(() => self.loading = false)

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
      // .listenForWhisper('typing', (e) => {
      //   alert('escribe  ')
      //   console.log(e);
      // });



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
    block() {
      if(confirm(this.$ml.get('chat').confBlock+this.otherUser.name+'?')) {
        this.loading = true
        var self = this
        axios.post('/api/chat/'+self.chat+'/block', null,
        {
           headers:{
              Authorization: `Bearer `+ this.$store.state.token
           }
         })
         .then(function (response)  {
           console.log(response)
           if(response.data.rc == 1) {
             window.location.reload();

           } else {
             alert('error')
           }

         })
         .catch(err => {
           alert('Error')
         })
         // finally
         .finally(() => self.loading = false)
      }
    }
  }
};
</script>
