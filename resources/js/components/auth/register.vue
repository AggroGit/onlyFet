<template>
  <div class="container">
    <!-- Imagen logo -->


    <div class="row justify-content-center">
      <div class="col-md-4 logoLogin">
        <img class="img-fluid" src="/logo.png" alt="">
      </div>
    </div>

    <div v-if="this.temporal.status && this.temporal.success" class="row justify-content-center aparecer">
      <div class="col-md-8 text-center">
        <h3>Ya estás registrado en OnlyFet, esperamos verte pronto</h3>
      </div>
    </div>

    <!-- Formulario login -->
      <div v-if="!this.temporal.success" class="row justify-content-center">
          <div class="col-md-6">
              <div class="card">
                  <div class="card-body">
                      <form  @submit.stop.prevent="register()" >

                          <div class="form-group row">
                              <entrada v-model="form.name" :label="$ml.get('auth').name" :name="'name'" :autocomplete="'name'" :type="'text'" :autofocus="true" :required="true"></entrada>
                          </div>

                          <div class="form-group row">
                              <entrada v-model="form.email" :label="'Email'" :name="$ml.get('auth').email" :autocomplete="'email'" :type="'email'"  :required="true"></entrada>
                          </div>

                          <div class="form-group row">
                              <entrada v-model="form.password" :label="'Contraseña'" :name="$ml.get('auth').password" :type="'password'" autocomplete="new-password" :required="true"></entrada>
                          </div>


                          <div class="form-group row">
                              <entrada v-model="form.passwordRepeat" :label="$ml.get('auth').RepPassword" :name="'password_confirmation'" :type="'password'" autocomplete="new-password" :required="true"></entrada>
                          </div>

                          <label for="country">{{$ml.get('auth').country}} :</label>
                          <b-form-select v-model="form.country" name="country" :options="options" class=""></b-form-select>


                          <div class="form-group row down-2">
                              <div class="col-md-12">
                                  <div class="form-check">
                                      <input class="form-check-input" type="checkbox" required name="legal" id="legal" >
                                      <label class="form-check-label" for="legal">
                                        {{$ml.get('auth').legalTerms}}
                                      </label>
                                  </div>
                              </div>
                          </div>

                          <div class="form-group row">
                              <div class="col-md-12">
                                  <div class="form-check">
                                      <input v-model="form.influencer" class="form-check-input" type="checkbox" name="influencer" id="influencer" >
                                      <label class="form-check-label" for="influencer">
                                          {{$ml.get('auth').influencerQ}}
                                      </label>
                                  </div>
                              </div>
                          </div>


                            <div class="form-group row ">
                                <div class="col-md-12 offset-md-12">
                                    <button type="submit" class="btn btn-primary boton">
                                        {{$ml.get('auth').register}}
                                        <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>

                            </form>


                            <div  class="form-group row contieneSeparadorRRSS down-2">
                                <div class="separadorRRSS"></div>
                            </div>

                            <!-- FACEBOOK -->
                            <div class="form-group row ">
                                <a href="/login/facebook" class="col-md-12 offset-md-12">
                                    <button class="btn btn-primary boton facebook">
                                        {{$ml.get('auth').registerFacebook}}
                                        <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                </a>
                            </div>

                            <!-- Google -->
                            <div  class="form-group row ">
                                <a disabled href="/login/google" class="col-md-12 offset-md-12">
                                    <button class="btn btn-primary boton Google">
                                        {{$ml.get('auth').registerGoogle}}
                                        <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                </a>
                            </div>

                            <div  class="form-group row contieneSeparadorRRSS down-2">
                                <div class="separadorRRSS"></div>
                            </div>








                          <div v-if="!this.temporal.status" class="form-group row ">
                              <div class="col-md-12 offset-md-12">
                                 <router-link to="/login">
                                    <button  class="btn btn-primary btn-secondary boton">
                                        {{$ml.get('auth').changelogin}}
                                        <!-- <span v-if="this.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> -->
                                    </button>
                                </router-link>
                              </div>
                          </div>

                          <div class="row down-2">
                            <div class="col-md-12">
                              <div v-if="this.error" class="alert alert-danger">
                                <strong>{{$ml.get('auth').prError}}</strong> {{$ml.get('auth').error}}
                              </div>
                              <div v-if="this.exists" class="alert alert-danger">
                                <strong>{{$ml.get('auth').prError}}</strong> {{$ml.get('auth').exist}}
                              </div>
                            </div>
                          </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>

