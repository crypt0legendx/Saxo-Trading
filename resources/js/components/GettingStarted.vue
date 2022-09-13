<template>
    <div id='getting-started' class="mx-auto mt-3">
        <div id='steps' class="w-100">
            <b-progress :value="(100 / maxSteps) * activeStep" :max='100' style="background-color: #404040;"></b-progress>
            <div id='step-btns' class='absolute-wh-center col-12 d-flex justify-content-around'>
                <v-icon
                    v-for="index in maxSteps"
                    :key="index"
                    :style="{ color: visibleStep >= index ? '#29cf5b' : '#404040' }"
                    class='col-1 text-center'
                    @click="changeStep(index)"
                >mdi-check-circle-outline</v-icon>
            </div>
        </div>
        <div id='starter-inputs' class='mt-5'>
            <div v-if="visibleStep === 1">
                <h4 class='text-left mb-4'>Personal Details</h4>
                <div role="group" class="w-100 p-1 mb-3">
                    <label for="select-nationality">Nationality</label>
                    <v-select
                        class="cus-input"
                        v-model="personalData.nationality"
                        :style="personalData.nationality | is_valid"
                        placeholder='Select your nationality'
                        :options="nationalitiesOptions"
                    ></v-select>
                </div>

                <div role="group" class="w-100 p-1 mb-3">
                    <label for="date-of-birth">Date of Birth</label>
                    <b-form-datepicker
                        id="date-of-birth"
                        class="no-focus-outline"
                        v-model="personalData.dateOfBirth"
                        :state='dateOfBirthState'
                        show-decade-nav="true"
                        hide-header="true"
                        :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                    ></b-form-datepicker>

                    <b-form-invalid-feedback id="date-of-birth-feedback">
                        Select your date of birth
                    </b-form-invalid-feedback>
                </div>

                <div role="group" class="w-100 p-1 mb-3">
                    <label for="mobile-phone">Your mobile phone</label>
                    <b-form-input
                        class='cus-input no-focus-outline'
                        id="mobile-phone"
                        v-model="personalData.mobilePhone"
                        :state="mobilePhoneState"
                        aria-describedby="mobile-phone-feedback"
                        placeholder="Your mobile phone number"
                        trim
                        required
                    ></b-form-input>

                    <b-form-invalid-feedback id="mobile-phone-feedback">
                        Enter a valid phone number
                    </b-form-invalid-feedback>
                </div>
            </div>

            <div v-if="visibleStep === 2">
                <h4 class='text-left mb-4'>Residential Address</h4>
                <div role="group" class="w-100 p-1 mb-3">
                    <label for="street">Street & Number</label>
                    <b-form-input
                        class='cus-input no-focus-outline'
                        id="street"
                        v-model="residence.streetNumber"
                        :state="streetNumberState"
                        aria-describedby="street-feedback"
                        placeholder="Enter your street & number"
                        trim
                        required
                    ></b-form-input>

                    <b-form-invalid-feedback id="street-feedback">
                        Field is empty or invalid
                    </b-form-invalid-feedback>
                </div>

                <div role="group" class="w-100 p-1 mb-3">
                    <label for="postal">Postal/Zip Code</label>
                    <b-form-input
                        class='cus-input no-focus-outline'
                        id="postal"
                        v-model="residence.postalCode"
                        :state="postalCodeState"
                        aria-describedby="postal-feedback"
                        placeholder="Enter your postal/zip code"
                        trim
                        required
                    ></b-form-input>

                    <b-form-invalid-feedback id="postal-feedback">
                        Field is empty or invalid
                    </b-form-invalid-feedback>
                </div>

                <div role="group" class="w-100 p-1 mb-3">
                    <label for="city">City/Town</label>
                    <b-form-input
                        class='cus-input no-focus-outline'
                        id="city"
                        v-model="residence.city"
                        :state="cityState"
                        aria-describedby="city-feedback"
                        placeholder="Enter your city/town"
                        trim
                        required
                    ></b-form-input>

                    <b-form-invalid-feedback id="city-feedback">
                        Field is empty or invalid
                    </b-form-invalid-feedback>
                </div>
            </div>

            <div v-if="visibleStep === 3">
                <h4 class='text-left mb-4'>Employment Information</h4>
                <div role="group" class="w-100 p-1 mb-3">
                    <label for="employment-status">Employment Status</label>
                    <v-select
                        class="cus-input"
                        v-model="employment.status"
                        :style="employment.status | is_valid"
                        placeholder='Select employment status'
                        :options="employmentStatusOptions"
                        required
                    ></v-select>
                </div>

                <div role="group" class="w-100 p-1 mb-3">
                    <label for="industry">
                        {{
                            employment.status === 'Unemployed' || employment.status === 'Retired' ?
                            'Previous Industy' : 'Industry'
                        }}
                    </label>
                    <v-select
                        class="cus-input"
                        v-model="employment.industry"
                        :style="employment.industry | is_valid"
                        placeholder='Select industry'
                        :options="industriesOptions"
                        required
                    ></v-select>
                </div>

                <div role="group" class="w-100 p-1 mb-3" v-if='employment.industry != null'>
                    <label for="industry-position">
                        Employment position
                    </label>
                    <v-select
                        class="cus-input"
                        v-model="employment.industryPos"
                        :style="employment.industryPos | is_valid"
                        placeholder='Select position'
                        :options="industryPositionsOptions"
                        required
                    ></v-select>
                </div>

                <div role="group" class="w-100 p-1 mb-3">
                    <label for="education">What is your level of education?</label>
                    <v-select
                        class="cus-input"
                        v-model="employment.education"
                        :style="employment.education | is_valid"
                        placeholder='Select education level'
                        :options="educationLevelOptions"
                        required
                    ></v-select>
                </div>
            </div>

            <div v-if="visibleStep === 4">
                <h4 class='text-left mb-4'>Financial Information</h4>

                <div role="group" class="w-100 p-1 mb-3">
                    <label for="anual-income">Anual Income</label>
                    <v-select
                        class="cus-input"
                        v-model="financial.anualIncome"
                        :style="financial.anualIncome | is_valid"
                        placeholder='Select anual income'
                        :options="anualIncomeOptions"
                        required
                    ></v-select>
                </div>

                <div role="group" class="w-100 p-1 mb-3">
                    <label for="estimated-net-worth">
                        Estimated Net Worth (excluding primary residence)
                    </label>
                    <v-select
                        class="cus-input"
                        v-model="financial.estimatedNetWorth"
                        :style="financial.estimatedNetWorth | is_valid"
                        placeholder='Select estimated net worth'
                        :options="anualIncomeOptions"
                        required
                    ></v-select>
                </div>

                <div role="group" class="w-100 p-1 mb-3">
                    <label for="source-wealth">Source of Wealth</label>
                    <v-select
                        class="cus-input"
                        v-model="financial.sourceWealth"
                        :style="financial.sourceWealth | is_valid"
                        placeholder='Select source of wealth'
                        :options="sourceWealthOptions"
                        required
                    ></v-select>
                </div>

                <div role="group" class="w-100 p-1 mb-3">
                    <label for="expected-country-of-funds">
                        Expected country of origin (and destination) of funds
                    </label>
                    <v-select
                        class="cus-input"
                        v-model="financial.expectedCountryOfOriginOfFunds"
                        :style="financial.expectedCountryOfOriginOfFunds | is_valid"
                        placeholder='Select expected country of origin funds'
                        :options="expectedCountryOfFundsOptions"
                        required
                    ></v-select>
                </div>

                <div role="group" class="w-100 p-1 mb-3">
                    <label for="expect-fund-next-months">
                        How much do you expect to fund in the next 12 months?
                    </label>

                    <v-select
                        class="cus-input"
                        v-model="financial.expectedFunds"
                        :style="financial.expectedFunds | is_valid"
                        placeholder='Select expected funds'
                        :options="expectToFundOptions"
                        required
                    ></v-select>
                </div>
            </div>

            <!--
            <div v-if="visibleStep === 5">
                <h4 class='text-left mb-4'>Trading Account Settings</h4>
                <b-container class="bv-example-row">
                    <b-row>
                        <div role="group" class="w-100 p-1 mb-3 col-12 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label for="account-type">Account Type</label>
                            <v-select
                                class="cus-input"
                                v-model="account.type"
                                :style="account.type | is_valid"
                                placeholder='Select account type'
                                :options="accountTypeOptions"
                                required
                            ></v-select>
                        </div>

                        <div role="group" class="w-100 p-1 mb-3 col-12 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label for="leverage">Leverage</label>
                            <v-select
                                class="cus-input"
                                v-model="account.leverage"
                                :style="account.leverage | is_valid"
                                placeholder='Select leverage'
                                :options="leverageOptions"
                                required
                            ></v-select>
                        </div>

                        <div role="group" class="w-100 p-1 mb-3 col-12 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label for="currency">Currency</label>
                            <v-select
                                class="cus-input"
                                v-model="account.currency"
                                :style="account.currency | is_valid"
                                placeholder='Select currency'
                                :options="currencyOptions"
                                required
                            ></v-select>
                        </div>
                    </b-row>
                </b-container>

                <div class='p-0 mt-3'>
                    <b-form-checkbox
                        id="risk-investments"
                        v-model="riskInvestments"
                        class="mb-3"
                        name="risk-investments"
                    >I verify that the objective of this account is speculative trading and that
                    I can afford to risk all of my invested amount based on my personal financial circumstances.
                    </b-form-checkbox>

                    <b-form-checkbox
                        id="read-understood"
                        v-model="readAndUnderstood"
                        class="mb-3"
                        name="read-understood"
                    >I declare that I have read, understood and accept the
                        <a href='#' class='font-weight-bold'>Client Agreement</a>,
                        <a href='#' class='font-weight-bold'>Order Execution Policy</a> and
                        <a href='#' class='font-weight-bold'>Conflicts of Interest Policy</a>.
                    </b-form-checkbox>
                </div>
            </div> -->

            <div class='d-flex'>
                <button class='btn getting-started-submit-btn mx-auto' @click="nextForm">Save and Continue</button>
            </div>
        </div>
    </div>
