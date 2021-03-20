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

          <form v-bind:class="{ invisible: loading }" @submit.stop.prevent="post()" >

            <!-- <div class="contieneInputPost"> -->
              <!-- <picture-input ></picture-input> -->
              <VueFileAgent
                ref="vueFileAgent"
                :theme="'grid'"
                :multiple="true"
                :deletable="true"
                :meta="true"
                :accept="'video/*,.jpg,.png,.jpeg'"
                :maxSize="'100MB'"
                :maxFiles="4"
                :helpText="'Choose images or videos'"
                :errorText="{
                  type: 'Invalid file type. Only images or zip Allowed',
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
              <div class="col-md-12 contieneInput">
                  <label for="post" class="entrada detextarea" >{{$ml.get('post').post}}</label>
                  <textarea ref="content" @keyup="detectPeople()" required v-model="form.content" rows="5"  name="post" class="form-control"  autocomplete="off" autofocus="true"></textarea>
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
                  <p>{{recomendation.name}} - @{{recomendation.nickname}}</p>
                </div>
              </div>
            </div>



            <div class="form-group row down-2">
              <div class="col-md-12 contieneInput">
                  <label for="hastags" class=" entrada labelHastags" >Hastags</label>
                  <input-tag :before-adding="QuitHastag()" :add-tag-on-keys="validarTags()" v-model="form.hastags" name="hastags"  class="form-control inuttags" required autocomplete="off" ></input-tag>
              </div>
            </div>

            <div class="row down-2" >
              <div class="col-md-12">
                <div v-if="this.error" class="alert alert-danger">
                  <strong>{{$ml.get('auth').prError}}</strong> {{$ml.get('post').error}}
                </div>
              </div>
            </div>


            <div class="form-group row down-2">
              <div class="checkbox col-md-12">
                <label><input type="checkbox" v-model="programOpen" value=""> {{$ml.get('post').program}}</label>
              </div>
              <div v-if="this.programOpen" class="col-md-12 contieneInput aparecer">
                  <label for="datetime" class=" entrada labelHastags" >{{$ml.get('post').date_program}}</label>
                  <datetime type="datetime" v-model="program" name='datetime' class="theme-orange" ></datetime>
              </div>
            </div>


            <div class="form-group row down-2">
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
      <!-- <div v-if="this.loading" class="contienePantallaCompletaDark aparecer">
        <div class="container text-center contieneCargador">
          <div class="spinner-border cargador cargaBlanco" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
      </div> -->
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
        loading:false,
        list:[],

      },
      loading:false,
      error: false,
      program:null,
      programOpen:false,
      form: {
        files:[],
        content: "",
        hastags:[],
        tags:[],
        program_date: null

      }
    }
  },
  mounted() {
    console.log(window.name);
  },
  methods: {
    validarTags() {
      return [13,188,32]
    },
    QuitHastag() {
      // alert(tag)
    },
    post() {
      //
      var hastags = (this.form.hastags)
      console.log()
      // this.form.files.push(this.$refs.vueFileAgent.fileRecords[0].file)
      // console.log(this.fileRecords)
      // return true;
      this.loading = true
      this.recomending.using = true
      var formData = new FormData();
      if(this.programOpen !== false) {
        formData.append('publish_at', this.program);
      }
	    formData.append('media', this.form.files);
      formData.append('content',this.form.content);
      formData.append('hastags',hastags);
      var self = this;
      axios.post('/api/post/create', formData,
      {
         headers:{
            Authorization: `Bearer `+ this.$store.state.token
         }
       })
      // then
      .then(function (response)  {
        console.log(response)
        if(response.data.rc == 1) {
          self.uploadFiles(response.data.data.id)
        }
        if(response.data.rc == 2) {
          self.exists = true
          return true
        }
        if(response.data.rc == 13) {
          self.$router.push('/login')
        }
      })
      .catch(err => {
        self.error = true;
        console.log(err)
      })
      // finally
      .finally(() => this.loading = true)
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
      var ret = this.form.content.replace(palabra,'@'+person.nickname+' ');
      this.form.content = ret
      this.recomending.using = false;
      this.$refs.content.focus()

    },











    // VIDEOS
    uploadFiles: function (id) {
        console.log(this)
        console.log(id)
        var self = this
        // this.loading = true;
        this.$refs.vueFileAgent.upload('/api/post/'+id+'/upload',{
              Authorization: `Bearer `+ self.$store.state.token

         }, this.fileRecordsForUpload)
        .then(function (response) {
          console.log(response)
        })
        // .catch(() => alert('Error al subir Archivo'))

        // finally
        .finally(function (response) {
          self.fileRecordsForUpload = [];
          self.$router.push('/post/'+id)
          window.location.reload()

        })

      },
      deleteUploadedFile: function (fileRecord) {
        // Using the default uploader. You may use another uploader instead.
        this.$refs.vueFileAgent.deleteUpload(this.uploadUrl, this.uploadHeaders, fileRecord);
      },
      filesSelected: function (fileRecordsNewlySelected) {

        // console.log(fileRecordsNewlySelected);
        var validFileRecords = fileRecordsNewlySelected.filter((fileRecord) => !fileRecord.error);
        this.fileRecordsForUpload = this.fileRecordsForUpload.concat(validFileRecords);
        // this.uploadFiles();
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
