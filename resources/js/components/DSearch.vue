<template>
    <div class="dsearchbox_container">
        <div v-if="!active && animFinished" @click="active = true" class="search-btn-open">
            <i class="font-icon icon_search"></i>
        </div>

        <transition
             v-on:before-enter="animFinished = false"
             v-on:after-leave="animFinished = true" 
             name="opensearch">
             
            <div v-if="active" class="dsearch-input">
                <b-input-group >
                    <b-form-input v-model="content" @input="handle" placeholder="Search for positions.."></b-form-input>
                    <b-input-group-append>
                    <b-input-group-text @click="active = false">
                        <i class="font-icon icon_search"></i>
                    </b-input-group-text>
                    </b-input-group-append>
                </b-input-group>
            </div>
        </transition>
    </div>
</template>
<script>
export default {
    name: "DSearch",

    data () {
        return {
            active: false,
            animFinished: true,
            content: '',
        }
    },

    methods: {
       handle() {
           this.$emit('input', this.content)
       }
    }
}
</script>

<style lang="scss">
    .opensearch-enter-active, .opensearch-leave-active {
         transition: transform .5s;
    }
    .opensearch-enter, .opensearch-leave-to  {
          transform: translateX(-170px);
    }
    .opensearch-enter-to, .opensearch-leave  {
          transform: translateX(0);
    }

    
    .dsearchbox_container {
        overflow:hidden;
        .search-btn-open {
            border-radius:5px;
            width:28px;
            height:22px;
            background: #353535;
            display:flex;
            justify-content: center;
            align-items: center;

            i {
                font-size:16px !important;
                color: #c2c2c2 !important;
            }

            &:hover {
                opacity: 0.7;
            }
        }
    }

    .dsearch-input {
        .input-group {
            height: 22px !important;

            input, .input-group-text{
                height:22px !important;
                background: #353535;
                border: #353535;
                color: #a7a7a7;
            }
        }
    }
</style>
