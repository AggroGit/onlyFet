
import Vue from 'vue'

import Vuex from 'vuex'
import Echo from 'laravel-echo';


import VueRouter from 'vue-router'
import InputTag from 'vue-input-tag'
import VueCookies from 'vue-cookies'
import VeeValidate from 'vee-validate'
import TextHighlight from 'vue-text-highlight';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import { ValidationProvider, extend } from 'vee-validate';

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'




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


// validator
// Vue.use(Vuelidate)
extend('secret', {
  validate: value => value === 'example',
  message: 'This is not the magic word'
});


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
