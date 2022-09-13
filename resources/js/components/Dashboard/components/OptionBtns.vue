<template>
    <div class="w-100">
        <div class="w-100 d-flex mt-3" v-if="!hideSubmit">
            <button
            :disabled="submitDisabled"
            class='submit-btn mx-auto'
            @click="$emit('submitEvent')"
            >
            {{ submitText ? submitText : 'Save' }}
            </button>
        </div>

        <div class="w-100 d-flex mt-3" v-if="!hideBack">
            <button
            class="back mx-auto pr-4 pl-4"
            @click="$emit('returnEvent')"
            >
                <v-icon v-if="backText === 'Back'">
                    mdi-chevron-left
                </v-icon>
                {{ backText ? backText : 'Back' }}
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'OptionBtns',
    props: {
        hideSubmit: Boolean,
        submitText: String,
        submitDisabled: Boolean,
        backText: String,
        hideBack: Boolean
    },
}
</script>

<style lang='scss'>
.submit-btn,
.back {
    transition: .15s ease-in-out;
}

.submit-btn {
    background-color: #0984e3;
    padding: 14px 0px;
    color: #fff;
    font-weight: 500;
    border-radius: 100px;
    border: none;
    width: 100%;
}

.submit-btn:disabled {
    opacity: .8;
}

.submit-btn:hover {
    opacity: .8;
}

.back {
    height: 40px;
    margin-top: 10px;
    background-color: transparent;
    color: #0984e3;
    border: none;
    font-weight: 500;
}

.back:hover {
    background-color: rgba(#0984e3, 0.05);
}

@media only screen and (max-width: 768px) {
    .submit-btn {
        width: 100% !important;
    }
}
</style>
