<template>
  <div v-if="state == 'login'" class='login-form absolute-wh-center'>
    <div class="branding text-center">
      <v-icon>mdi-vuejs</v-icon>
      <h3 class='text-center pb-2'>SaxoTrading</h3>
    </div>

    <b-form @submit="onSubmit" class='pt-4'>
      <div class='small-text-dark w-100 text-center small pb-4'>
        Don't have an account? <a href="/signup" class='font-weight-bold'>Register</a>
      </div>

      <b-form-group id="email-input-group">
        <b-form-input
        size="lg"
        id="email-input"
        v-model="form.email"
        type="email"
        placeholder="Your e-mail goes here..."
        maxlength="60"
        @input="onMod('email')"
        :state="emailFilter"
        aria-describedby="email-feedback"
        ></b-form-input>

        <b-form-invalid-feedback id="email-feedback">
          This is not valid email address.
        </b-form-invalid-feedback>
      </b-form-group>

      <b-form-group id="password-input-group">
        <b-form-input
        size="lg"
        id="password-input"
        v-model="form.password"
        type="password"
        placeholder="Your password goes here..."
        maxlength="30"
        ></b-form-input>
      </b-form-group>

      <b-form-group>
        <div class='w-100 d-flex form-utils mt-1 mb-3'>
          <div class="fbf">
            <input v-model="form.stayedSignedIn" class="regular-checkbox" type="checkbox"/>
            <span class="checkmark"></span>
            <label>Stay Signed</label>
          </div>
        </div>
      </b-form-group>

      <b-button id='submit-btn' class='w-100' type="submit" variant="success">
        <div class='w-100 d-flex'>
          <span class='mx-auto' style='font-size:15px; font-weight: bold;'>

            <div v-if="form.loading" class="spinner-grow" role="status">
              <span class="sr-only">Loading...</span>
            </div>

            <span v-else>Sign In</span>
          </span>
        </div>
      </b-button>

      <div id='forgot-password' class='w-100 text-center small mt-3' style='font-size: 12px !important;'>
          Forgot your <a alt="Reset password" style="cursor:pointer" v-on:click="state = 'recovery'" class='font-weight-bold'>password</a>?
        </div>


    </b-form>
  </div>

  <div v-else-if="state == '2fa'" class='login-form absolute-wh-center twostep'>
    <div class="branding text-center">
      <v-icon>mdi-vuejs</v-icon>
      <h3 class='text-center pb-2'>Two-step authentication</h3>
    </div>

    <b-form @submit="twoStepSubmit" class='pt-4'>
      <div class='small-text-dark w-100 text-center small'>
        Please, enter the 8-digit code that we sent to your email address.<br><br>
        The code is valid for 5 minutes.
      </div>

      <div class="w-100 text-center small" style="margin-top:5px;">
        <a href="#">Resend code</a></div>

        <b-form-group id="code-group">
          <b-form-input
          id="code"
          size='lg'
          v-model="twofa_code"
          type="text"
          placeholder="Enter the code *"
          maxlength="8"
          @input="onMod('code')"
          :state="codeFilter"
          aria-describedby="code-feedback"
          ></b-form-input>

          <b-form-invalid-feedback id="code-feedback">
            Verification code consist only from numbers
          </b-form-invalid-feedback>
        </b-form-group>

        <b-button id='submit-btn' class='w-100' type="submit" variant="success">
          <div class='w-100 d-flex'>
            <span class='mx-auto' style='font-size:15px; font-weight: bold;'>

              <div v-if="form.loading" class="spinner-grow" role="status">
                <span class="sr-only">Loading...</span>
              </div>

              <span v-else>Submit</span>
            </span>
          </div>
        </b-button>

      </b-form>
    </div>

    <div v-else-if="state == 'recovery'" class='login-form absolute-wh-center twostep'>
      <div class="branding text-center">
        <v-icon>mdi-vuejs</v-icon>
        <h3 class='text-center pb-2'>Recover Lost Password</h3>
      </div>

      <b-form @submit="recoverySubmit" class='pt-4'>
        <div class='small-text-dark w-100 text-center small'>
          Please enter the associated with your account email address<br><br>
        </div>

        <b-form-group id="code-group">
          <b-form-input
          id="code"
          size='lg'
          v-model="recover_email_address"
          type="text"
          placeholder="Enter your email address"
          maxlength="320"
          @input="onMod('recovery')"
          :state="recoveryEmail"
          aria-describedby="recovery-feedback"
          ></b-form-input>

          <b-form-invalid-feedback id="recovery-feedback">
            This is not a valid email address
          </b-form-invalid-feedback>
        </b-form-group>

        <b-button id='submit-btn' class='w-100' type="submit" variant="success">
          <div class='w-100 d-flex'>
            <span class='mx-auto' style='font-size:15px; font-weight: bold;'>

              <div v-if="form.loading" class="spinner-grow" role="status">
                <span class="sr-only">Loading...</span>
              </div>

              <span v-else>Submit</span>
            </span>
          </div>
        </b-button>

      </b-form>
    </div>

    <div v-else-if="state == 'new_password'" class='login-form absolute-wh-center twostep'>
      <div class="branding text-center">
        <v-icon>mdi-vuejs</v-icon>
        <h3 class='text-center pb-2'>Create new password</h3>
      </div>

      <b-form @submit="newPasswordSubmit" class='pt-4'>
        <b-form-group id="code-group">
          <b-form-input
          id="new_password"
          v-model="new_password"
          type="password"
          placeholder="Enter your new password"
          maxlength="24"
          @input="onMod('new_password')"
          :state="new_passwordFilter"
          aria-describedby="newpassword-feedback"
          ></b-form-input>

          <b-form-invalid-feedback id="newpassword-feedback">
            * Password length should be at least 6 characters
          </b-form-invalid-feedback>
        </b-form-group>

        <b-form-group id="code-group">
          <b-form-input
          id="confirm_new_password"
          v-model="confirm_new_password"
          type="password"
          placeholder="Confirm your new password"
          maxlength="24"
          @input="onMod('confirm_new_password')"
          :state="confirm_new_passwordFilter"
          aria-describedby="confirm_newpassword-feedback"
          ></b-form-input>

          <b-form-invalid-feedback id="confirm_newpassword-feedback">
            * Password should match
          </b-form-invalid-feedback>
        </b-form-group>

        <b-button id='submit-btn' class='w-100' type="submit" variant="success">
          <div class='w-100 d-flex'>
            <span class='mx-auto' style='font-size:15px; font-weight: bold;'>

              <div v-if="form.loading" class="spinner-grow" role="status">
                <span class="sr-only">Loading...</span>
              </div>

              <span v-else>Submit</span>
            </span>
          </div>
        </b-button>

      </b-form>
    </div>

    <div v-else-if="state == 'loading'"  class='w-100 h-100 d-flex justify-content-center align-items-center'>
      <b-spinner style="width: 100px; height: 100px;" variant="light"></b-spinner>
    </div>
  </template>

  <script>
  export default {
    name: 'Login',
    data() {
      return {
        form: {
          email: '',
          password: '',
          stayedSignedIn: false,
          errorMessage: null,
          logged : null,
          loading: null,
        },

        twofa_code: null,
        recover_email_address: null,
        state: "login",
        temp_token : '',

        confirm_new_password: '',
        new_password: '',

        modified: {
          'email' : false,
          'password' : false,
          'code' : false,
          'recovery' : false,
          'new_password' : false,
          'confirm_new_password' : false,
        }
      }
    },

    mounted() {
        if(this.$route.query.pruid != undefined) {
          this.state = 'loading';
          var pruid = this.$route.query.pruid;

          this.$http.get(`/api/auth/password/recovery/${pruid}`).then((res) => {
            if(res.status == 200) {
              this.state = 'new_password';
            } else if(res.status == 404) {
              this.$router.push('/');
            }
          }).catch((error) => {
              this.state = 'login'
              this.$router.push('/').catch(err => {})
          })
        }
    },


    computed: {
      new_passwordFilter() {
        if(this.modified['new_password'] == false) return null;
        return /^.{6,}$/.test(this.new_password);
      },

      confirm_new_passwordFilter() {
        if(this.modified['confirm_new_password'] == false) return null;
        if(this.confirm_new_password != this.new_password) return false;

        return /^.{6,}$/.test(this.confirm_new_password);
      },


      codeFilter() {
        if(this.modified['code'] == false) return null;
        return /[0-9]{8,}/.test(this.twofa_code);
      },

      emailFilter() {
        if(this.modified['email'] == false) return null;
        return /^\S+@\S+\.\S+$/.test(this.form.email);
      },

      recoveryEmail() {
        if(this.modified.recovery == false) return null;
        return /^\S+@\S+\.\S+$/.test(this.recover_email_address);
      },
    },


    methods: {
      onMod(prop) {
        this.modified[prop] = true
      },

      newPasswordSubmit(event) {
        event.preventDefault();
        this.form.loading = true;
        this.$http.post(`/api/auth/password/recovery/change/${this.$route.query.pruid}`, {
          password: this.new_password
        }).then((res) => {
          res = res.data;

          if(res.error != undefined) {
              this.$dtoast.pop({
                preset: "error",
                heading: `Auth Error`,
                content:  res.error,
          })
          } else if(res.success != undefined) {
              this.$dtoast.pop({
                preset: "success",
                heading: `Success!`,
                content:  res.success,
          })

            setTimeout(() => {
              this.state = 'login';
              this.$router.push('/');
            }, 1000)
          }

          this.form.loading = false;
        })
      },

      recoverySubmit(event) {
        event.preventDefault();
        this.form.loading = true;

        this.$http.post(`/api/auth/password/recovery`, {
          email: this.recover_email_address
        }).then((res) => {
          res = res.data;

          if(res.error != undefined) {
             this.$dtoast.pop({
                preset: "error",
                heading: `Auth Error`,
                content:  res.error,
          })
          } else if(res.success != undefined) {
              this.$dtoast.pop({
                preset: "success",
                heading: `Success!`,
                content:  res.success,
          })
          }

          this.form.loading = false;
        })
      },

      twoStepSubmit(event) {
        event.preventDefault();
        this.form.loading = true;
        var th = this
        this.$http.post(`/api/auth/twostep/${this.twofa_code}`, {
          token: this.temp_token
        }).then((res) => {
          res = res.data;

          if(res.status != undefined && res.status == 'ERROR') {
             this.$dtoast.pop({
                preset: "error",
                heading: `2FA Error`,
                content:  res.reason,
          })
          } else {
              this.$dtoast.pop({
                preset: "success",
                heading: `Authentication Granted`,
                content:  `You logged in successfully`,
          })

            localStorage.setItem('access_token', JSON.stringify({value: res.access_token, expiry : Date.now() + 3600 * 1000}) );
            this.$http.defaults.params = {}
            this.$http.defaults.params['token'] = res.access_token

            setTimeout(function() {
              th.$router.push('/');
            }, 1000)
          }

          this.form.loading = false;
        })
      },


      onSubmit(event) {
        this.form.errorMessage = null;
        this.form.logged = null;

        event.preventDefault();
        const {email, password} = this.form;

        let th = this;
        if(email.trim() !== '' && password.trim() !== '') {
          this.form.loading = true;
          this.$http.post('/api/auth/login', {
            'email' : email,
            'password' : password
          })
          .then((response) => {
            this.form.loading = false;
            if(response.data.access_token != undefined) {
              localStorage.setItem('access_token', JSON.stringify({value: response.data.access_token, expiry : Date.now() + 3600 * 1000}) );
              this.$http.defaults.params = {}
              this.$http.defaults.params['token'] = response.data.access_token

                this.$dtoast.pop({
                      preset: "success",
                      heading: `Authentication Granted`,
                      content:  `You logged in successfully`,
                })

              setTimeout(function() {
                th.$router.push('/');
              }, 1000)
            } else if(response.data['2fa'] != undefined) {
              this.state = "2fa";
              this.temp_token = response.data.temp_token;
              return;
            }


          }).catch(error => {
            this.form.loading = false;
            th.form.errorMessage = true;

              this.$dtoast.pop({
                preset: "error",
                heading: `Auth Error`,
                content:  `Invalid credentials`,
          })
          });
        } else {
           this.$dtoast.pop({
                preset: "error",
                heading: `Error`,
                content:  `Please, enter your credentials`,
          })
        }
      }
    }
  }
  </script>

  <style lang="scss">
  @import '~/variables.scss';

  body {
    background-color: $formsBodyBgColor;
  }


  .branding {
    i {
      font-size: 70px;
      color: #727272;
    }
  }

  .login-form.twostep {
    max-height:500px !important;
    padding: 30px 50px !important;

    .small-text-dark {
      font-size:16px !important;
      color: #848484 !important;
    }
  }


  .login-form {
    max-width: 450px;
    max-height: 540px;
    width: 100%;
    height: 100%;
    padding: 30px 60px;
    border: 1px solid #404040;
    border-radius: 10px;
    font-family: 'Roboto', sans-serif;
    color: #fff;

    .small-text-dark {
      color: #848484 !important;
    }
    .fbf {

      label {
        margin-left: 5px;
        vertical-align: middle;
      }
      .regular-checkbox {
        -webkit-appearance: none;
        background-color: #404040;
        border: 1px solid #404040;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05);
        padding: 9px;
        border-radius: 3px;
        display: inline-block;
        position: relative;
      }

      .regular-checkbox:active, .regular-checkbox:checked:active {
        box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px 1px 3px rgba(0,0,0,0.1);
      }

      .regular-checkbox:checked {
        box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05), inset 15px 10px -12px rgba(255,255,255,0.1);
        color: #99a1a7;
      }

      .regular-checkbox:checked:after {
        content: "";
        width: 60% !important;
        /* font-size: 14px; */
        position: absolute;
        top: 4px;
        left: 4px;
        /* color: #99a1a7; */
        height: 60% !important;
        border-radius: 50%;
        background: #909090;
      }
    }

    .spinner-grow {
      width: 1.5rem !important;
      height: 1.5rem !important;
    }

    .btn {
      height:45px !important;
      border-radius:10px;
    }

    .form-utils {
      margin-top:-15px;
      color:$select_f;
    }

    .status {
      span {
        max-width: 300px;
        position:absolute;
        transition: .5s all;
        opacity: 0;
      }
    }

    .small-text-dark {
      color: $navbar_menus_bg;
      font-size:12px;
    }
    h3 {
      color: $topbar_fcolor;
    }
    a {
      color: #118ab2 !important;
    }

    a:hover {
      text-decoration: none;
    }

    form {
      color: #cdcdcd !important;

      .form-group {

        label {
          font-size:12px !important;
        }


        input {
          color: $topbar_secondary_fcolor;
          background-color: #282828;
          border: 1px solid #404040;
          border-radius: 5px;
          outline: none !important;
          font-size: 14px;

          &:active {
            outline: none !important;
          }
        }
      }
    }
  }
  </style>
