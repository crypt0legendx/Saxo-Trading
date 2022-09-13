<template>
    <div class="dashboard-component p-2 col-12 col-lg-11 col-md-11">
        <h5 class="mb-4 text-center header-label">Change {{ headerLabel }} Account Leverage</h5>

        <div role="group" class="w-100 mb-3">
            <label class='label-group'>{{ headerLabel }} account</label>
            <v-select
                class="cus-input"
                v-model="accSelected"
                :value="accSelected"
                label="text"
                :class="accSelected.value != null ? 'v-select-valid' : 'v-select-no-valid'"
                :placeholder='`Select ${headerLabel} account`'
                :options="accSelectOptions"
                @input="displayCurrLev"
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

        <OptionBtns
            @submitEvent='editLeverage'
            @returnEvent="$emit('return')"
            :backText='`Back`'
        />
    </div>
</template>

<script>
import OptionBtns from '../OptionBtns.vue';
import leverageJson from '../../../../json/leverage.json';

export default {
    name: 'EditLeverage',
    components: {
        OptionBtns
    },
    props: {
        headerLabel: String,
        accData: Array,
        accIndex: Number,
    },
    data() {
        return {
            accSelected: 0,
            accSelectOptions: [],
            levSelected: 0,
            levOptions: leverageJson
        }
    },
    methods: {
        editLeverage() {
            this.$emit('editLeverage', this.accData[this.accSelected.value].uuid.id, this.levSelected.split(':')[1]);
            this.$emit('return');
        },
        displayCurrLev() {
            if(this.accSelected.value != null)
                this.levSelected = this.accData[this.accSelected.value].leverage;
        }
    },
    mounted() {
        this.accSelectOptions = this.accData.map((obj, index) =>
            ({value: index, text: `${obj.uuid.type} ${obj.uuid.id} - ${obj.balance} ${obj.currency}`})
        );

        this.accSelected = this.accSelectOptions[this.accIndex];
        this.levSelected = this.accData[this.accIndex].leverage;
    }
}
</script>

<style lang='scss'>
</style>
