<template>
    <div class="container down-2">
      <div class="row justify-content-center contenedor">
        <div class="col-md-12">
          <div v-if="this.loading" class="container text-center contieneCargador aparecer">
            <div class="spinner-border cargador" style="width: 3rem; height: 3rem;" role="status">
              <span class="sr-only">Loading...</span>
            </div>
            <h3 class="down-4">{{$ml.get('post').posting}}</h3>
          </div>

          <form v-bind:class="{ invisible: loading }" @submit.stop.prevent="createAuction()" >
            <h1>{{this.$ml.get('auction').createTitle}}</h1>
            <br>
            <!-- <div class="contieneInputPost"> -->
              <!-- <picture-input ></picture-input> -->
              <VueFileAgent
                ref="vueFileAgent"
                required
                :theme="'grid'"
                :multiple="true"
                :deletable="true"
                :meta="true"
                :accept="'.jpg,.png,.jpeg'"
                :maxSize="'100MB'"
                :maxFiles="6"
                :helpText="this.$ml.get('auction').media"
                :errorText="{
                  type: 'Invalid file type. Only images Allowed',
                  size: 'Files should not exceed 100MB in size',
                }"
                @select="filesSelected($event)"
                @beforedelete="onBeforeDelete($event)"
                @delete="fileDeleted($event)"
                v-model="fileRecords"
              ></VueFileAgent>
              <!-- <button :disabled="!fileRecordsForUpload.length" @click="uploadFiles()">
                Upload {{ fileRecordsForUpload.length }} files
              </button> -->
            <!-- </div> -->
            <br>
            <div class="form-group row down-2">


                <entrada v-model="form.title" :label="$ml.get('auction').title" :name="'title'" :type="'text'" :autofocus="true" autocomplete="off" :required="true"></entrada>

                <entrada v-model="form.price" :min="1" :label="$ml.get('auction').initial_price" :name="'price'" :type="'number'" autocomplete="off" :required="true"></entrada>


              <div class="col-md-12 contieneInput">
                  <label for="post" class="entrada detextarea" >{{$ml.get('auction').description}}</label>
                  <textarea ref="content" required v-model="form.description" rows="5"  name="post" class="form-control"  autocomplete="off" autofocus="true"></textarea>
              </div>

              <div  class="col-md-12 contieneInput">
                  <label for="datetime" class=" entrada labelHastags" >{{$ml.get('auction').finish_at}}</label>
                  <datetime type="datetime" v-model="form.finish_at" name='datetime' class="theme-orange" ></datetime>
              </div>


                <!-- <entradaText v-model="form.content" @change="detectPeople()" :rows="4" :label="$ml.get('post').post" :name="'name'" autocomplete="off" :type="'text'" :autofocus="true" :required="true"></entradaText> -->
            </div>






            <div class="row down-2" >
              <div class="col-md-12">
                <div v-if="this.error" class="alert alert-danger">
                  <strong>{{$ml.get('auth').prError}}</strong> {{this.errorMessage}}
                </div>
              </div>
            </div>




            <div class="form-group row down-2">
                <div class="col-md-12 offset-md-12">
                    <button type="submit" class="btn btn-primary boton">
                        {{$ml.get('auction').createAuction}}
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
  data() {
    return {
      fileRecords:[],
      fileRecordsForUpload:[],
      loading:false,
      error: false,
      errorMessage: "Error",
      form: {
        files:[],
        content: "",
        title:"",
        finish_at:"",
        price:null,
      },

      fileRecords: [],
      uploadUrl: '/api/auction/upload',
      uploadHeaders: { 'X-Test-Header': 'vue-file-agent' },
      fileRecordsForUpload: [], // maintain an upload queue



    }
  },
  mounted() {
    console.log(window.name);
  },
  methods: {

    createAuction() {
      console.log(this.form.media)
      this.loading=true
      //
      var self = this;
      axios.post('/api/auction/create', this.form, {
        headers:{
           Authorization: `Bearer `+ self.$store.state.token
        }
      })
      // then
      .then(function (response) {
        if(response.data.rc == 1) {
          var auction_id = response.data.data.id
          if(self.fileRecordsForUpload.length == 0) {
            self.$router.push('/auction/'+auction_id)
          } else {
            self.uploadFiles(auction_id);
          }
        }

        if(response.data.rc == 1601) {
          self.error = true
          self.errorMessage = self.$ml.get('auction').onlyOne
        }
        if(response.data.rc == 4) {
          self.error = true
          self.errorMessage = self.$ml.get('auction').onlyInfluencer
        }

      })
      .catch(err => {
        alert('Server Error')
        console.log('error->')
        console.log(err)
      })
      // finally
      .finally(() => this.loading = false, self.forget = false)

    },








          uploadFiles: function (auction_id) {
            var self = this;
            // Using the default uploader. You may use another uploader instead.
            this.$refs.vueFileAgent.upload('/api/auction/'+auction_id+'/upload',{
                  Authorization: `Bearer `+ self.$store.state.token
             },this.fileRecordsForUpload)
             //
             .then(function (response) {

               self.fileRecordsForUpload = [];
               self.$router.push('/auction/'+auction_id)
             })
             //
             .catch(err => {
               console.log(err)
               self.error = true;
             })


            this.fileRecordsForUpload = [];
          },
          deleteUploadedFile: function (fileRecord) {
            console.log(fileRecord)
            // Using the default uploader. You may use another uploader instead.
            this.$refs.vueFileAgent.deleteUpload(this.uploadUrl, this.uploadHeaders, fileRecord);
          },
          filesSelected: function (fileRecordsNewlySelected) {
            var validFileRecords = fileRecordsNewlySelected.filter((fileRecord) => !fileRecord.error);
            this.fileRecordsForUpload = this.fileRecordsForUpload.concat(validFileRecords);
            // this.uploadFiles();
          },
          onBeforeDelete: function (fileRecord) {
            console.log('antes')
            console.log(fileRecord)
            console.log('despues')
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
  }
};
</script>
