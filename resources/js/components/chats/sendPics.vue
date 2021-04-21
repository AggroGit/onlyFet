<template>
  <div>
    <!-- El icono -->
    <b-icon font-scale="1.7"  style="color:#383d41;" icon="camera-fill" aria-hidden="true" @click="cerrar()" class="icon"></b-icon>
    <!-- La parte abierta -->
    <div v-if="this.open" class="contienePantallaCompletaDark | aparecer" @click="cerrar()">
      <div class="sendPics | contenedor" @click.stop="delete_clicked">
        <form  @submit.stop.prevent="send()" >
          <h4>{{this.$ml.get('chat').sendImage}}</h4>
          <VueFileAgent
            ref="vueFileAgent"
            required
            :theme="'list'"
            :multiple="true"
            :deletable="true"
            :meta="true"
            :accept="'video/*,.jpg,.png,.jpeg'"
            :maxSize="'500MB'"
            :maxFiles="6"
            :helpText="this.$ml.get('chat').selectMedia"
            :errorText="{
              type: 'Invalid file type.',
              size: 'Files should not exceed 500MB in size',
            }"
            @select="filesSelected($event)"
            @beforedelete="onBeforeDelete($event)"
            @delete="fileDeleted($event)"
            v-model="fileRecords"
          ></VueFileAgent>

          <div v-if="this.$store.state.auth.influencer && this.massive==false" class="form-group mt-4">
            <h5 class="form-check-label" for="forPay">
              {{this.$ml.get('chat').privateMessage}}
            </h5>

            <div class="custom-control custom-switch mt-2">
              <input type="checkbox" class="custom-control-input" v-model="form.forPay" id="forPay">
              <label v-if="!this.form.forPay" class="custom-control-label" for="forPay"></label>
              <label v-else class="custom-control-label" for="forPay">{{this.$ml.get('chat').privateMessage}}</label>
            </div>

            <div v-if="this.form.forPay" class="form-group row mt-3">
              <entrada v-model="form.price" :label="$ml.get('chat').priceMessage+' €'" :name="'price1'" :autocomplete="'none'" :inputmode="'numeric'" :type="'number'" :min='1' :max="'400'" :step="'1'" :autofocus="true" :required="true"></entrada>
            </div>




          </div>

          <!-- Boton -->
          <div class="form-group row ">
              <div class="col-md-12 offset-md-12">
                  <button type="submit" class="btn btn-primary boton">
                      {{$ml.get('propina').send}}
                      <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  </button>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>

</template>

<script>



export default {
  props: {
      chat: {
        default:false
      },
      massive: {
        default: false
      }
  },
  data() {
    return {
      loading: false,
      open:false,
      isPay:false,
      fileRecords:[],
      fileRecordsForUpload:[],
      token: null,
      form: {
        forPay:false,
        price: 1
      },
      url:"/api/chat/massive"
    }
  },
  methods: {
    createTokenAndUrl() {
      this.token =  Math.random().toString(36).substr(2) + Math.random().toString(36).substr(2);
      if(this.chat !== false) {
        this.url = '/api/chat/'+this.chat.id+'/media/'+this.token+'/create'
      } else {
        this.url = '/api/chat/massive/'+this.token
      }


    },
    cerrar() {
      this.open = !this.open
      this.createTokenAndUrl()
      this.fileRecords=[],
      this.fileRecordsForUpload=[],
      this.form = {
        forPay:false,
        price:1,
      }

    },
    uploadFiles: function () {
      var self = this;
      // Using the default uploader. You may use another uploader instead.
      this.$refs.vueFileAgent.upload('/api/chat/media/'+this.token,{
            Authorization: `Bearer `+ self.$store.state.token
       },this.fileRecordsForUpload)
       //
       .then(function (response) {
         self.fileRecordsForUpload = [];
         self.fileRecords = []
         // self.$router.push('/profile')
         self.mediaToMessage();
       })
       //
       .catch(err => {
         console.log(err)
         self.error = true;
       })
      this.fileRecordsForUpload = [];
    },


    deleteUploadedFile: function (fileRecord) {
      // Using the default uploader. You may use another uploader instead.
      this.$refs.vueFileAgent.deleteUpload(this.uploadUrl, this.uploadHeaders, fileRecord);
    },


    filesSelected: function (fileRecordsNewlySelected) {
      var validFileRecords = fileRecordsNewlySelected.filter((fileRecord) => !fileRecord.error);
      this.fileRecordsForUpload = this.fileRecordsForUpload.concat(validFileRecords);
      // this.uploadFiles();
    },


    onBeforeDelete: function (fileRecord) {

      var i = this.fileRecordsForUpload.indexOf(fileRecord);
      if (i !== -1) {
      // queued file, not yet uploaded. Just remove from the arrays
        this.fileRecordsForUpload.splice(i, 1);
        var k = this.fileRecords.indexOf(fileRecord);
        if (k !== -1) this.fileRecords.splice(k, 1);
      } else {
        if (confirm('Are you sure you want to delete?')) {
          this.$refs.vueFileAgent.deleteFileRecord(fileRecord); // will trigger 'delete' event
        }
      }
    },


    fileDeleted: function (fileRecord) {
      var i = this.fileRecordsForUpload.indexOf(fileRecord);
      if (i !== -1) {
        this.fileRecordsForUpload.splice(i, 1);
      } else {
        this.deleteUploadedFile(fileRecord);
      }
    },
    delete_clicked() {
      // e
    },
    send() {
      if(this.loading || this.fileRecords.length == 0) {
        return true;
      }
      this.loading = true;
      this.uploadFiles()
    },
    // ata el mensaje con las imágenes
    mediaToMessage() {
        var self = this
        axios.post(this.url, this.form,
        {
           headers:{
              Authorization: `Bearer `+ this.$store.state.token
           }
         })
        // then
        .then(function (response)  {
          if(response.data.rc == 1) {
            self.cerrar();
          }
        })
        .catch(err => {
          self.error = true;
          alert('Error')
        })
        // finally
        .finally(() => {
          self.loading = false
          if(self.massive)
            alert(self.$ml.get('chat').success)
        })

    }
  }
};
</script>
