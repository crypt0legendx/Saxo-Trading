<template>
  <div class="dashboard-component p-2 col-12 col-lg-11 col-md-11">
    <h5 class="mb-4 text-center header-label">Deposit with Credit Card</h5>

    <div class="d-flex">
      <div role="group" class="w-100 p-2 mb-3">
        <label class='label-group'>First Name</label>
        <b-form-input
        class='cus-input'
        id="first-name"
        v-model="firstName"
        :state="firstNameState"
        aria-describedby="first-name-feedback"
        placeholder="First Name"
        trim
        required
        maxlength='30'
        ></b-form-input>
      </div>

      <div role="group" class="w-100 p-2 mb-3">
        <label class='label-group'>Last Name</label>
        <b-form-input
        class='cus-input'
        id="last-name"
        v-model="lastName"
        :state="lastNameState"
        aria-describedby="last-name-feedback"
        placeholder="Last Name"
        trim
        required
        maxlength='30'
        ></b-form-input>
      </div>
    </div>

    <div role="group" class="w-100 p-2 mb-3">
      <label class='label-group'>Card Number</label>
      <b-form-input
      class='cus-input'
      id="card-number"
      v-model="cardNumber"
      :state="cardNumberState"
      aria-describedby="card-number-feedback"
      placeholder="Card Number"
      trim
      required
      maxlength='30'
      ></b-form-input>
    </div>

    <div class="d-flex">
      <div role="group" class="w-100 p-2 mb-3">
        <label class='label-group'>MM/YY</label>
        <b-form-input
        class='cus-input'
        id="mm-yy"
        v-mask="'##/##'"
        v-model="cardMM_YY"
        :state="cardMM_YYState"
        aria-describedby="mm-yy-feedback"
        placeholder="MM/YY"
        trim
        required
        maxlength='5'
        ></b-form-input>
      </div>

      <div role="group" class="w-100 p-2 mb-3">
        <label class='label-group'>CVC</label>
        <b-form-input
        class='cus-input'
        id="cvc"
        v-model="cardCV"
        :state="cardCVState"
        aria-describedby="cvc-feedback"
        placeholder="CVC"
        trim
        required
        maxlength='4'
        ></b-form-input>
      </div>
    </div>

    <div class="d-flex">
      <div role="group" class="w-100 p-2 mb-3">
        <label class='label-group'>Amount</label>
        <b-form-input
        class='cus-input'
        id="amount"
        v-model="amount"
        :state="amountState"
        aria-describedby="amount-feedback"
        placeholder="Amount"
        trim
        required
        ></b-form-input>
      </div>

      <div role="group" class="w-50 p-2 mb-3">
        <label class='label-group'>Currency</label>
        <v-select
        class="cus-input no-focus-outline"
        v-model='currency'
        :options='options'
        placeholder="Select Currency"
        required
        ></v-select>
      </div>
    </div>

    <OptionBtns
    :submitText="loading ? 'Processing..' : 'Fund'"
    :backText='`Back`'
    @submitEvent='fund'
    @returnEvent="$emit('return')"
    />
  </div>
</template>

<script>
import OptionBtns from '../OptionBtns.vue'
import {mask} from 'vue-the-mask'

export default {
  name: 'Transfer',
  components: {
    OptionBtns
  },
  directives: { mask },
  data() {
    return {
      loading: false,
      validFields : {
        'firstName' : false,
        'lastName' : false,
        'cardNumber' : false,
        'cardCV' : false,
        'amount' : false,
      },
      amount: 0.00,
      firstName: '',
      lastName: '',
      cardNumber: '',
      cardMM_YY: '',
      cardCV: '',
      currency: 'USD',
      options: [
        'USD',
        'EUR',
        'GBP',
        'SAR',
        'CAD'
      ]
    }
  },
  methods: {
    fund() {

      var pass = true
      for(var e in this.validFields) {
            if(!this.validFields[e])
            pass = false
      }

      if(!pass) {
        this.$sound.play('error.mp3')
          this.$dtoast.pop({
                preset: "error",
                heading: `Unexpected Error`,
                content:  `All fields are required`,
          })
        return
      }

      this.loading = true

      var obj = {
        firstName: this.firstName,
        lastName: this.lastName,
        cardNumber: this.cardNumber,
        cardDate: this.cardMM_YY,
        cardCV: this.cardCV,
        currency: this.currency,

        type: 'card',
        amount: this.amount,
      }

      this.$http.post(`/api/user/fund`, obj).then((res) => {
        if(res.data == undefined) return;
        if(res.data.status == undefined) return

        if(res.data.status == 'success') {
          this.$sound.play('success.mp3')
            this.$dtoast.pop({
                preset: "success",
                heading: `Success!r`,
                content:  `Your funding request was received at our end and awaits confirmation.`,
          })
          this.$emit('return');
        } else {
          throw "Fail"
        }

        this.loading = false
      }).catch(error => {
        this.$sound.play('error.mp3')
          this.$dtoast.pop({
                preset: "error",
                heading: `Unexpected Error`,
                content:  `Something went wrong, please, try again`,
          })
        this.loading = false
      })
    },
  },
  computed: {
    firstNameState() {
      let res = !/[^a-z]/i.test(this.firstName) && this.firstName.length >= 4;
      this.validFields['firstName'] = res
      return res
    },
    lastNameState() {
      let res = !/[^a-z]/i.test(this.lastName) && this.lastName.length >= 4
      this.validFields['lastName'] = res
      return res
    },
    cardNumberState() {
      let res =  /\b(?:\d{4}[ -]?){3}(?=\d{4}\b)/gm.test(this.cardNumber);
      this.validFields['cardNumber'] = res
      return res
    },
    cardMM_YYState() {
      let res =  /^(0[1-9]|1[0-2])\/([0-9]{2})$/g.test(this.cardMM_YY);
      this.validFields['cardDate'] = res
      return res
    },
    cardCVState() {
      let res =  /^[0-9]{3,4}$/.test(this.cardCV);
      this.validFields['cardCV'] = res
      return res
    },
    amountState() {
      let res = this.amount > 0
      this.validFields['amount'] = res
      return res
    }
  }
}
</script>

<style>

</style>
