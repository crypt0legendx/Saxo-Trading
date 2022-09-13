<template>
  <AddWalletFunds
      @return='fundsActive = false'
      v-if='fundsActive'
  />

    <div v-else class="dashboard-component p-2 col-12 col-lg-11 col-md-11">
        <h5 class="mb-4 text-center header-label">
            Creating a {{ headerLabel }} Account
        </h5>

        <div role="group" class="w-100 mb-3">
            <label class='label-group'>Account type</label>
            <v-select
                class="cus-input"
                v-model="accTypeSelected"
                :class="accTypeSelected != null ? 'v-select-valid' : 'v-select-no-valid'"
                :options="accTypeOptions"
                placeholder="Account type"
                required
            ></v-select>
        </div>

        <div role="group" class="w-100 mb-3">
            <label class='label-group'>Leverage</label>
            <v-select
                class="cus-input no-focus-outline"
                v-model='levSelected'
                :class="levSelected != null ? 'v-select-valid' : 'v-select-no-valid'"
                :options='levOptions'
                placeholder="Select Leverage"
                required
            ></v-select>
        </div>

        <div role="group" class="w-100 mb-3" v-if="levSelected">
            <label class='label-group'>Account Base Currency</label>
            <v-select
                class="cus-input no-focus-outline"
                v-model='currencySelected'
                :options='currencyOptions'
                :class="currencySelected != null ? 'v-select-valid' : 'v-select-no-valid'"
                placeholder="Select Base Currency"
                required
            ></v-select>
        </div>

        <div role="group" class="w-100 mb-3" v-if="currencySelected">
            <label class='label-group'>{{accTypeSelected == "DEMO" ? `Amount ($0 - $150 000)` : `Amount ($0 - $${$store.state.user.user_obj.balance })`}}
              <span v-on:click="fundsActive = true" style="color: #ff5c5c; cursor:pointer" v-show="$store.state.user.user_obj.balance <= 0">Add funds</span>
            </label>
            <b-form-input
                class="cus-input no-focus-outline"
                type="number"
                :placeholder="`Amount ($0 - $ ${$store.state.user.user_obj.balance})`"
                v-model='amountInput'
                :state='amountEntered'
                :max="parseInt($store.state.user.user_obj.balance)"
                :min="0"
            ></b-form-input>
        </div>

        <b-alert show variant="info" v-if="headerLabel === 'Demo'" style="font-size: 14px !important;">
            <v-icon style="font-size: 22px !important;">mdi-information</v-icon>
            Demo accounts are simulations only, predominantly used as a learning tool for inexperienced investors or for testing new trading strategies. During highly volatile or illiquid periods, similar trades requested on real and demo accounts may be treated differently, and you cannot expect that the demo account always reflects all the market conditions of the live trading environment.
        </b-alert>

        <OptionBtns
            @submitEvent='createAccount'
            @returnEvent="$emit('return')"
            :backText='`Back`'
        />
    </div>
</template>

<script>

import OptionBtns from '../OptionBtns.vue';
import accountTypesJson from '../../../../json/account_types.json';
import leverageJson from '../../../../json/leverage.json';
import currenciesJson from '../../../../json/currency_list.json';

export default {
    name: 'CreateAccount',
    components: {
        OptionBtns
    },
    props: {
        headerLabel: String
    },
    data() {
        return {
            fundsActive: false,
            accTypeSelected: (this.headerLabel === 'Demo' ? `DEMO` : null),
            accTypeOptions: (this.headerLabel === 'Demo' ? [`DEMO`] : accountTypesJson),
            levSelected: null,
            levOptions: leverageJson,
            currencySelected: null,
            currencyOptions: currenciesJson,
            amountInput: 0,
        }
    },

    mounted() {
    },

    methods: {
        createAccount() {
            const {accTypeSelected, levSelected, currencySelected, amountInput} = this;

            if(accTypeSelected != null && levSelected != null && currencySelected != null) {

              if((amountInput < 0 && amountInput > this.$store.state.user.user_obj.balance) && this.accTypeSelected != 'DEMO') {
                th.$sound.play('error.mp3')
                  this.$dtoast.pop({
                    preset: "error",
                    heading: `Unexpected Error`,
                    content:  `You don't have enough balance to do that`,
                  })
                return;
              }

                this.$emit('createAccount', {
                    type: accTypeSelected.toLowerCase(),
                    leverage: levSelected.split(':')[1],
                    currency: currencySelected,
                    balance: amountInput,
                });

                this.$emit('return');
            }
        }
    },
    computed: {
        accTypeSelection() {
            return this.accTypeSelected != null;
        },
        levSelection() {
            return this.levSelected != null;
        },
        currencySelection() {
            return this.currencySelected != null;
        },
        amountEntered() {
            if(this.accTypeSelected == 'DEMO' && this.amountInput > 0 && this.amountInput <= 150000)
              return true;
            else
              return this.amountInput >= 0 && this.amountInput <= this.$store.state.user.user_obj.balance;
        }
    }
}
</script>

<style lang='scss'>
</style>
