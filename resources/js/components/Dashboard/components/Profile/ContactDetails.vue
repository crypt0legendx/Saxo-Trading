<template>
    <div class="dashboard-component p-2 col-12 col-lg-11 col-md-11">
        <h5 class="mb-4 text-center header-label">Edit Contact Details</h5>

        <div role="group" class="w-100 p-1 mb-2">
            <label class='label-group'>Your phone number</label>
            <b-form-input
                class='cus-input'
                id="phone-number"
                @input="onMod('phone')"
                v-if="loaded"
                v-model="phoneNumber"
                :state="phoneNumberState"
                aria-describedby="phone-number-feedback"
                placeholder="Enter your first name"
                trim
                required
            ></b-form-input>

            <b-skeleton class="cus-input" v-if="!loaded" type="input"></b-skeleton>

            <b-form-invalid-feedback id="phone-number-feedback">
                Field is empty or invalid
            </b-form-invalid-feedback>
        </div>

        <div role="group" class="w-100 p-1 mb-2">
            <label class='label-group'>Your Email address</label>
            <b-form-input
                class='cus-input'
                v-if="loaded"
                id="email"
                v-model="emailAddress"
                @input="onMod('email')"
                :state="null"
                aria-describedby="email-feedback"
                trim
                required
                disabled
            ></b-form-input>

            <b-skeleton class="cus-input" v-if="!loaded" type="input"></b-skeleton>

            <b-form-invalid-feedback id="email-feedback">
                Field is empty or invalid
            </b-form-invalid-feedback>
        </div>

        <OptionBtns
            @submitEvent='SaveDetails'
            :hideBack="true"
            :submitDisabled="!loaded"
        />
    </div>
</template>

<script>
import OptionBtns from '../OptionBtns.vue';

export default {
    name: 'ContactDetails',
    components: {
        OptionBtns
    },

    mounted() {
      this.$http.get(`/api/user/`).then((res) => {
        if(res.data.id != undefined) {
          res = res.data;
          var user_data = JSON.parse(res.user_data);

          this.emailAddress = res.user.email
          this.phoneNumber = user_data.personalData.mobilePhone

          this.loaded = true;
        }
      })
    },

    data() {
        return {
            loaded:false,
            prevState: {
                phoneNumber: '',
                emailAddress: '',
            },

            modified : {
              phone: false,
              email: false,
            },

            phoneNumber: '',
            emailAddress: '',
        }
    },
    methods: {
      onMod(prop) {
        this.modified[prop] = true
      },

        SaveDetails() {
            const {prevState: ps} = this;
            if(ps.phoneNumber != this.phoneNumber || ps.emailAddress != this.emailAddress) {

                this.$http.post(`/api/user/update/user_data`, {
                  mobilePhone:this.phoneNumber,
                }).then((res) => {
                  this.$sound.play('success.mp3')
                    this.$dtoast.pop({
                            preset: "success",
                            heading: `Success!`,
                            content:  `Your contact details were updated successfully.`,
                    })
              }).catch((error) => {
                    this.$dtoast.pop({
                            preset: "error",
                            heading: `Unexpected Error`,
                            content:  `Something went wrong, please, try again`,
                    })
              })

                this.prevState = {
                    phoneNumber: this.phoneNumber,
                    emailAddress: this.emailAddress,
                }
            }
        }
    },
    computed: {
        phoneNumberState() {
            if(this.modified['phone'] == false) return null;
            return /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im.test(this.phoneNumber);
        },
        emailAddressState() {
              if(this.modified['email'] == false) return null;
            return /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(this.emailAddress);
        }
    }
}
</script>

<style>

</style>
