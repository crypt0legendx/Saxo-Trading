<template>
    <div class="dashboard-component p-2 col-12 col-lg-11 col-md-11">
        <h5 class="mb-4 text-center header-label">Transfer between your Wallet and Trading Account</h5>

        <div role="group" class="w-100 mb-3">
            <label class='label-group'>Source account</label>
            <v-select
                class="cus-input no-focus-outline"
                v-model="sourceAccountSelected"
                :value="sourceAccountSelected"
                :class="sourceAccountSelected != null ? 'v-select-valid' : 'v-select-no-valid'"
                :placeholder='`Select source account`'
                :options='accountOptions'
                required
                @input="checkSelection(0)"
            ></v-select>
        </div>

        <div role="group" class="w-100 mb-3">
            <label class='label-group'>Target account</label>
            <v-select
                class="cus-input no-focus-outline"
                v-model='targetAccountSelected'
                :options='accountOptions'
                :class="targetAccountSelected != null ? 'v-select-valid' : 'v-select-no-valid'"
                :placeholder='`Select target account`'
                required
                @input="checkSelection(1)"
            ></v-select>
        </div>

        <div role="group" class="w-100 mb-3">
            <label class='label-group'>Sending amount</label>
            <b-form-input
                class="cus-input no-focus-outline"
                type="number"
                v-model='sendingAmount'
                :state='sendingAmountState'
                placeholder="0 - 100000"
                :max="100000"
                :min="0"
            ></b-form-input>
        </div>

        <OptionBtns
            :submitText="`Transfer`"
            :backText='`Back`'
            @submitEvent='transfer'
            @returnEvent="$emit('return')"
        />
    </div>
</template>

<script>
import OptionBtns from '../OptionBtns.vue';

export default {
    name: 'Transfer',
    components: {
        OptionBtns
    },
    data() {
        return {
            sourceAccountSelected: null,
            targetAccountSelected: null,
            sendingAmount: 0,
            accountOptions: []
        }
    },
    mounted() {
      this.accountOptions = this.getAccounts()
    },
    methods: {
        getAccounts() {
          let accounts = []
          const user = this.$store.getters.get_user
          user.accounts.forEach((account) => {
            accounts.push('SX#' + account.id)
          })
          return accounts
        },
        transfer() {
            if(this.sourceAccountSelected != null && this.targetAccountSelected != null && this.sendingAmount > 0 && this.sendingAmount < 100000) {
                this.$emit('transfer');
                this.$emit('return');
            }
        },
        checkSelection(iSel) {
            if(this.sourceAccountSelected === this.targetAccountSelected) {
                if(iSel === 0)
                    this.targetAccountSelected = null;
                else
                    this.sourceAccountSelected = null;
            }
        }
    },
    computed: {
        sendingAmountState() {
            return this.sendingAmount > 0 && this.sendingAmount < 100000;
        }
    }
}
</script>

<style>

</style>
