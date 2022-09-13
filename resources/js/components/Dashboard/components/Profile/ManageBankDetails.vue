<template>
    <div class="dashboard-component col-12 col-lg-11 col-md-11">
        <h5 class="mb-4 text-center header-label">
            Manage bank details
        </h5>

        <div>
            <div class="bank-details" v-for="(bank, index) in bankDetails" :key='index'>
                <b-button @click="toggleBankDetails(`bank-details-${index}`)" class="bank-toggler d-flex w-100 m-0 no-focus-outline">
                    <span class="mr-auto">{{ bank.bankName }}</span>
                    <span class="ml-auto">
                        {{ bank.currency }}
                        <v-icon>mdi-chevron-down</v-icon>
                    </span>
                </b-button>
                <b-collapse :id="`bank-details-${index}`" class="bank-details-data">
                    <div class="d-flex justify-content-center row">
                        <div role="group" class="col-lg-8 col-md-8 col-sm-12 col-xs-12 mb-3">
                            <label class='label-group'>Account holder's name</label>
                            <b-form-input
                                class="cus-input no-focus-outline"
                                type="text"
                                v-model='bank.accountHolder'
                                :state='null'
                                :placeholder="`Account holder's name`"
                                disabled
                            ></b-form-input>
                        </div>
                        <div role="group" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-3">
                            <label class='label-group'>Currency</label>
                            <!--:class="bank.currency != null ? 'v-select-valid' : 'v-select-no-valid'"-->
                            <v-select
                                class="cus-input"
                                v-model="bank.currency"
                                :placeholder='`Currency`'
                                :options="bankInputs.currencyOptions"
                                disabled
                            ></v-select>
                        </div>
                    </div>

                    <div role="group" class="mb-3">
                        <label class='label-group'>IBAN/Account number</label>
                        <!--:class="bank.iban != null ? 'is-valid' : 'is-invalid'"-->
                        <b-form-input
                            :state='null'
                            v-model='bank.iban'
                            class="cus-input no-focus-outline"
                            type="text"
                            :placeholder="`IBAN/Account number`"
                            disabled
                        ></b-form-input>
                    </div>

                    <div role="group" class="mb-3">
                        <label class='label-group'>Swift/BIC (Sort code for UK)</label>
                        <b-form-input
                            class="cus-input no-focus-outline"
                            type="text"
                            :placeholder="`BIC`"
                            v-model='bank.bic'
                            :state='null'
                            disabled
                        ></b-form-input>
                    </div>

                    <div role="group" class="mb-3">
                        <label class='label-group'>Bank name</label>
                        <b-form-input
                            class="cus-input no-focus-outline"
                            type="text"
                            :placeholder="`Bank name`"
                            v-model='bank.bankName'
                            :state='null'
                            disabled
                        ></b-form-input>
                    </div>

                    <div role="group" class="mb-3">
                        <label class='label-group'>Bank address</label>
                        <b-form-input
                            class="cus-input no-focus-outline"
                            type="text"
                            :placeholder="`Bank address`"
                            v-model='bank.bankAddress'
                            :state='null'
                            disabled
                        ></b-form-input>
                    </div>

                    <OptionsBtns
                        @submitEvent='rmBank(index)'
                        :submitText='`Remove`'
                        :hideBack="true"
                        class="mb-3"
                    />
                </b-collapse>
            </div>

            <div class="bank-details">
                <b-button @click="toggleBankDetails(`bank-add-inputs`)" class="bank-toggler d-flex w-100 m-0 no-focus-outline">
                    <span class="mr-auto" style="color: #0984e3 !important;">Add new bank details</span>
                    <span class="ml-auto">
                        <v-icon>mdi-plus</v-icon>
                    </span>
                </b-button>
                <b-collapse :id="`bank-add-inputs`" class="bank-details-data">
                    <div class="d-flex justify-content-center row">
                        <div role="group" class="col-lg-8 col-md-8 col-sm-12 col-xs-12 mb-3">
                            <label class='label-group'>Account holder's name</label>
                            <b-form-input
                                class="cus-input no-focus-outline"
                                type="text"
                                :placeholder="`Account holder's name`"
                                v-model='bankInputs.accountHolder'
                                :state='bankInputsHolderState'
                            ></b-form-input>
                        </div>
                        <div role="group" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-3">
                            <label class='label-group'>Currency</label>
                            <v-select
                                class="cus-input"
                                v-model="bankInputs.currency"
                                :class="bankInputs.currency != null ? 'v-select-valid' : 'v-select-no-valid'"
                                :placeholder='`Currency`'
                                :options="bankInputs.currencyOptions"
                            ></v-select>
                        </div>
                    </div>

                    <div role="group" class="mb-3">
                        <label class='label-group'>IBAN/Account number</label>
                        <b-form-input
                            class="cus-input no-focus-outline"
                            type="text"
                            :placeholder="`IBAN/Account number`"
                            v-model='bankInputs.iban'
                            :state='bankInputsIban'
                        ></b-form-input>
                    </div>

                    <div role="group" class="mb-3">
                        <label class='label-group'>Swift/BIC (Sort code for UK)</label>
                        <b-form-input
                            class="cus-input no-focus-outline"
                            type="text"
                            :placeholder="`Swift/BIC (Sort code for UK)`"
                            v-model='bankInputs.bic'
                            :state='bankInputsBic'
                        ></b-form-input>
                    </div>

                    <div role="group" class="mb-3">
                        <label class='label-group'>Bank name</label>
                        <b-form-input
                            class="cus-input no-focus-outline"
                            type="text"
                            :placeholder="`Bank name`"
                            v-model='bankInputs.bankName'
                            :state='bankInputsBankName'
                        ></b-form-input>
                    </div>

                    <div role="group" class="mb-3">
                        <label class='label-group'>Bank address</label>
                        <b-form-input
                            class="cus-input no-focus-outline"
                            type="text"
                            :placeholder="`Bank address`"
                            v-model='bankInputs.bankAddress'
                            :state='bankInputsBankAddress'
                        ></b-form-input>
                    </div>

                    <OptionsBtns
                        @submitEvent='addBankDetails'
                        :submitText='`Save`'
                        :hideBack="true"
                        class="mb-3 mt-3"
                    />
                </b-collapse>
            </div>
        </div>
    </div>
