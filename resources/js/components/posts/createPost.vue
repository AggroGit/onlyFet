<template>
    <div class="container down-2">
      <div class="row justify-content-center">
        <div class="col-md-12">

          <form @submit.stop.prevent="post()">

            <!-- <div class="contieneInputPost"> -->
              <!-- <picture-input ></picture-input> -->
              <VueFileAgent
                ref="vueFileAgent"
                :theme="'grid'"
                :multiple="true"
                :deletable="true"
                :meta="true"
                :accept="'video/*,.zip'"
                :maxSize="'10MB'"
                :maxFiles="14"
                :helpText="'Choose images or zip files'"
                :errorText="{
                  type: 'Invalid file type. Only images or zip Allowed',
                  size: 'Files should not exceed 10MB in size',
                }"
                @select="filesSelected($event)"
                @beforedelete="onBeforeDelete($event)"
                @delete="fileDeleted($event)"
                v-model="fileRecords"
              ></VueFileAgent>
              <button :disabled="!fileRecordsForUpload.length" @click="uploadFiles()">
                Upload {{ fileRecordsForUpload.length }} files
              </button>
            <!-- </div> -->
            <br>
            <div class="form-group row down-2">
              <div class="col-md-12 contieneInput">
                  <label for="post" class="entrada detextarea" >{{$ml.get('post').post}}</label>
                  <textarea ref="content" @keyup="detectPeople()" v-model="form.content" rows="5"  name="post" class="form-control" required autocomplete="off" autofocus="true"></textarea>
              </div>
                <!-- <entradaText v-model="form.content" @change="detectPeople()" :rows="4" :label="$ml.get('post').post" :name="'name'" autocomplete="off" :type="'text'" :autofocus="true" :required="true"></entradaText> -->
            </div>



            <div v-if="this.recomending.using" class="contieneRecomendador fadeInOut aparecer">
              <div v-for="(recomendation) in this.recomending.list" @click="SelectRecomendation(recomendation)" :key="recomendation.id" class="Recomendation aparecer fadeInOut">
                <div class="contieneRecomendadorImg">
                  <img v-if="recomendation.image" :src="recomendation.image.sizes.VerySmall" alt="">
                  <img v-else src="/default.png" alt="">
                </div>
                <div class="">
                  <p>{{recomendation.name}}</p>
                </div>
              </div>
            </div>


            <div class="form-group row down-2">
              <div class="col-md-12 contieneInput">
                  <label for="hastags" class=" entrada labelHastags" >Hastags</label>
                  <input-tag  v-model="form.hastags" name="hastags"  class="form-control inuttags" required autocomplete="off" ></input-tag>
              </div>
            </div>

            <div class="row down-2" >
              <div class="col-md-12">
                <div v-if="this.error" class="alert alert-danger">
                  <strong>{{$ml.get('auth').prError}}</strong> {{$ml.get('post').error}}
                </div>
              </div>
            </div>


            <div class="form-group row ">
                <div class="col-md-12 offset-md-12">
                    <button type="submit" class="btn btn-primary boton">
                        {{$ml.get('post').publy}}
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
      recomending:{
        using:false,
        loading:true,
        list:[],


      },
      loading:false,
      error: false,
      form: {
        content: null,
        hastags:[],
        tags:[]

      }
    }
  },
  mounted() {
    console.log(window.name);
  },
  methods: {
    post() {
      this.loading = true
      this.recomending.using = true
      axios.post('/api/post/create', this.form,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
      // then
      .then(function (response) {
        if(response.data.rc == 1) {

        }
        if(response.data.rc == 2) {
          self.exists = true
          return true
        }
      })
      .catch(() => this.error = true)
      // finally
      .finally(() => this.loading = false)
    },
    detectPeople() {
      // cogemos la ultima palabra
      var s = this.form.content;
      var todo = s.split(" ");
      var palabra = todo[todo.length - 1]
      if(palabra.includes("@")) {
        this.recomending.using = true;
        this.lookFor(palabra)
        // alert(palabra)
      } else {
        this.recomending.using = false;
      }
    },
    lookFor(name) {
      // quitamos el @
      name = name.split("@")[1]
      this.recomending.loading = true;
      var self = this
      axios.post('/api/post/recomend', {
        name:name
      },
      {
         headers:{
            Authorization: `Bearer `+ self.$store.state.token
         }
       })
      // then
      .then(function (response) {
        if(response.data.rc == 1) {
          self.recomending.list = response.data.data
        }

      })
      // .catch(() => this.error = true)
      // finally
      .finally(() => self.recomending.loading = false)
    },
    SelectRecomendation(person) {
      var s = this.form.content;
      var todo = s.split(" ");
      var palabra = todo[todo.length - 1]
      var ret = this.form.content.replace(palabra,'@'+person.name+' ');
      this.form.content = ret
      this.recomending.using = false;
      this.$refs.content.focus()

    },











    // VIDEOS
    uploadFiles: function () {
        // Using the default uploader. You may use another uploader instead.
        this.$refs.vueFileAgent.upload('/api/video', this.uploadHeaders, this.fileRecordsForUpload);
        this.fileRecordsForUpload = [];
      },
      deleteUploadedFile: function (fileRecord) {
        // Using the default uploader. You may use another uploader instead.
        this.$refs.vueFileAgent.deleteUpload(this.uploadUrl, this.uploadHeaders, fileRecord);
      },
      filesSelected: function (fileRecordsNewlySelected) {
        console.log(fileRecordsNewlySelected);
        var validFileRecords = fileRecordsNewlySelected.filter((fileRecord) => !fileRecord.error);
        this.fileRecordsForUpload = this.fileRecordsForUpload.concat(validFileRecords);
      },
      onBeforeDelete: function (fileRecord) {
        var i = this.fileRecordsForUpload.indexOf(fileRecord);
        if (i !== -1) {
          this.fileRecordsForUpload.splice(i, 1);
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