</template>

<style lang='scss'>
@import '../scss/_variables.scss';
@import "../../../node_modules/vue-select/src/scss/vue-select.scss";

body {
    background-color: $formsBodyBgColor;
}

@media only screen and (max-width: 425px) {
    #getting-started #starter-inputs {
        padding: 20px !important;
        border-radius: 0px !important;
        border-left: none !important;
        border-right: none !important;
    }
}

#getting-started {
    width: 100%;
    min-height: 100vh;
    padding-top: 5%;

    label {
        font-size: 14px !important;
    }

    a {
        color: #118ab2 !important;
    }

    a:hover {
        text-decoration: none;
    }

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

    #starter-inputs {
        border-radius: 15px;
        padding: 60px;
        padding-bottom: 0px;
        max-width: 768px !important;
        width: 100%;
        margin: auto;
        min-height: 300px;
        border: 1px solid #404040;
        border-radius: 10px;
        color: #A2A2A2;

        .v-select {
            background-color: #282828;
            padding: 0px !important;

            .vs__dropdown-toggle {
                height: 52px !important;
                padding-left: 4px !important;
                padding-right: 4px !important;

                .vs__selected-options .vs__selected,
                .vs__search {
                    color: #cccccc !important;
                }

                .vs__actions {
                    color: #cccccc !important;

                    .vs__clear {
                        line-height: 0 !important;
                        padding-right: 5px !important;
                    }

                    .vs__clear,
                    .vs__open-indicator {
                        fill: #A2A2A2 !important;
                    }
                }
            }

            .vs__dropdown-menu {
				background: #282828 !important;
                li,
                option {
					background: transparent !important;
                    transition: all .1s ease-in-out;
                    padding-top: 15px !important;
                    padding-bottom: 15px !important;
                    border-bottom: 1px solid #202020;
                    color: #cccccc !important;
                }

                li:hover,
                li:focus,
                li:active,
                option:hover,
                option:focus,
                option:active {
                    background-color: #202020 !important;
                }
            }

            .vs__dropdown-menu::-webkit-scrollbar {
                opacity: .4 !important;
                width: 5px;
                transition: all .1s ease-in-out;
            }

            .vs__dropdown-menu::-webkit-scrollbar-track {
                box-shadow: inset 0 0 6px rgba(0, 0, 0, 0);
            }

            .vs__dropdown-menu::-webkit-scrollbar-thumb {
                background-color: #424242;
                border-radius: 2px;
            }
        }

        .cus-input {
            font-size: 13px;
            height: 52px;
            padding: 0px 16px;
            transition: all .05s ease-in;
            width: 100%;
            color: $topbar_secondary_fcolor;
            background-color: #282828;
            border-radius: 2px;
        }

        .invalid-feedback {
           font-size: 12px;
        }

        .getting-started-submit-btn {
            margin: 30px;
            height: 54px;
            background-color: #0984e3;
            color: #fff;
            font-weight: 500;
            border-radius: 100px;
            min-width: 280px;
        }
    }
}
</style>

