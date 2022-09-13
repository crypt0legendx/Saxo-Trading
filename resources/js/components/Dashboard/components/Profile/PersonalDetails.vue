<template>
  <div class="dashboard-component p-2 col-12 col-lg-11 col-md-11">
    <h5 class="mb-4 text-center header-label">Edit Personal Details</h5>

    <div role="group" class="w-100 p-1 mb-2">
      <label class='label-group'>First Name</label>
      <b-form-input
      v-if="loaded"
      class='cus-input'
      id="first-name"
      @input="onMod('fname')"
      v-model="firstName"
      :state="firstNameState"
      aria-describedby="first-name-feedback"
      placeholder="Enter your first name"
      trim
      required
      ></b-form-input>

      <b-skeleton class="cus-input" v-if="!loaded" type="input"></b-skeleton>

      <b-form-invalid-feedback id="first-name-feedback">
        Field is empty or invalid
      </b-form-invalid-feedback>
    </div>

    <div role="group" class="w-100 p-1 mb-2">
      <label class='label-group'>Last Name</label>
      <b-form-input
      v-if="loaded"
      class='cus-input'
      id="last-name"
      @input="onMod('lname')"
      v-model="lastName"
      :state="lastNameState"
      aria-describedby="last-name-feedback"
      placeholder="Enter your first name"
      trim
      required
      ></b-form-input>

      <b-skeleton class="cus-input" v-if="!loaded" type="input"></b-skeleton>

      <b-form-invalid-feedback id="last-name-feedback">
        Field is empty or invalid
      </b-form-invalid-feedback>
    </div>

    <div role="group" class="w-100 p-1 mb-2">
      <label class="label-group">Date of Birth</label>
      <b-form-input
      v-if="loaded"
      class='cus-input'
      id="date-birth"
      :value="birthDate"
      :state="null"
      trim
      disabled
      ></b-form-input>

      <b-skeleton class="cus-input" v-if="!loaded" type="input"></b-skeleton>
    </div>

    <div role="group" class="w-100 p-1 mb-2">
      <label class="label-group">Street & Number</label>
      <b-form-input
      v-if="loaded"
      class='cus-input no-focus-outline'
      id="street-number"
      v-model="streetNumber"
      @input="onMod('str')"
      :state="streetNumberState"
      aria-describedby="street-feedback"
      placeholder="Enter your street & number"
      trim
      required
      ></b-form-input>

      <b-skeleton class="cus-input" v-if="!loaded" type="input"></b-skeleton>

      <b-form-invalid-feedback id="street-feedback">
        Field is empty or invalid
      </b-form-invalid-feedback>
    </div>

    <div role="group" class="w-100 p-1 mb-2">
      <label class="label-group">Postal/Zip Code</label>
      <b-form-input
      v-if="loaded"
      class='cus-input no-focus-outline'
      id="postal"
      v-model="postalCode"
      @input="onMod('postal')"
      :state="postalCodeState"
      aria-describedby="postal-feedback"
      placeholder="Enter your postal/zip code"
      trim
      required
      ></b-form-input>

      <b-skeleton class="cus-input" v-if="!loaded" type="input"></b-skeleton>

      <b-form-invalid-feedback id="postal-feedback">
        Field is empty or invalid
      </b-form-invalid-feedback>
    </div>

    <div role="group" class="w-100 p-1 mb-2">
      <label class="label-group">City/Town</label>
      <b-form-input
      v-if="loaded"
      class='cus-input no-focus-outline'
      id="city"
      v-model="city"
      @input="onMod('city')"
      :state="cityState"
      aria-describedby="city-feedback"
      placeholder="Enter your city/town"
      trim
      required
      ></b-form-input>

      <b-skeleton class="cus-input" v-if="!loaded" type="input"></b-skeleton>

      <b-form-invalid-feedback id="city-feedback">
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
  name: 'PersonalDetails',
  components: {
    OptionBtns
  },
  data() {
    return {
      loaded: false,

      modified : {
        fname: false,
        lname: false,
        str : false,
        postal: false,
        city: false,
      },

      prevState: {
        firstName: '',
        lastName: '',
        streetNumber: '',
        postalCode: '',
        city: ''
      },
      firstName: '',
      lastName: '',
      birthDate: '',
      streetNumber: '',
      postalCode: '',
      city: ''
    }
  },

  mounted() {
    this.$http.get(`/api/user/`).then((res) => {
      if(res.data.id != undefined) {
        res = res.data;
        var user_data = JSON.parse(res.user_data);

        var name = res.user.name.split(' ');
        var first_name = name[0];
        var last_name = name[1];

        this.firstName =  first_name;
        this.lastName = last_name;
        this.birthDate = user_data.personalData.dateOfBirth
        this.streetNumber = user_data.residence.streetNumber
        this.postalCode = user_data.residence.postalCode
        this.city = user_data.residence.city

        this.loaded = true;
      }
    })
  },

  methods: {
    onMod(prop) {
      this.modified[prop] = true
    },

    SaveDetails() {
      const {prevState: ps} = this;

      if(ps.firstName != this.firstName ||
        ps.lastName != this.lastName ||
        ps.streetNumber != this.streetNumber ||
        ps.postalCode != this.postalCode ||
        ps.city != this.city)
        {

          this.$http.post(`/api/user/update/user_data`, {
            name: this.firstName + ' ' + this.lastName,
            streetNumber: this.streetNumber,
            postalCode: this.postalCode,
            city: this.city,
          }).then((res) => {

            this.$sound.play('success.mp3')
              this.$dtoast.pop({
                preset: "success",
                heading: `Success!`,
                content:  `Your personal information was updated`,
          })
        })
      }
    }
    },
    computed: {
      firstNameState() {
        if(this.modified['fname'] == false) return null;
        return !/[^a-z]/i.test(this.firstName) && this.firstName.length >= 4;
      },
      lastNameState() {
        if(this.modified['lname'] == false) return null;
        return !/[^a-z]/i.test(this.lastName) && this.lastName.length >= 4
      },
      postalCodeState() {
        if(this.modified['postal'] == false) return null;
        return /^[a-z0-9]+$/i.test(this.postalCode) && this.postalCode.length > 2;
      },
      streetNumberState() {
        if(this.modified['str'] == false) return null;
        return /^(?:[A-Za-z]+)(?:[A-Za-z0-9 _]*)$/.test(this.streetNumber) && this.streetNumber.length > 4;
      },
      cityState() {
        if(this.modified['city'] == false) return null;
        return !/[^a-z]/i.test(this.city) && this.city.length > 2
      }
    }
  }
  </script>

  <style>

  </style>
