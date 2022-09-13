<template>
    <div id='register-container' class="mx-auto mt-5 mb-3">
       <!-- <div id='steps' class="w-100">
            <b-progress
            :value="(
                completedInputs.fn &&
                completedInputs.ln &&
                completedInputs.email &&
                completedInputs.pwd &&
                completedInputs.confPwd ? 100 : 0
            )"
            :max='100' style="background-color: #404040;">
            </b-progress>

            <div id='step-btns' class='absolute-wh-center col-12 d-flex justify-content-around'>
                <v-icon
                    class="col-1 text-center"
                    :style="{ color: completedInputs.fn ? '#29cf5b' : '#404040' }"
                >mdi-check-circle-outline
                </v-icon>

                <v-icon
                    class="col-1 text-center"
                    :style="{ color: completedInputs.ln ? '#29cf5b' : '#404040' }"
                >mdi-check-circle-outline
                </v-icon>

                <v-icon
                    class="col-1 text-center"
                    :style="{ color: completedInputs.email ? '#29cf5b' : '#404040' }"
                >mdi-check-circle-outline
                </v-icon>

                <v-icon
                    class="col-1 text-center"
                    :style="{ color: completedInputs.pwd ? '#29cf5b' : '#404040' }"
                >mdi-check-circle-outline
                </v-icon>

                <v-icon
                    class="col-1 text-center"
                    :style="{ color: completedInputs.confPwd ? '#29cf5b' : '#404040' }"
                >mdi-check-circle-outline
                </v-icon>
            </div>
        </div>-->
        <h4 class='text-center mt-0' style="color: #dcdcdc;">Register now, its quick!</h4>

        <label class='w-100 text-center' style='color: #515151;'>
            Already have an account?
            <a href='/login' class='font-weight-bold'>Log in</a>
        </label>

        <b-form id='register-form' @submit="onSubmit" class="pt-2">
            <div role="group" class="w-100 p-1 mb-1">
                <label for="first-name">First Name</label>
                <b-form-input
                    class='reg-input'
                    id="first-name"
                    v-model="firstName"
                    :state="firstNameState"
                    aria-describedby="first-name-feedback"
                    placeholder="Enter your first name"
                    trim
                    required
                    :style="{'border-color': !inputsChangedOnce.fn ? '#404040 !important' : ''}"
                    @keydown="inputState(0)"
                    @change='inputState(0)'
                ></b-form-input>
            </div>

            <div role="group" class="w-100 p-1 mb-1">
                <label for="last-name">Last Name</label>
                <b-form-input
                    class='reg-input'
                    id="last-name"
                    v-model="lastName"
                    :state="lastNameState"
                    aria-describedby="last-name-feedback"
                    placeholder="Enter your last name"
                    trim
                    required
                    :style="{'border-color': !inputsChangedOnce.ln ? '#404040 !important' : ''}"
                    @keydown="inputState(1)"
                    @change='inputState(1)'
                ></b-form-input>
            </div>

            <div role="group" class="w-100 p-1 mb-1">
                <label for="email">Enter e-mail</label>
                <b-form-input
                    class='reg-input'
                    type="email"
                    id="email"
                    v-model="email"
                    :state="emailState"
                    aria-describedby="email-feedback"
                    placeholder="Enter your e-mail address"
                    trim
                    required
                    @keydown="inputState(2)"
                    @change='inputState(2)'
                ></b-form-input>

                <b-form-invalid-feedback id="email-feedback" :style="emailFeedback ? 'display:block' : ''">
                    {{ emailFeedback }}
                </b-form-invalid-feedback>
            </div>

            <div role="group" class="w-100 p-1 mb-1">
                <label for="password">Password</label>
                <b-form-input
                    type="password"
                    class='reg-input'
                    id="password"
                    v-model="password"
                    :state="passwordState"
                    aria-describedby="password-feedback"
                    placeholder="Enter your password"
                    trim
                    required
                    @keydown="inputState(3)"
                    @change='inputState(3)'
                ></b-form-input>

                <b-form-invalid-feedback id="password-feedback">
                    Password must contain the following:<br/>
                    - at least one digit<br/>
                    - one lower case<br/>
                    - one upper case<br/>
                    - length of 8 characters
                </b-form-invalid-feedback>
            </div>

            <div role="group" class="w-100 p-1 mb-1">
                <label for="confirm-password">Confirm Password</label>
                <b-form-input
                    type="password"
                    class='reg-input'
                    id="confirm-password"
                    v-model="confirmPassword"
                    :state="confirmPasswordState"
                    aria-describedby="confirm-password-feedback"
                    placeholder="Comfirm your password"
                    trim
                    required
                ></b-form-input>

                <b-form-invalid-feedback id="confirm-password-feedback">
                    Make sure this field is the same as your password
                </b-form-invalid-feedback>
            </div>

            <button id='reg-submit-btn' type="submit" class="btn mt-3" :disabled="!(
                completedInputs.fn &&
                completedInputs.ln &&
                completedInputs.email &&
                completedInputs.pwd &&
                completedInputs.confPwd
            )">

              <div v-if="btnLoading" class="spinner-grow" role="status">
                <span class="sr-only">Loading...</span>
              </div>
              <template v-else>
                Register
              </template>
            </button>

            <label class='w-100 mt-3 text-center' style="font-size: 14px !important; color: #515151 !important;">
                By registering you agree to our <a href="#" class='font-weight-bold'>privacy policy</a>
            </label>
        </b-form>
    </div>
