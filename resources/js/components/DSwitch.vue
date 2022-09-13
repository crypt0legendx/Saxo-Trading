<template>
    <div class="switch-btn">
        <div @click="toggle('left')" :class="['left', {'active' : active == 'left'}]">{{left}}</div>
        <div @click="toggle('right')" :class="['right', {'active' : active == 'right'}]">{{right}}</div>
    </div>
</template>

<script>

export default {
    name: 'DSwitch',
    props: {
        left: String,
        right: String,
        active: String,
    },

    data () {
        return {

        }
    },

    methods: {
        toggle(e) {
            this.active = e
            this.$emit('onSelect', e)
        },
    }
    
}
</script>

<style lang="scss">
    .switch-btn {
        width:150px;
        height:20px;

        display:flex;

        .left, .right {
            border-radius: 3px;
            display:flex;
            justify-content: center;
            align-items: center;
            width:100%;
            background: #353535;
            color:#a7a7a7;

            &:hover {
                opacity: .7;
                cursor:pointer;
            }
        }

        .active {
            background: #d9d9d9;
            color: #000 ;
        }
    }
</style>
