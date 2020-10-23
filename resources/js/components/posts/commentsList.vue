<template>
  <div class="container">
      <div class="row justify-content-center down-3">
        <div v-if="this.loading" class="container text-center contieneCargador">
          <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
        <div class="col-md-12">
          <h5 v-if="this.comments.length !== 0" class="text-right">{{this.comments.length}} comentarios</h5>
        </div>

        <div v-if="!this.loading" class="col-md-8">
          <div v-if="this.comments.length == 0" class="Empty text-center">
            <img src="/iconos/empty-tag.png" alt="">
            <h5>{{$ml.get('post').noComments}}</h5>
          </div>
          <b-list-group class="aparecer">
              <b-list-group-item v-for="(comment) in this.comments" :key="comment.id"  class="flex-column align-items-start cabecerapoST no-border">
                <div class="cabecerapoST">
                  <avatar :conection="true" :us="comment.user"></avatar>
                  <div class="contieneDetPost" to="/profile">
                    <p>{{comment.user.name}}</p>
                    <p class="grisaceo">{{comment.fecha}}</p>
                </div>
                </div>
                <div class="comment down-1">
                  <p>{{comment.comment}}</p>
                </div>
              </b-list-group-item>
          </b-list-group>
        </div>
      </div>


      <div class="row justify-content-center down-3">
          <div class="col-md-8 text-center">
              <textarea @keyup.enter="Comentar()"  v-if="comenting" ref="content" v-model="comment.content" rows="5"  name="post" class="form-control aparecer entradaComment" required autocomplete="off" autofocus="true"></textarea>
                <button @click="Comentar()" class="btn btn-primary boton botoncoment">
                    <b-icon style="color: #ffffff;" class="left" icon="chat-left" font-scale="1.2"></b-icon>
                    {{$ml.get('post').comment}}
                    <span v-if="this.comment.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </button>
          </div>
        </div>





    </div>
</template>

<script>



export default {
  data() {
    return {
      loading:true,
      id:null,
      comments: null,
      comenting:false,
      comment: {
        content:"",
        loading:false
      }
    }
  },
  created() {
    this.id = this.$route.params.post_id;
    this.getComments();
  },
  methods: {
    getComments() {
      axios.post('/api/post/'+this.id+'/comments', null, {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      .then(response => {
        if(response.data.rc == 1) {
          console.log(response)
          this.comments = response.data.data;
        }
      })
      .finally(response => {
        this.loading = false;
      })
    },
    Comentar(){
      if(this.comenting && this.comment.content !== "" && this.comment.loading==false) {
        this.comment.loading=true
        var self = this
        axios.post('/api/post/'+this.id+'/comment', {
          comment:self.comment.content
        },
        {
           headers:{
              Authorization: `Bearer `+ this.$store.state.token
           }
         })
        // then
        .then(function (response)  {
          console.log(response)
          if(response.data.rc == 1) {
            var d = response.data.data
            d.user = self.$store.state.auth
            self.comments.push(response.data.data)
          }
          else if(response.data.rc == 13) {
            self.$router.push('/login')
          }
          else {
            alert('error desconocido')
          }
        })
        .catch(err => {
          self.error = true;
          console.log(err)
        })
        // finally
        .finally(() => {
          self.comment.loading = false
          self.comment.content=""
          self.comenting = false
        })


      }else if(this.comenting) {
        this.comenting = false
      } else {
        this.comenting = true
      }
    }
  }
}

</script>
