<template>
    <div class="dashboard-component p-2 col-12 col-lg-11 col-md-11" style="max-width: 425px !important; ">
        <h5 class="mb-4 text-center header-label">  Tax identification numbers</h5>

        <div class="text-center">
            <label class='header-label'>Do you have a Tax Identification Number?</label>

            <b-form-group
            id='tax-id-checkboxes'
            class="mt-4"
            >
                <b-form-radio 
                v-model="taxIdCheckbox" 
                :class='taxIdCheckbox === `Yes` ? `selected-variant` : ``'
                name="some-radios" 
                value="Yes"
                >Yes
                </b-form-radio>

                <b-form-radio 
                v-model="taxIdCheckbox" 
                :class='taxIdCheckbox === `No` ? `selected-variant` : ``'
                name="some-radios" 
                value="No"
                >No
                </b-form-radio>
            </b-form-group>
        </div>

        
        <label class='label-group mt-3' style="text-align: left !important;">
            This information is required so that we comply with the Common Reporting Standard.
        </label>

        <label class='header-label mt-3' style="text-align: left !important;">
            Please select {{ taxIdCheckbox === 'Yes' ? 
                'the country where you are a Tax Resident and provide your Tax Identification Number.' : 
                'the reason why you do not have a Tax Identification Number:' 
            }}
        </label>

        <div class="d-flex" v-if="taxIdCheckbox === 'Yes'">
            <div role="group" class="w-100 p-2 mb-3">
                <v-select
                    class="cus-input no-focus-outline"
                    v-model='selectedCountry'
                    :options='countryOptions'
                    :class="selectedCountry != null ? 'v-select-valid' : 'v-select-no-valid'"
                    :placeholder='`Country`'
                    required
                ></v-select>
            </div>

            <div role="group" class="w-100 p-2 mb-3">
                <b-form-input 
                    class="cus-input no-focus-outline"
                    type="text"
                    v-model='taxIdInput'
                    :state='taxIdInputState'
                    placeholder="Tax Id Number"
                ></b-form-input>
            </div>
        </div>

        <div v-if="taxIdCheckbox === 'No'">
            <div role="group" class="w-100 mb-3">
                <v-select
                    class="cus-input no-focus-outline"
                    v-model='taxIdReason'
                    :options='taxIdReasonOptions'
                    :class="taxIdReason != null ? 'v-select-valid' : 'v-select-no-valid'"
                    :placeholder='`Reason`'
                    required
                ></v-select>
            </div>
        </div>

        <div>
            <b-alert show variant="info" style="font-size: 14px !important;">
                <v-icon style="font-size: 22px !important;">mdi-information</v-icon>
                    In case any of the information you have provided changes in the future, or appears to be incorrect, we kindly ask that you inform us accordingly.
            </b-alert>
        </div>

        <OptionBtns
            v-if="taxIdCheckbox != null"
            :hideBack='true'
            @submitEvent='TaxIdentification'
            @returnEvent="$emit('return')"
        />
    </div>
</template>

<script>
import OptionBtns from '../OptionBtns.vue';
import countryJson from '../../../../json/countries.json';

export default {
    name: 'TaxIdentification',
    components: {
        OptionBtns
    },
    data() {
        return {
            taxIdCheckbox: 'No',
            selectedCountry: null,
            countryOptions: countryJson,
            taxIdInput: null,
            taxIdReason: null,
            taxIdReasonOptions: [
                "My country doesn't issue TAX IDs",
                "I am not required to register for a TAX ID"
            ],
        }
    },
    methods: {
        TaxIdentification() {
            if(this.taxIdCheckbox != null) {
                switch(this.taxIdCheckbox) {
                    case 'Yes':
                        if((this.selectedCountry != null && this.taxIdInput != null)) {
                            alert(`${this.selectedCountry} ${this.taxIdInput}`);
                        }
                    break;
                    case 'No':
                        if(this.taxIdReason != null) {
                            alert(this.taxIdReason);
                        }
                    break;
                }
            }
        }
    },
    computed: {
        taxIdInputState() {
            return this.taxIdInput != null;
        }
    }
}
</script>

<style lang='scss'>
#tax-id-checkboxes {

    .selected-variant {
        border-color: #00b894 !important;
    }

    .custom-control {
        transition: border-color .25s ease-in-out !important;
        padding: 14px !important;
        margin: 20px 10px !important;
        background-color: #2d3436;
        display: inline;
        border: 1px solid #2d3436;
        border-radius: 100px;
        padding-left: 42px !important;
        font-size: 14px;
        
        .custom-control-label {
            color: #f9fafb;
            width: 60px;
            line-height: 1.8;
            cursor: pointer;
            user-select: none !important;
        }

        .custon-control-input {
            padding: 10px !important;
            outline:none !important;
            outline-width: 0 !important;
            box-shadow: none !important;
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            border-radius: 100px;
            cursor: pointer;
        }

        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #00b894 !important;
        }
    }
}

@media only screen and (max-width: 375px) {
    #tax-id-checkboxes .custom-control {
        margin: 20px 5px !important;
    }
}
</style>
