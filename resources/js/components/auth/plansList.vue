<template>
    <div class="container contenedor">


      <!-- LOADER -->
      <div v-if="this.loading" class="container text-center contieneCargador aparecer">
        <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>






      <!-- LIST -->
      <div v-if="this.loading ==false && this.plans.length==0"  class="container text-center contienevacio down-5 aparecer">
        <img src="/iconos/empty-tag.png" alt="">
        <h5 class="down-2">{{$ml.get('auth').noSus}}</h5>
      </div>




      <!-- PLANES -->

      <div v-if="!this.loading" class="row down-2 aparecer">
        <div class="col-md-12">


          <div v-for="(data) in this.plans" :key="data.id"  class=" down-3 card post sobreadoPlus aparecer">
            <div class="cabecerapoST">
              <avatar :us="data.user"></avatar>
                <div class="contieneDetPost">
                  <p>{{data.user.name}}</p>
                  <!-- <p>{{data.created_at}}</p> -->
                  <!-- <p> se publicará en {{data.fecha}}</p> -->
              </div>
              <div class="opcionesPost">
                {{givePrice(data)}}
              </div>

              <div class="opcionesPost">
                  {{data.fecha}}
              </div>

            </div>

            <div class="card-body">


            </div>

            <div class="separador"></div>
            <div class="contieneOpcion">
              <a @click="Eliminar(data)" class="rojo" >Eliminar suscripción</a>
            </div>
            <div v-if="removing" class="contienePantallaCompletaDark aparecer">
              <div class="container text-center contieneCargador">
                <div class="spinner-border cargador cargaBlanco" style="width: 3rem; height: 3rem;" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
                <!-- <h3>Suscribiendo</h3> -->
              </div>
            </div>
          </div>




        </div>
      </div>

      <!-- NOTIFICACIONES -->

      <div class="row down-3">
        <div class="col-md-12">
          <h4 class="aparecer">Notificaciones</h4>
        </div>
        <div class="col-md-12">
          <router-link v-for="(notification) in this.notifications" :key="notification.id" :to="giveMeRoute(notification)" class=" down-3 card post sobreadoPlus aparecer noLink">
            <div class="cabecerapoST">
              <avatar :us="notification.from"></avatar>
                <div class="contieneDetPost">
                  <p>{{notification.title}}</p>
                  <p>{{notification.body}}</p>
                  <!-- <p>{{data.created_at}}</p> -->
                  <!-- <p> se publicará en {{data.fecha}}</p> -->
              </div>
              <div class="opcionesPost">
                  {{notification.fecha}}
              </div>
            </div>

            <div class="card-body">


            </div>
            <div class="contieneOpcion">
              <!-- <a @click="Eliminar(data)" class="rojo" >Eliminar suscripción</a> -->
            </div>
            <div v-if="removing" class="contienePantallaCompletaDark aparecer">
              <div class="container text-center contieneCargador">
                <div class="spinner-border cargador cargaBlanco" style="width: 3rem; height: 3rem;" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
                <!-- <h3>Suscribiendo</h3> -->
              </div>
            </div>
          </router-link>
        </div>
      </div>






    </div>
</template>

<script>



export default {
  data() {
    return {
      loading:false,
      plans:[],
      suscribing:false,
      removing:false,
      notifications:[],
    }
  },
  created() {
    this.getPlans()
    this.getNotifications()
  },
methods: {
getPlans() {

  var self = this
  axios.post('/api/auth/plans', null, {
    headers:{
       Authorization: `Bearer `+ this.$store.state.token
    }
  })
  .then(response => {
    //
    if(response.data.rc == 1) {
      self.plans  = response.data.data
    }
    if(response.data.rc == 13) {
      self.$router.push('/login')
    }

  })
  .finally(response => {
    self.loading = false;
  })

},
getNotifications() {
  var self = this
  axios.post('/api/auth/notifications', null, {
    headers:{
       Authorization: `Bearer `+ this.$store.state.token
    }
  })
  .then(response => {
    //
    if(response.data.rc == 1) {
      self.notifications = response.data.data
    }
    if(response.data.rc == 13) {
      self.$router.push('/login')
    }

  })
  .finally(response => {
    // self.loading = false;
  })
},
priceOf(name) {
  for (var i = 0; i < this.plans.length; i++) {
    if(this.plans[i].payForEvery == name) {
      return this.plans[i].price
    }

  }
},
givePrice(plan) {
  switch (plan.payForEvery) {
    case 'month1':
      return plan.price + " € "+ this.$ml.get('stripe').ev + ' 30 ' + this.$ml.get('stripe').days
      break;
      case 'month3':
        return plan.price + " € "+ this.$ml.get('stripe').ev + ' 3 ' + this.$ml.get('stripe').months
        break;
        case 'month6':
          return plan.price + " € "+ this.$ml.get('stripe').ev + ' 6 ' + this.$ml.get('stripe').months
          break;
          case 'month12':
            return plan.price + " € "+ this.$ml.get('stripe').ev + ' 12 ' + this.$ml.get('stripe').months
            break;
    default:
    return this.$ml.get('stripe').months

  }
},
Eliminar(plan) {
  if(this.removing) {
    return true;
  }
  if(!confirm(this.$ml.get('stripe').sureUnsuscribe)) {
    return true;
  }
  this.removing = true;
  var self = this
  axios.post('/api/auth/plans/'+plan.id+'/unsuscribe', null, {
    headers:{
       Authorization: `Bearer `+ this.$store.state.token
    }
  })
  .then(response => {
    //
    if(response.data.rc == 1) {
      window.location.reload();
    }
    if(response.data.rc == 13) {
      self.$router.push('/login')
    }

  })
  .finally(response => {
    self.loading = false;
    self.removing = false;
  })
},
giveMeRoute(noti) {
  if(noti.type == "chat") {
    return "/chats/"+noti.data
  }
  if(noti.type == "publication") {
    return "/post/"+noti.data+"/coments"
  }
  if(noti.type == "like") {
    return "/post/"+noti.data
  }
  if(noti.type == "propina") {
    return "/user/"+noti.data
  }
  return "";
}
}
};
</script>
