<template>
    <div class="dashboard-component p-2 col-12 col-lg-11 col-md-11">
        <h5 class="mb-4 text-center header-label">Withdraw</h5>

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
        
        <OptionBtns
            :submitText="`Withdraw`"
            :backText='`Back`'
            @submitEvent='withdraw'
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
            firstName: '',
            lastName: '',
            cardNumber: '',
            cardMM_YY: '',
            cardCV: '',
        }
    },
    methods: {
        withdraw() {
            this.$emit('withdraw');
            this.$emit('return');
        }
    },
    computed: {
        firstNameState() {
            return !/[^a-z]/i.test(this.firstName) && this.firstName.length >= 4;
        },
        lastNameState() {
            return !/[^a-z]/i.test(this.lastName) && this.lastName.length >= 4
        },
        cardNumberState() {
            return /\b(?:\d{4}[ -]?){3}(?=\d{4}\b)/gm.test(this.cardNumber);
        },
        cardMM_YYState() {
            return /^(0[1-9]|1[0-2])\/([0-9]{2})$/g.test(this.cardMM_YY);
        },
        cardCVState() {
            return /^[0-9]{3,4}$/.test(this.cardCV);
        }
    }
}
</script>

<style>

</style>