/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


import Vue from 'vue'
import Vuex from 'vuex'
import Echo from 'laravel-echo';





import VueRouter from 'vue-router'
import InputTag from 'vue-input-tag'
import VueCookies from 'vue-cookies'
import VeeValidate from 'vee-validate'
import TextHighlight from 'vue-text-highlight';
import { StripeCheckout } from 'vue-stripe'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import { ValidationProvider, extend } from 'vee-validate';
import PictureInput from 'vue-picture-input'
import VueCardFormat from 'vue-credit-card-validation';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import VueFileAgent from 'vue-file-agent';
import VueFileAgentStyles from 'vue-file-agent/dist/vue-file-agent.css';


// nuestras
import './ml.js' // idiomas


// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)
// router
Vue.use(VueRouter)
// storage content
Vue.use(Vuex)
// cookies
Vue.use(VueCookies)
// credit cards
Vue.use(VueCardFormat);
// Video
Vue.use(VueFileAgent);



// tags
Vue.component('input-tag', InputTag)
// resaltar en texto
Vue.component('text-highlight', TextHighlight);

Vue.component('ValidationProvider', ValidationProvider);

// COMPONENTS
Vue.component('example',  require('./components/ExampleComponent.vue').default);
//
Vue.component('app',      require('./components/app.vue').default);
// Vue.component('stripe',   require('vue-stripe-checkout').default);
// Vue.component('starr',    require('vue-star-rating').default);
// NATIVE
Vue.component('navBar',    require('./components/navBar.vue').default);
// avatar user
Vue.component('avatar',   require('./components/avatar.vue').default);
// Chat List
Vue.component('navBar',    require('./components/navBar.vue').default);
// Chat List
Vue.component('chatsList',    require('./components/chats/chatList.vue').default);
// Chat View
Vue.component('chatView',    require('./components/chats/chatView.vue').default);
// Chat View + Chat List
Vue.component('fullChatView',require('./components/chats/fullChatView.vue').default);
// lon
Vue.component('login',require('./components/auth/login.vue').default);
// lon
Vue.component('notifications',require('./components/auth/notifications.vue').default);
//
Vue.component('notification',require('./components/auth/notification.vue').default);
//
Vue.component('register',require('./components/auth/register.vue').default);
// entrada de texto con animación
Vue.component('entrada',require('./components/entrada.vue').default);
// textarea
Vue.component('entradaText',require('./components/entradatextarea.vue').default);

// perfil
Vue.component('profile',require('./components/auth/profile.vue').default);
// ditar perfil
Vue.component('editProfile',require('./components/auth/editProfile.vue').default);
//input image
Vue.component('picture-input',      require('vue-picture-input').default);
// stripe button for add account
Vue.component('stripe-express',      require('./components/stripe/addExpress.vue').default);
// stripe add visa
Vue.component('stripe-add-visa', require('./components/stripe/addVisa.vue').default);
// stripe credit cards
Vue.component('stripe-cards', require('./components/stripe/cards.vue').default);
// postear
Vue.component('postear',  require('./components/posts/createPost.vue').default);







/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 const routes = [
 {path: '/',              component: Vue.component('fullChatView')},
 // auth
 {path: '/login',         component: Vue.component('login')},
 {path: '/register',      component: Vue.component('register')},
 // user
 {path: '/profile',       component: Vue.component('profile')},
 {path: '/profile/edit',  component: Vue.component('editProfile')},
 // stripe
 {path: '/profile/cards',  component: Vue.component('stripe-cards')},
 // posts
 {path: '/post/create',   component: Vue.component('postear')},

 //
 {path: '/home',          component: Vue.component('fullChatView')},
 {path: '/chats',         component: Vue.component('chatsList')},
 {path: '/chats/:id',     component: Vue.component('chatView')},
 {path: '/full/chats',    component: Vue.component('fullChatView')},
 {path: '/full/chats/:id',component: Vue.component('fullChatView')},


]















 var router = new VueRouter({
  routes: routes,
  mode: 'history'
});

var t = Vue.$cookies.get('token');
const store = new Vuex.Store({
  state: {
    // user info
    auth:false,
    // token
    token: t,
    // conexion
    pusher: false,
    // auth channel
    authChannel: false,
    // app channel
    appchannel: false,
    // poner token en cookie
    putTokenInCookie: (token) => {
      Vue.$cookies.set('token',token);
    },
    quitTokenInCookie: () => {
      Vue.$cookies.remove('token');
    },
    // put a connection
    initConection: (token) => {
      window.Echo = new Echo({
        broadcaster: 'pusher',
          key:'289463930',
          wsHost: '127.0.0.1',
          wsPort: 6001,
          disableStats: true,
          // enable_client_messages:true,
          cluster:'eu',
          forceTLS:false,
          authEndpoint:'/api/broadcasting/auth',
          auth: {
            headers: {
              Authorization: `Bearer `+ token
            }
          }
      });
    },
    // init User Connection
    authChannelConnect:() => {
      console.log('conecting to '+appCode+'.User.'+user.id)
      window.authChannel = window.Echo.join(appCode+'.User.'+user.id);//+self.$store.state.auth.id);
      window.appchannel = window.Echo.join(appCode+'.App');//+self.$store.state.auth.id);

    },
    // know if user is in channel
    isUserInChannel:(userIn,channel) => {
      var f = false;
      channel.subscription.members.each(function(member) {
        if(member.id == userIn.id) {
          f=true;
        }
      });
      return f;
    },
    time: (time) => {
      var date = new Date(time);
      return date.getHours() +":"+date.getMinutes()
    }




  }
})





const app = new Vue({
    el: '#app',
    router: router,
    store:store,

});