// import { MLBuilder } from 'vue-multilanguage'

export default {
  data(){
    return {
      loading:false,
      error:false,
      exists:false,
      temporal: {
        status:false,
        success:false
      },
      test:"",
      form: {
        email:null,
        password:"",
        passwordRepeat:"",
        name:null,
        influencer:false,
        country:""
      },
      options: [
        {text: 'Afghanistan', value: 'AF'},
          {text: 'Åland Islands', value: 'AX'},
          {text: 'Albania', value: 'AL'},
          {text: 'Algeria', value: 'DZ'},
          {text: 'American Samoa', value: 'AS'},
          {text: 'AndorrA', value: 'AD'},
          {text: 'Angola', value: 'AO'},
          {text: 'Anguilla', value: 'AI'},
          {text: 'Antarctica', value: 'AQ'},
          {text: 'Antigua and Barbuda', value: 'AG'},
          {text: 'Argentina', value: 'AR'},
          {text: 'Armenia', value: 'AM'},
          {text: 'Aruba', value: 'AW'},
          {text: 'Australia', value: 'AU'},
          {text: 'Austria', value: 'AT'},
          {text: 'Azerbaijan', value: 'AZ'},
          {text: 'Bahamas', value: 'BS'},
          {text: 'Bahrain', value: 'BH'},
          {text: 'Bangladesh', value: 'BD'},
          {text: 'Barbados', value: 'BB'},
          {text: 'Belarus', value: 'BY'},
          {text: 'Belgium', value: 'BE'},
          {text: 'Belize', value: 'BZ'},
          {text: 'Benin', value: 'BJ'},
          {text: 'Bermuda', value: 'BM'},
          {text: 'Bhutan', value: 'BT'},
          {text: 'Bolivia', value: 'BO'},
          {text: 'Bosnia and Herzegovina', value: 'BA'},
          {text: 'Botswana', value: 'BW'},
          {text: 'Bouvet Island', value: 'BV'},
          {text: 'Brazil', value: 'BR'},
          {text: 'British Indian Ocean Territory', value: 'IO'},
          {text: 'Brunei Darussalam', value: 'BN'},
          {text: 'Bulgaria', value: 'BG'},
          {text: 'Burkina Faso', value: 'BF'},
          {text: 'Burundi', value: 'BI'},
          {text: 'Cambodia', value: 'KH'},
          {text: 'Cameroon', value: 'CM'},
          {text: 'Canada', value: 'CA'},
          {text: 'Cape Verde', value: 'CV'},
          {text: 'Cayman Islands', value: 'KY'},
          {text: 'Central African Republic', value: 'CF'},
          {text: 'Chad', value: 'TD'},
          {text: 'Chile', value: 'CL'},
          {text: 'China', value: 'CN'},
          {text: 'Christmas Island', value: 'CX'},
          {text: 'Cocos (Keeling) Islands', value: 'CC'},
          {text: 'Colombia', value: 'CO'},
          {text: 'Comoros', value: 'KM'},
          {text: 'Congo', value: 'CG'},
          {text: 'Congo, The Democratic Republic of the', value: 'CD'},
          {text: 'Cook Islands', value: 'CK'},
          {text: 'Costa Rica', value: 'CR'},
          {text: 'Cote D\'Ivoire', value: 'CI'},
          {text: 'Croatia', value: 'HR'},
          {text: 'Cuba', value: 'CU'},
          {text: 'Cyprus', value: 'CY'},
          {text: 'Czech Republic', value: 'CZ'},
          {text: 'Denmark', value: 'DK'},
          {text: 'Djibouti', value: 'DJ'},
          {text: 'Dominica', value: 'DM'},
          {text: 'Dominican Republic', value: 'DO'},
          {text: 'Ecuador', value: 'EC'},
          {text: 'Egypt', value: 'EG'},
          {text: 'El Salvador', value: 'SV'},
          {text: 'Equatorial Guinea', value: 'GQ'},
          {text: 'Eritrea', value: 'ER'},
          {text: 'Estonia', value: 'EE'},
          {text: 'Ethiopia', value: 'ET'},
          {text: 'Falkland Islands (Malvinas)', value: 'FK'},
          {text: 'Faroe Islands', value: 'FO'},
          {text: 'Fiji', value: 'FJ'},
          {text: 'Finland', value: 'FI'},
          {text: 'France', value: 'FR'},
          {text: 'French Guiana', value: 'GF'},
          {text: 'French Polynesia', value: 'PF'},
          {text: 'French Southern Territories', value: 'TF'},
          {text: 'Gabon', value: 'GA'},
          {text: 'Gambia', value: 'GM'},
          {text: 'Georgia', value: 'GE'},
          {text: 'Germany', value: 'DE'},
          {text: 'Ghana', value: 'GH'},
          {text: 'Gibraltar', value: 'GI'},
          {text: 'Greece', value: 'GR'},
          {text: 'Greenland', value: 'GL'},
          {text: 'Grenada', value: 'GD'},
          {text: 'Guadeloupe', value: 'GP'},
          {text: 'Guam', value: 'GU'},
          {text: 'Guatemala', value: 'GT'},
          {text: 'Guernsey', value: 'GG'},
          {text: 'Guinea', value: 'GN'},
          {text: 'Guinea-Bissau', value: 'GW'},
          {text: 'Guyana', value: 'GY'},
          {text: 'Haiti', value: 'HT'},
          {text: 'Heard Island and Mcdonald Islands', value: 'HM'},
          {text: 'Holy See (Vatican City State)', value: 'VA'},
          {text: 'Honduras', value: 'HN'},
          {text: 'Hong Kong', value: 'HK'},
          {text: 'Hungary', value: 'HU'},
          {text: 'Iceland', value: 'IS'},
          {text: 'India', value: 'IN'},
          {text: 'Indonesia', value: 'ID'},
          {text: 'Iran, Islamic Republic Of', value: 'IR'},
          {text: 'Iraq', value: 'IQ'},
          {text: 'Ireland', value: 'IE'},
          {text: 'Isle of Man', value: 'IM'},
          {text: 'Israel', value: 'IL'},
          {text: 'Italy', value: 'IT'},
          {text: 'Jamaica', value: 'JM'},
          {text: 'Japan', value: 'JP'},
          {text: 'Jersey', value: 'JE'},
          {text: 'Jordan', value: 'JO'},
          {text: 'Kazakhstan', value: 'KZ'},
          {text: 'Kenya', value: 'KE'},
          {text: 'Kiribati', value: 'KI'},
          {text: 'Korea, Democratic People\'S Republic of', value: 'KP'},
          {text: 'Korea, Republic of', value: 'KR'},
          {text: 'Kuwait', value: 'KW'},
          {text: 'Kyrgyzstan', value: 'KG'},
          {text: 'Lao People\'S Democratic Republic', value: 'LA'},
          {text: 'Latvia', value: 'LV'},
          {text: 'Lebanon', value: 'LB'},
          {text: 'Lesotho', value: 'LS'},
          {text: 'Liberia', value: 'LR'},
          {text: 'Libyan Arab Jamahiriya', value: 'LY'},
          {text: 'Liechtenstein', value: 'LI'},
          {text: 'Lithuania', value: 'LT'},
          {text: 'Luxembourg', value: 'LU'},
          {text: 'Macao', value: 'MO'},
          {text: 'Macedonia, The Former Yugoslav Republic of', value: 'MK'},
          {text: 'Madagascar', value: 'MG'},
          {text: 'Malawi', value: 'MW'},
          {text: 'Malaysia', value: 'MY'},
          {text: 'Maldives', value: 'MV'},
          {text: 'Mali', value: 'ML'},
          {text: 'Malta', value: 'MT'},
          {text: 'Marshall Islands', value: 'MH'},
          {text: 'Martinique', value: 'MQ'},
          {text: 'Mauritania', value: 'MR'},
          {text: 'Mauritius', value: 'MU'},
          {text: 'Mayotte', value: 'YT'},
          {text: 'Mexico', value: 'MX'},
          {text: 'Micronesia, Federated States of', value: 'FM'},
          {text: 'Moldova, Republic of', value: 'MD'},
          {text: 'Monaco', value: 'MC'},
          {text: 'Mongolia', value: 'MN'},
          {text: 'Montserrat', value: 'MS'},
          {text: 'Morocco', value: 'MA'},
          {text: 'Mozambique', value: 'MZ'},
          {text: 'Myanmar', value: 'MM'},
          {text: 'Namibia', value: 'NA'},
          {text: 'Nauru', value: 'NR'},
          {text: 'Nepal', value: 'NP'},
          {text: 'Netherlands', value: 'NL'},
          {text: 'Netherlands Antilles', value: 'AN'},
          {text: 'New Caledonia', value: 'NC'},
          {text: 'New Zealand', value: 'NZ'},
          {text: 'Nicaragua', value: 'NI'},
          {text: 'Niger', value: 'NE'},
          {text: 'Nigeria', value: 'NG'},
          {text: 'Niue', value: 'NU'},
          {text: 'Norfolk Island', value: 'NF'},
          {text: 'Northern Mariana Islands', value: 'MP'},
          {text: 'Norway', value: 'NO'},
          {text: 'Oman', value: 'OM'},
          {text: 'Pakistan', value: 'PK'},
          {text: 'Palau', value: 'PW'},
          {text: 'Palestinian Territory, Occupied', value: 'PS'},
          {text: 'Panama', value: 'PA'},
          {text: 'Papua New Guinea', value: 'PG'},
          {text: 'Paraguay', value: 'PY'},
          {text: 'Peru', value: 'PE'},
          {text: 'Philippines', value: 'PH'},
          {text: 'Pitcairn', value: 'PN'},
          {text: 'Poland', value: 'PL'},
          {text: 'Portugal', value: 'PT'},
          {text: 'Puerto Rico', value: 'PR'},
          {text: 'Qatar', value: 'QA'},
          {text: 'Reunion', value: 'RE'},
          {text: 'Romania', value: 'RO'},
          {text: 'Russian Federation', value: 'RU'},
          {text: 'RWANDA', value: 'RW'},
          {text: 'Saint Helena', value: 'SH'},
          {text: 'Saint Kitts and Nevis', value: 'KN'},
          {text: 'Saint Lucia', value: 'LC'},
          {text: 'Saint Pierre and Miquelon', value: 'PM'},
          {text: 'Saint Vincent and the Grenadines', value: 'VC'},
          {text: 'Samoa', value: 'WS'},
          {text: 'San Marino', value: 'SM'},
          {text: 'Sao Tome and Principe', value: 'ST'},
          {text: 'Saudi Arabia', value: 'SA'},
          {text: 'Senegal', value: 'SN'},
          {text: 'Serbia and Montenegro', value: 'CS'},
          {text: 'Seychelles', value: 'SC'},
          {text: 'Sierra Leone', value: 'SL'},
          {text: 'Singapore', value: 'SG'},
          {text: 'Slovakia', value: 'SK'},
          {text: 'Slovenia', value: 'SI'},
          {text: 'Solomon Islands', value: 'SB'},
          {text: 'Somalia', value: 'SO'},
          {text: 'South Africa', value: 'ZA'},
          {text: 'South Georgia and the South Sandwich Islands', value: 'GS'},
          {text: 'Spain', value: 'ES'},
          {text: 'Sri Lanka', value: 'LK'},
          {text: 'Sudan', value: 'SD'},
          {text: 'Suritext', value: 'SR'},
          {text: 'Svalbard and Jan Mayen', value: 'SJ'},
          {text: 'Swaziland', value: 'SZ'},
          {text: 'Sweden', value: 'SE'},
          {text: 'Switzerland', value: 'CH'},
          {text: 'Syrian Arab Republic', value: 'SY'},
          {text: 'Taiwan, Province of China', value: 'TW'},
          {text: 'Tajikistan', value: 'TJ'},
          {text: 'Tanzania, United Republic of', value: 'TZ'},
          {text: 'Thailand', value: 'TH'},
          {text: 'Timor-Leste', value: 'TL'},
          {text: 'Togo', value: 'TG'},
          {text: 'Tokelau', value: 'TK'},
          {text: 'Tonga', value: 'TO'},
          {text: 'Trinidad and Tobago', value: 'TT'},
          {text: 'Tunisia', value: 'TN'},
          {text: 'Turkey', value: 'TR'},
          {text: 'Turkmenistan', value: 'TM'},
          {text: 'Turks and Caicos Islands', value: 'TC'},
          {text: 'Tuvalu', value: 'TV'},
          {text: 'Uganda', value: 'UG'},
          {text: 'Ukraine', value: 'UA'},
          {text: 'United Arab Emirates', value: 'AE'},
          {text: 'United Kingdom', value: 'GB'},
          {text: 'United States', value: 'US'},
          {text: 'United States Minor Outlying Islands', value: 'UM'},
          {text: 'Uruguay', value: 'UY'},
          {text: 'Uzbekistan', value: 'UZ'},
          {text: 'Vanuatu', value: 'VU'},
          {text: 'Venezuela', value: 'VE'},
          {text: 'Viet Nam', value: 'VN'},
          {text: 'Virgin Islands, British', value: 'VG'},
          {text: 'Virgin Islands, U.S.', value: 'VI'},
          {text: 'Wallis and Futuna', value: 'WF'},
          {text: 'Western Sahara', value: 'EH'},
          {text: 'Yemen', value: 'YE'},
          {text: 'Zambia', value: 'ZM'},
          {text: 'Zimbabwe', value: 'ZW'}
      ],
    }
  },

  mounted() {
    console.log(window.name);

    // si ya hay usuario lo echamos
    if(this.$store.state.auth) {
      this.$router.push('home')
    }
    this.getCountryCode();

  },
  methods: {

    getCountryCode() {
      var self = this
      $.get("http://ip-api.com/json", function(response) {
        self.form.country = response.countryCode
        console.log(response.countryCode);}, "jsonp");
    },

    checkForUp(event) {
      console.log(event)
    },
    register() {
      if(this.form.password !== this.form.passwordRepeat && this.form.password !== "" && this.form.password!==null) {
        alert('vaya, las contraseñas no coinciden')
        return true;
      }
      this.loading=true
      //
      var self = this;
      axios
      .post('/api/register',this.form)
      // then
      .then(function (response) {
        self.interpretate(response)
      })
      // finally
      .finally(() => this.loading = false)
    },

    interpretate(response) {
      // si es correcto ponemos el token en cookie
      if(response.data.rc == 1) {
        // datos del usuario
        this.$store.state.auth = response.data.data.user
        // cookie
        this.$store.state.putTokenInCookie(response.data.data.access_token)
        // in our
        this.$store.state.token = response.data.data.access_token
        // put user info globally
        window.user = response.data.data.user;
        // init conection
        this.$store.state.initConection(response.data.data.access_token);
        // start the user connection
        // this.$store.state.authChannelConnect();
        this.$store.state.authChannel = window.Echo.join(appCode+'.User.'+user.id);//+self.$store.state.auth.id);
        this.$store.state.appchannel = window.Echo.join(appCode+'.App');//+self.$store.state.auth.id);
        //
        this.$router.push('/')

      } else {
        if(response.data.rc == 2) {
          this.exists = true;
          return true;
        }
        this.error = true;
      }

    },
    interpretateTemporal(response) {
      // si es correcto ponemos el token en cookie
      if(response.data.rc == 1) {
        // datos del usuario
        this.temporal.success = true;


      } else {
        if(response.data.rc == 2) {
          this.exists = true;
          return true;
        } else {
          if(response.data.rc == 2) {
            this.exists = true;
            return true;
          }
          this.error = true;
        }
        this.error = true;
      }
    }






  }
};
</script>