</template>

<style lang="scss">
@import '~/variables.scss';

body {
    background-color: $formsBodyBgColor;
    min-height: 100vh !important;
    margin: 0 !important;
    padding: 0 !important;
}

#register-container {
    max-width: 500px;
    width: 100%;
    padding: 30px;
    padding-bottom: 0px;
    /*border: 1px solid #404040;
    border-radius: 10px;*/
    font-size: 13px;
    color: #84949a;

    #steps {
        max-width: 768px !important;
        margin: auto;
        padding: 15px;
        position: relative;

        .progress {
            max-height: 3px !important;
            .progress-bar {
                background-color: #29cf5b;
            }
        }

        #step-btns {
            .v-icon {
                transition: all .15s ease-in-out;
                border: none;
                padding: 0 !important;
                margin: 0 !important;
                background: none;
            }

            .v-icon::before {
                z-index: 999;
                font-size: 32px;
                border-radius: 100px;
                background-color: $formsBodyBgColor;
            }
        }
    }

    a {
        color: #118ab2 !important;
    }

    a:hover {
        text-decoration: none;
    }

    #register-form {
        padding: 30px 15px;

        label {
            color: #A2A2A2 !important;
            font-weight: 400;
        }

        .groups-divider {
            min-width: 100%;
            width: 100%;
            height: 0px;
            background-color: #eaeef1 !important;
        }

        .reg-input {
            font-size: 13px;
            height: 38px;
            padding: 0px 16px;
            transition: all .05s ease-in;
            width: 100%;
            color: $topbar_secondary_fcolor;
            background-color: #282828;
            border: 1px solid #404040;
            border-radius: 2px;
        }

        .reg-input:hover {
            border-width: 2px;
            padding: 0px 15px;
        }

        .invalid-feedback {
            font-size: 12px !important;
        }

        #reg-submit-btn {
            height: 54px;
            background-color: #0984e3;
            color: #fff;
            font-weight: 500;
            border-radius: 100px;
            min-width: 100%;
        }
    }
}
</style>

<script>
import countriesJson from '../../json/countries.json';

const generateCountriesData = () => {
    let countriesArray = [{ value: null, text: 'Select Country' }];
    const itrEnd = ('z').charCodeAt(0);

    for(let i = ('a').charCodeAt(0); i <= itrEnd; ++i) {
        let symbolRef = String.fromCharCode(i).toUpperCase();
        countriesArray.push({
            label: symbolRef,
            options: countriesJson.filter(c => c[0].toUpperCase() === symbolRef)
        });
    }

    return countriesArray;
}

