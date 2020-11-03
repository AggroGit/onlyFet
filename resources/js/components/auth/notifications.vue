<template>
  <div class="ContieneNotificaciones">
    <notification v-for="(notification) in this.notifications" :data="notification" :key="notification.id" class="notificationn" >
  </notification>
</div>
</template>

<script>



export default {
  data() {
    return {
      notifications:[

      ]
    }
  },
  mounted() {
    this.connectToChannel()
  },
  methods: {
    connectToChannel() {
      console.log(this.$store.state.authChannel)
      this.$store.state.authChannel
      .listen('NotificationEvent', (e) => {
          console.log(e)
          this.notifications.push(e)

      })
      .here((users) => {

      })
      .joining((user) => {

      })
      .leaving((user) => {

      })

    }
  }
};
</script>