</template>

<script>
import OptionsBtns from '../OptionBtns.vue';
import currencyJson from '../../../../json/currency_list.json';

export default {
    name: 'ManageBankDetails',
    components: {
        OptionsBtns
    },
    data() {
        return {
            bankInputs: {
                accountHolder: null,
                currency: null,
                currencyOptions: currencyJson,
                iban: null,
                bic: null,
                bankName: null,
                bankAddress: null,
            },
            bankDetails: [],
        }
    },

    mounted() {
      this.$http.get(`/api/user/`).then((res) => {
        if(res.data.id != undefined) {
          res = res.data;
          var user_data = JSON.parse(res.user_data);
          var banks = user_data.banks;

          if(banks != undefined && banks.length > 0)
            banks.forEach((bank) => {
              this.bankDetails.push({
                  id: bank.id,
                  accountHolder: bank.accountHolder,
                  currency: bank.currency,
                  iban: bank.iban,
                  bic: bank.bic,
                  bankName: bank.bankName,
                  bankAddress: bank.bankAddress
              });
            })

          this.loaded = true;
        }
      })
    },


    methods: {
        rmBank(index) {
          var bankID = this.bankDetails[index].id
          this.$http.post(`/api/user/bank/remove/${bankID}`).then((res) => {
              if(res.data.length == 0) {
                this.$sound.play('error.mp3')
                this.$dtoast.pop({
                    preset: "error",
                    heading: `Bank Error`,
                    content:  `Error while remoing your bank.. Please, try again`,
                })
                return;
              }

              this.$sound.play('success.mp3')
                this.$dtoast.pop({
                        preset: "success",
                        heading: `Success!`,
                        content:  `You successfully removed this bank`,
                })

              this.bankDetails.splice(index, 1)

            //  this.toggleBankDetails('bank-add-inputs');
          })
        },

        toggleBankDetails(collapseId) {
            this.$root.$emit('bv::toggle::collapse', collapseId);
        },
        checkFields(obj) {
            const {accountHolder, currency, iban, bic, bankName, bankAddress} = obj;
            return accountHolder != null && currency != null && iban != null && bic != null && bankName != null && bankAddress != null;
        },
        addBankDetails() {
            if(this.checkFields(this.bankInputs)) {
                const {accountHolder, currency, iban, bic, bankName, bankAddress} = this.bankInputs;

                var bank = {
                    accountHolder: accountHolder,
                    currency: currency,
                    iban: iban,
                    bic: bic,
                    bankName: bankName,
                    bankAddress: bankAddress
                }

                this.$http.post(`/api/user/bank/add`, {bank: bank}).then((res) => {
                  res = res.data

                  if(res.id == undefined) {
                    this.$sound.play('error.mp3')
                    this.$dtoast.pop({
                        preset: "error",
                        heading: `Bank Error`,
                        content:  `Error while adding your bank.. Please, try again`,
                     })
                    return;
                  }

                  bank.id = res.id
                  this.$sound.play('success.mp3')
                    this.$dtoast.pop({
                            preset: "success",
                            heading: `Success!`,
                            content:  `You successfully added a new bank account`,
                    })


                  this.bankDetails.push(bank);

                  this.bankInputs.accountHolder = null;
                  this.bankInputs.currency = null;
                  this.bankInputs.iban = null;
                  this.bankInputs.bic = null;
                  this.bankInputs.bankName = null;
                  this.bankInputs.bankAddress = null;
                  this.toggleBankDetails('bank-add-inputs');
              })
            }
        },
        saveBankDetails(index) {
            alert(index);
        }
    },
    computed: {
        bankInputsHolderState() {
            return this.bankInputs.accountHolder != null;
        },
        bankInputsIban() {
            return this.bankInputs.iban != null;
        },
        bankInputsBic() {
            return this.bankInputs.bic != null;
        },
        bankInputsBankName() {
            return this.bankInputs.bankName != null;
        },
        bankInputsBankAddress() {
            return this.bankInputs.bankAddress != null;
        }
    }
}
</script>

<style lang='scss'>
.vs--disabled .vs__dropdown-toggle, .vs--disabled .vs__clear, .vs--disabled .vs__search, .vs--disabled .vs__selected, .vs--disabled .vs__open-indicator {
  background-color: transparent !important;
  cursor: not-allowed !important;
  color: #84949a !important;
}

.vs--disabled.vs__dropdown-toggle {
  display:none !important;
}


.bank-details {
    border: 1px solid #424a4d;
    margin: 20px 0px !important;
    border-radius: 5px;

    .bank-toggler {
        color: #84949a;
        padding: 20px;
        border: none !important;
        background-color: transparent;
        font-size: 16px !important;
    }

    .bank-toggler:hover,
    .bank-toggler:active,
    .bank-toggler:focus {
        color: #84949a !important;
        background: transparent !important;
    }

    .bank-details-data {
        padding: 0px 20px;
    }
}
</style>