export default {
    name: 'Register',
    data() {
        return {
            visibleStep: 0,
            activeStep: 0,
            completedInputs: {
                fn: false,
                ln: false,
                email: false,
                pwd: false,
                confPwd: false
            },
            maxSteps: 5,
            btnLoading: false,
            countriesArray: [],
            firstName: '',
            lastName: '',
            email: '',
            password: '',
            confirmPassword: '',
            emailFeedback: '',
            passwordFeedback: '',
            inputsChangedOnce: {
                fn: false,
                ln: false,
                email: false,
                pwd: false,
            }
        }
    },
    methods: {
        test(i) {
            alert(i);
        },
        inputState(st) {
            const {fn, ln, pwd, email} = this.inputsChangedOnce;

            switch(st) {
                case 0: this.inputsChangedOnce.fn = fn === false ? true : fn; break;
                case 1: this.inputsChangedOnce.ln = ln === false ? true : ln; break;
                case 2: this.inputsChangedOnce.email = email === false ? true : email; break;
                case 3: this.inputsChangedOnce.pwd = pwd === false ? true : pwd; break;
            }
        },
        changeTheme() {
            this.darkTheme = !this.darkTheme;
        },
        onSubmit(event) {
            event.preventDefault();
            let that = this
            this.btnLoading = true
            this.$http.post('/api/auth/signup', {
              'first_name' : this.firstName,
              'last_name' : this.lastName,
              'email' : this.email,
              'password' : this.password,
            }).then((response) => {
              if(response.data.access_token != undefined) {
                localStorage.setItem('access_token', JSON.stringify({value: response.data.access_token, expiry : Date.now() + 3600 * 1000}) );
                this.$http.defaults.params = {}
                this.$http.defaults.params['token'] = response.data.access_token
                //registered
                  this.$dtoast.pop({
                            preset: "success",
                            heading: `Success!`,
                            content:  `Your account was created successfully`,
                    })
                setTimeout(function() {
                  window.location.href = '/'
                }, 1000)

                that.btnLoading = false
              }
            }).catch(error => {
              error = error.response

              if(error.data.message != undefined) {
                if(error.data.message == 'The given data was invalid.') {
                  if(error.data.errors.email) {
                    that.emailFeedback = 'This email is already in use.';
                  }
                }

                 this.$dtoast.pop({
                preset: "error",
                heading: `Registration Error`,
                content:  error.data.message,
          })

                that.btnLoading = false
              }
            })
        }
    },
    mounted() {
        this.countriesArray = generateCountriesData();
    },

    computed: {
        firstNameState() {
            if(this.inputsChangedOnce.fn) {
                if(!/[^a-z]/i.test(this.firstName) && this.firstName.length >= 2)
                    this.completedInputs.fn = true;
                else
                    this.completedInputs.fn = false;

                return !/[^a-z]/i.test(this.firstName) && this.firstName.length >= 2;
            }
        },
        lastNameState() {
            if(this.inputsChangedOnce.ln) {
                if(!/[^a-z]/i.test(this.lastName) && this.lastName.length >= 2)
                    this.completedInputs.ln = true;
                else
                    this.completedInputs.ln = false;

                return !/[^a-z]/i.test(this.lastName) && this.lastName.length >= 2;
            }
        },
        emailState() {
            if(this.inputsChangedOnce.email) {
                if(/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(this.email))
                    this.completedInputs.email = true;
                else
                    this.completedInputs.email = false;

                return /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(this.email);
            }
        },
        passwordState() {
            if(this.inputsChangedOnce.pwd) {
                if(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test(this.password))
                    this.completedInputs.pwd = true;
                else
                    this.completedInputs.pwd = false;

                return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test(this.password);
            }
        },
        confirmPasswordState() {
            if(this.inputsChangedOnce.pwd) {
                if(this.confirmPassword.length >= 8 && this.confirmPassword === this.password)
                    this.completedInputs.confPwd = true;
                else
                    this.completedInputs.confPwd = false;

                return this.confirmPassword.length >= 8 && this.confirmPassword === this.password;
            }
        }
    }
}
</script>
