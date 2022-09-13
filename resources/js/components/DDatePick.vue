<template>
    <div class="ddatepicker">

            <VueCtkDateTimePicker 
            :no-value-to-custom-elem="false"
            :dark="dark"
            :noHeader="true"
            format="YYYY-MM-DD HH:mm[Z]"
            formatted="YYYY-MM-DD HH:mm"
            color="#272727"
            v-model="value"
            @validate="onValid"
            >
            
            <input 
                    type="text" 
                    :value="value != null ? value.slice(0, -1) : value"
                    :class="['ddate-input', {'disabled': disabled}]"
                    readonly
                    :disabled="disabled == true"
            />
            </VueCtkDateTimePicker>

        

    </div>

</template>

<script>
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
export default {
    name: 'DDatePick',
    components: {VueCtkDateTimePicker},
    props: {
        value: String,
        disabled: Boolean,
        format: String,
        dark: Boolean,
    },

    data () {
        return {
          
        }
    },

    methods : {
       onValid() {
           this.$emit('input', this.value)
       }
    }

}
</script>

<style lang="scss">
.datepicker-container.is-dark .datepicker-today {
    background:none !important
}

.ddate-input {
    padding:5px 5px; 
    background: #1d1d1d; 
    color: #898989; 
    outline: none; 
    border:none; 
    height:22px; 
    width:140px; 
    font-size:13px;
}

.ddate-input.disabled {
    background: #2d2d2d;
    user-select:none; 
}

</style>