<script>
import nationalitiesJson from '../json/nationalities.json';
import industriesJson from '../json/industries.json';
import empStatusesJson from '../json/empstatuses.json';
import educationJson from '../json/education.json';
import industriesPositionsJson from '../json/industry_positions.json';
import anualIncomeJson from '../json/anual_income.json';
import sourceWealthJson from '../json/source_wealth.json';
import countriesJson from '../json/countries.json';
import expectsToFundJson from '../json/expect_to_fund.json';
import accountTypesJson from '../json/account_types.json';
import currencyListJson from '../json/currency_list.json';
import leverageJson from '../json/leverage.json';

const generateCountriesData = () => {
    let countriesArray = [{ value: null, text: '...' }];
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

const generateOptions = (jsonFile) => {
    return [
        ...jsonFile.map(data => (data))
    ];
}

export default {
    name: 'GettingStarted',
    data() {
        return {
            visibleStep: 1,
            activeStep: 1,
            completedSteps: [],
            //maxSteps: 5,
            maxSteps: 4,
            nationalitiesOptions: generateOptions(nationalitiesJson),
            employmentStatusOptions: generateOptions(empStatusesJson),
            industriesOptions: generateOptions(industriesJson),
            industryPositionsOptions: generateOptions(industriesPositionsJson),
            educationLevelOptions: generateOptions(educationJson),
            anualIncomeOptions: generateOptions(anualIncomeJson),
            sourceWealthOptions: generateOptions(sourceWealthJson),
            expectedCountryOfFundsOptions: generateOptions(countriesJson),
            expectToFundOptions: generateOptions(expectsToFundJson),
            accountTypeOptions: generateOptions(accountTypesJson),
            leverageOptions: generateOptions(leverageJson),
            currencyOptions: generateOptions(currencyListJson),

            personalData: {
                nationality: null,
                dateOfBirth: null,
                mobilePhone: '',
            },
            residence: {
                streetNumber: '',
                postalCode: '',
                city: '',
            },
            employment: {
                status: null,
                industry: null,
                industryPos: null,
                education: null,
            },
            financial: {
                anualIncome: null,
                estimatedNetWorth: null,
                sourceWealth: null,
                expectedCountryOfOriginOfFunds: null,
                expectedFunds: null,
            },
            account: {
                type: null,
                leverage: null,
                currency: null
            },
            riskInvestments: false,
            readAndUnderstood: false
        }
    },
    methods: {
        changeStep(stepToChange) {
            if(this.completedSteps.indexOf(stepToChange) != -1 || this.activeStep === stepToChange)
                this.visibleStep = stepToChange;
        },
        pushStep() {
            if(this.completedSteps.indexOf(this.activeStep) === -1)
                this.completedSteps.push(this.activeStep);

            this.visibleStep++;
            this.activeStep++;
        },
        nextForm() {
            localStorage.setItem('activeStep', this.activeStep);
            this.activeStep = this.visibleStep;

            const {nationality, dateOfBirth, mobilePhone} = this.personalData;
            const {streetNumber, postalCode, city} = this.residence;
            const {status, industry, industryPos, education} = this.employment;
            const {anualIncome, estimatedNetWorth, sourceWealth, expectedCountryOfOriginOfFunds, expectedFunds} = this.financial;
            const {type, leverage, currency} = this.account;

            switch(this.activeStep) {
                case 1:
                    if(nationality && dateOfBirth && /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im.test(mobilePhone))
                        this.pushStep();
                    break;
                case 2:
                    if((/^(?:[A-Za-z]+)(?:[A-Za-z0-9 _]*)$/.test(streetNumber) && streetNumber.length > 4) &&
                    (/^[a-z0-9]+$/i.test(postalCode) && postalCode.length > 2) && (!/[^a-z]/i.test(city) && city.length > 2))
                        this.pushStep();
                    break;
                case 3:
                    if(status && industry && education && industryPos)
                        this.pushStep();
                    break;
                case 4:
                    if(anualIncome && estimatedNetWorth && sourceWealth && expectedCountryOfOriginOfFunds && expectedFunds) {

                        var objToSend = {
                          personalData: this.personalData,
                          residence: this.residence,
                          employment: this.employment,
                          financial: this.financial,
                        }

                        var that = this
                        this.$store.dispatch('changeUserData', {prop: 'user_data', val: JSON.stringify(objToSend)}).then((response) => {
                          if(response.data.user_data != undefined && response.data.user_data != null){

                            this.$dtoast.pop({
                            preset: "info",
                            heading: `Information Saved`,
                            content:  `Your information was saved.`,
                            })
                            

                            that.$router.push({ name: 'Dashboard' })
                          } else {
                             this.$dtoast.pop({
                                preset: "error",
                                heading: `Unexpected Error`,
                                content:  `Your request can't be processed right now.`,
                                })
                          }
                        }).catch(error => {
                            this.$dtoast.pop({
                                    preset: "error",
                                    heading: `Unexpected Error`,
                                    content:  `Your request can't be processed right now.`,
                                    })
                        })
                      }
                    break;
                case 5:
                    if(type && leverage && currency && this.riskInvestments && this.readAndUnderstood) {

                      var objToSend = {
                        personalData: this.personalData,
                        residence: this.residence,
                        employment: this.employment,
                        financial: this.financial,
                        account: this.account,
                      }

                      let that = this
                      this.$store.dispatch('changeUserData', {prop: 'user_data', val: JSON.stringify(objToSend)}).then((response) => {
                        if(response.data.user_data != undefined && response.data.user_data != null){
                          this.$dtoast.pop({
                            preset: "info",
                            heading: `Information Saved`,
                            content:  `Your information was saved.`,
                            })

                          setTimeout(function() {
                            window.location.href = "/";
                          }, 1000)
                        }
                      }).catch(error => {
                         this.$dtoast.pop({
                                    preset: "error",
                                    heading: `Unexpected Error`,
                                    content:  `Your request can't be processed right now.`,
                                    })
                      })
                    }
                    break;
            }
        },
    },
    computed: {
        nationalityState() {
            return this.personalData.nationality != null;
        },
        dateOfBirthState() {
            return this.personalData.dateOfBirth != null;
        },
        mobilePhoneState() {
            return /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im.test(this.personalData.mobilePhone);
        },
        streetNumberState() {
            return /^(?:[A-Za-z]+)(?:[A-Za-z0-9 _]*)$/.test(this.residence.streetNumber) &&
                this.residence.streetNumber.length > 4;
        },
        postalCodeState() {
            return /^[a-z0-9]+$/i.test(this.residence.postalCode) && this.residence.postalCode.length > 2;
        },
        cityState() {
            return !/[^a-z]/i.test(this.residence.city) && this.residence.city.length > 2;
        },
        empStatusState() {
            return this.employment.status != null;
        },
        industryState() {
            return this.employment.industry != null;
        },
        industryPositionState() {
            return this.employment.industryPos != null;
        },
        educationState() {
            return this.employment.education != null;
        },
        anualIncomeState() {
            return this.financial.anualIncome != null;
        },
        estimatedNetWorthState() {
            return this.financial.estimatedNetWorth != null;
        },
        sourceWealthState() {
            return this.financial.sourceWealth != null;
        },
        expectedCountryOfOriginOfFundsState() {
            return this.financial.expectedCountryOfOriginOfFunds != null;
        },
        expectedFundsState() {
            return this.financial.expectedFunds != null;
        },
        typeState() {
            return this.account.type != null;
        },
        leverageState() {
            return this.account.leverage != null;
        },
        currencyState() {
            return this.account.currency != null;
        }
    },
    filters: {
        is_valid(inputSelect) {
            return {
                border: `1px solid ` + (inputSelect != null ? '#28a745 !important' : '#dc3545 !important')
            }
        }
    }
}
</script>
