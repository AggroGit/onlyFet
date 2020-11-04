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
import { Datetime } from 'vue-datetime'
// You need a specific loader for CSS files
import 'vue-datetime/dist/vue-datetime.css'
import VueVideoPlayer from 'vue-video-player'
// require videojs style
import 'video.js/dist/video-js.css'
import TextHighlight from 'vue-text-highlight';
import { StripeCheckout } from 'vue-stripe'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import { ValidationProvider, extend } from 'vee-validate';
import PictureInput from 'vue-picture-input'
import VueCardFormat from 'vue-credit-card-validation';
import ToggleButton from 'vue-js-toggle-button'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import VueFileAgent from 'vue-file-agent';
import VueFileAgentStyles from 'vue-file-agent/dist/vue-file-agent.css';
import multiguard from 'vue-router-multiguard';


// nuestras
import './ml.js' // idiomas

//
Vue.use(Datetime)
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

// switch button
Vue.use(ToggleButton)
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
// post
Vue.component('post',require('./components/posts/post.vue').default);
// perfil
Vue.component('profile',require('./components/auth/profile.vue').default);
// otros
Vue.component('user',require('./components/auth/user.vue').default);
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
// carrousel
Vue.component('carousel',  require('./components/posts/carrousel.vue').default);
// coments
Vue.component('comments',  require('./components/posts/commentsList.vue').default);
//
Vue.component('home',  require('./components/home.vue').default);
//
Vue.component('news',  require('./components/posts/news.vue').default);
//
Vue.component('wall',  require('./components/posts/news.vue').default);
//
Vue.component('images',  require('./components/posts/images.vue').default);
//
Vue.component('videos',  require('./components/posts/videos.vue').default);
//
Vue.component('inicio',  require('./components/main.vue').default);
//
Vue.component('imageView',  require('./components/posts/image.vue').default);
//
Vue.component('propina',  require('./components/propina.vue').default);
//
Vue.component('suscriptions',  require('./components/auth/suscriptions.vue').default);
//
Vue.component('listPlans',  require('./components/auth/plansList.vue').default);
//
Vue.use(VueVideoPlayer)
// datetime
Vue.component('datetime', Datetime);




// MIDDLEWARES
const auth = function(to, from, next) {
  var t = Vue.$cookies.get('token')
    if(t == undefined || t== false) {
      try {
        redirect('/login');
      } catch (e) {
        window.location.href = "/login"
      } finally {

      }

    } else {
      next()
    }
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 const routes = [
 {path: '/',              component: Vue.component('inicio')},
 // auth
 {path: '/login',         component: Vue.component('login')},
 {path: '/register',      component: Vue.component('register')},
 // user
 {path: '/profile',       component: Vue.component('profile'),beforeEnter: multiguard([auth])},
 {path: '/profile/edit',  component: Vue.component('editProfile'),beforeEnter: multiguard([auth])},
 {path: '/profile/suscriptions',  component: Vue.component('suscriptions'),beforeEnter: multiguard([auth])},
 {path: '/:post_id/image/:name',  component: Vue.component('imageView'),beforeEnter: multiguard([auth])},
 {path: '/user/:nickname/',      component: Vue.component('user'),
 children: [
   {
     path:'',
     component: Vue.component('wall'),
     name:'wall',
   },
   {
     path:'pics',
     component: Vue.component('images'),
     name:'pics',
     beforeEnter: multiguard([auth])
   },
   {
     path:'videos',
     component: Vue.component('videos'),
     name:'videos',
     beforeEnter: multiguard([auth])
   },
   {
     path:'wall',
     name:'wall',
     component: Vue.component('wall'),
   }
 ]

},
 // stripe
 {path: '/profile/cards',  component: Vue.component('stripe-cards'),beforeEnter: multiguard([auth])},
 // posts
 {path: '/post/create',           component: Vue.component('postear'),beforeEnter: multiguard([auth])},
 {path: '/post/test',             component: Vue.component('carousel'),beforeEnter: multiguard([auth])},
 {path: '/post/:post_id',         component: Vue.component('post'),beforeEnter: multiguard([auth])},
 {path: '/post/:post_id/coments', component: Vue.component('comments')},

 // home
 {  path: '/novedades',component: Vue.component('home'),
      children: [
        {
          path:'suscriptions',
          beforeEnter: multiguard([auth]),
          component: Vue.component('listPlans'),
          name:'suscriptions'
        },
        {
          path:'news',
          beforeEnter: multiguard([auth]),
          component: Vue.component('news'),
          name:'news'
        },
        {
          path:'',
          name:'news',
          beforeEnter: multiguard([auth]),
          component: Vue.component('news'),
        }
      ]

},
 //
 {path: '/chats',         component: Vue.component('chatsList'),beforeEnter: multiguard([auth])},
 {path: '/chats/:id',     component: Vue.component('chatView'),beforeEnter: multiguard([auth])},
 // {path: '/full/chats',    component: Vue.component('fullChatView'),beforeEnter: multiguard([auth])},
 // {path: '/full/chats/:id',component: Vue.component('fullChatView'),beforeEnter: multiguard([auth])},


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
    // pantalla completa
    entero:false,
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
          key:289463930,
          wsHost: "82.223.216.96",
          wsPort: 6002,
          disableStats: true,
           enabledTransports: ['ws'],
           encrypted:false,
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
