<template>
  <div class="dashboard-component p-2 col-12 col-lg-11 col-md-11">
    <h5 class="mb-4 text-center header-label">
      Add Funds to {{ headerLabel }} Account
    </h5>

    <div role="group" class="w-100 mb-3">
      <label class='label-group'>{{ headerLabel }} Account number</label>
      <b-form-input
      class="cus-input no-focus-outline"
      :value='`${account.uuid.type.toUpperCase()} ${account.uuid.id} / ${account.currency} ${account.balance}`'
      :state='null'
      disabled
      ></b-form-input>
    </div>

    <!--       :placeholder="`Amount (500 ${account.currency} - ${max_deposit.toLocaleString('en-US', { style: 'currency', currency: account.currency }) }) `" -->

    <div role="group" class="w-100 mb-3">
      <label class='label-group'>Amount<!-- (500 {{ account.currency }} - 100 000 {{ account.currency }})--></label>
      <b-form-input
      class="cus-input no-focus-outline"
      type="number"
      :placeholder='`500 - 100 000 ${account.currency}`'
      v-model='amountInput'
      :state='amountEntered'
      :max="100000"
      :min="500"
      ></b-form-input>
    </div>

    <div role="group" class="w-100 mb-3">
      <label class='label-group'>Currency</label>
      <b-form-input
      class="cus-input no-focus-outline"
      v-model='account.currency'
      :state='null'
      disabled
      ></b-form-input>
    </div>

    <OptionBtns
    @submitEvent='addFunds'
    @returnEvent="$emit('return')"
    :backText='`Back`'
    />
  </div>
</template>

<script>
import OptionBtns from '../OptionBtns.vue';

export default {
  name: 'AddFunds',
  components: {
    OptionBtns
  },
  props: {
    headerLabel: String,
    account: Object,
  },
  data() {
    return {
      amountInput: null,
      max_deposit: 100000,
    }
  },

  async mounted() {
    const res = await this.$store.dispatch('manualConvert', {from: 'USD', to: this.account.currency, load: this.$store.state.user.user_obj.balance})
    this.max_deposit = res
  },


  methods: {
     async addFunds() {
      if(this.amountInput >= 500 && this.amountInput <= this.max_deposit) {
        const res = await this.$store.dispatch('manualConvert', {from: this.account.currency, to: 'USD', load: this.amountInput})
        this.$emit('addFunds', {account: this.account, funds: res});
        this.$emit('return');
      }
    }
  },
  computed: {
    amountEntered() {
      return this.amountInput >= 500 && this.amountInput <= this.max_deposit;
    }
  }
}
</script>

<style lang='scss'>
</style>
