<template>
    <div class="dashboard-component col-12 col-lg-11 col-md-11">
        <h5 class="mb-5 text-center header-label">
            {{ closeAccountActive ? 'Close Account' : 'Please tell us why are you leaving?' }}
        </h5>

        <div v-if='!closeAccountActive'>
            <b-form-checkbox-group
            id='close-account-reasons'
            v-model="selected"
            >
                <b-form-checkbox 
                :class="selected.indexOf(`I'm no longer interested in CFD trading`) != -1 ? 'selected-variant' : ''"
                value="I'm no longer interested in CFD trading">
                    I'm no longer interested in CFD trading
                </b-form-checkbox>

                <b-form-checkbox 
                :class="selected.indexOf(`I'm looking for other trading instruments`) != -1 ? 'selected-variant' : ''"
                value="I'm looking for other trading instruments">
                    I'm looking for other trading instruments
                </b-form-checkbox>

                <b-form-checkbox 
                :class="selected.indexOf(`I'm trading with another broker`) != -1 ? 'selected-variant' : ''"
                value="I'm trading with another broker">
                    I'm trading with another broker
                </b-form-checkbox>

                <b-form-checkbox 
                :class="selected.indexOf(`I'm not happy with the charges`) != -1 ? 'selected-variant' : ''"
                value="I'm not happy with the charges">
                    I'm not happy with the charges
                </b-form-checkbox>

                <b-form-checkbox 
                :class="selected.indexOf(`Other`) != -1 ? 'selected-variant' : ''"
                value="Other">
                    Other
                </b-form-checkbox>
            </b-form-checkbox-group>

            <b-form-textarea
            id="textarea"
            v-if="selected.indexOf('Other') != -1"
            v-model="otherReasonInput"
            placeholder="Other reason..."
            rows="3"
            max-rows="6"
            ></b-form-textarea>

            <h6 style="color: #d63031 !important;" v-if="selected.length === 0">
                Please specify the reason why you are leaving us
            </h6>

            <OptionBtns
                :hideBack="!(selected.length > 0)"
                @submitEvent="$emit('setActiveNav')"
                @returnEvent="openCloseAccount"
                :submitText="`Keep your account open`"
                :backText="`Continue`"
            />
        </div>

        <div v-if='closeAccountActive'>
            <b-form-group
            id='close-account-choice'
            >
                <b-form-radio 
                v-model="closeSelected" 
                :class='closeSelected === `Close this account` ? `selected-variant` : ``'
                name="some-radios" 
                value="Close this account"
                >Close this account
                </b-form-radio>

                <b-form-radio 
                v-model="closeSelected" 
                :class='closeSelected === `Close this account and delete personal data` ? `selected-variant` : ``'
                name="some-radios" 
                value="Close this account and delete personal data"
                >Close this account and delete personal data
                </b-form-radio>
            </b-form-group>

            <OptionBtns
                v-if='closeSelected != null'
                @submitEvent='closeAccountRequest'
                :submitText="`Submit`"
                :hideBack="true"
            />
        </div>
        


    </div>
</template>

<script>
import OptionBtns from '../OptionBtns.vue';

export default {
    name: 'CloseAccount',
    components: {
        OptionBtns
    },
    data() {
        return {
            selected: [],
            closeSelected: null,
            otherReasonInput: null,
            closeAccountActive: false,
        }
    },
    methods: {
        closeAccountRequest() {
            alert(1);
        },
        openCloseAccount() {
            this.closeAccountActive = true;
        }
    }
}
</script>

<style lang='scss'>
#close-account-reasons,
#close-account-choice {

    .selected-variant {
        border-color: #00b894 !important;
    }

    .custom-control {
        transition: border-color .25s ease-in-out !important;
        padding: 14px !important;
        margin: 20px auto !important;
        display: block !important;
        background-color: #2d3436;
        border: 1px solid #2d3436;
        border-radius: 100px;
        padding-left: 42px !important;
        font-size: 14px;
        
        .custom-control-label {
            color: #f9fafb;
            min-width: 100% !important;
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
</style>