<template>
    <div class="container">
      <div v-if="this.loading" class="contienePantallaCompletaDark aparecer">
        <div class="container text-center contieneCargador">
          <div class="spinner-border cargador cargaBlanco" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
      </div>
      <!-- Formulario -->
      <form @submit.stop.prevent="register()">
        <div class="row justify-content-center mt-5">
          <div class="col-md-6">
            <div class="form-group">
              <h3>{{$ml.get('auth').becameInfluencer}}</h3>
            </div>
            <div class="form-group mt-2">
              <h5>{{$ml.get('auth').documents}} </h5>
              <span style=" white-space: pre-wrap; word-wrap: break-word;">{{$ml.get('auth').requisistsInfluencer}}
              </span>
            </div>
            <VueFileAgent
              ref="vueFileAgent"
              required
              :theme="'grid'"
              :multiple="true"
              :deletable="true"
              :meta="true"
              :accept="'.jpg,.png,.jpeg'"
              :maxSize="'500MB'"
              :maxFiles="6"
              :minFiles="3"
              :helpText="this.$ml.get('auth').documents"
              :errorText="{
                type: 'Invalid file type. Only images Allowed',
                size: 'Files should not exceed 100MB in size',
              }"
              @select="filesSelected($event)"
              @beforedelete="onBeforeDelete($event)"
              @delete="fileDeleted($event)"
              v-model="fileRecords"
            ></VueFileAgent>
            <!--  -->
            <div class="form-group row">
              <div  class="col-md-12 ">
                  <label for="datetime" class=" entrada labelHastags" >{{$ml.get('auth').birthday}}</label>
                  <datetime type="date" v-model="form.birthday" name='birthday' class="theme-orange" ></datetime>
              </div>
            </div>
            <div class="form-group row mt-4">
                <entrada v-model="form.email" :label="$ml.get('auth').email" :name="'email'" :autocomplete="'email'" :type="'email'" :autofocus="true" :required="true"></entrada>
            </div>
            <div class="form-group row">
              <entrada v-model="form.promotionalCode" :label="$ml.get('auth').promotionalCode" :name="'promotionalCode'" :type="'text'" autocomplete="none"></entrada>
            </div>
            <div class="form-group row">
              <entrada v-model="form.bank_account" :label="$ml.get('auth').bank_account" :name="'bank_account'" :type="'text'" autocomplete="none" :required="true"></entrada>
            </div>

            <div class="row down-2">
              <div class="col-md-12">
                <div v-if="this.errorMessage !== false" class="alert alert-danger">
                  <strong>{{$ml.get('auth').prError}}</strong> {{this.errorMessage}}
                </div>
              </div>
            </div>

            <div class="form-group row ">
                <div class="col-md-12 offset-md-12">
                    <button type="submit" class="btn btn-primary boton">
                        {{$ml.get('auth').becameInfluencer}}
                        <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>

          </div>
      </div>
    </form>


    </div>
</template>

<script>



export default {
  data() {
    return {
      auth: this.$store.state.auth,
      loading:false,
      fileRecords:[],
      fileRecordsForUpload:[],
      errorMessage: false,
      form: {
        files:[],
        email: null,
        promotionalCode: null,
        birthday: null,
        bank_account: null,
      }
    }
  },
  mounted() {
    this.init();
  },


  methods: {

    init() {
      if(this.auth.wantToBeInfluencer) {
        this.$router.push('/profile');
      }
      this.form.email         = this.auth.email
      this.form.birthday      = this.auth.birthday
      this.form.bank_account  = this.auth.bank_account

    },


    uploadFiles: function () {
      var self = this;
      // Using the default uploader. You may use another uploader instead.
      this.$refs.vueFileAgent.upload('/api/register/documents/upload',{
            Authorization: `Bearer `+ self.$store.state.token
       },this.fileRecordsForUpload)
       //
       .then(function (response) {
         self.fileRecordsForUpload = [];
         // self.$router.push('/profile')
          window.location.reload()
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

    register() {
      this.loading=true

      if(this.form.birthday == null || this.form.birthday == "") {
        alert(this.$ml.get('auth').birthday)
        this.loading=false
        return false
      }
      if(this.fileRecordsForUpload.length == 0) {
        alert(this.$ml.get('auth').documents)
        this.loading=false
        return false;
      }
      //
      this.loading = true
      var self = this;
      axios
      .post('/api/register/influencer',this.form,
      {
        headers:{
           Authorization: `Bearer `+ this.$store.state.token
        }
      })
      // then
      .then(function (response) {
        if(response.data.rc == 1)
          self.interpretate(response)

        else if(response.data.rc == 0)
          self.errorMessage = "Error"
        else
          self.errorMessage = "Error"
      })
      // finally
      .finally(function (response) {
        self.loading = false
      })

    },

    interpretate(response) {
      // si es correcto ponemos el token en cookie
      if(response.data.rc == 1) {
        // datos del usuario
        this.$store.state.auth = response.data.data
        // put user info globally
        window.user = response.data.data;
        //
        this.uploadFiles();
        //
      } else {
        if(response.data.rc == 2) {
          this.exists = true;
          return true;
        }
        this.error = true;
      }

    },

  }
};
</script>
