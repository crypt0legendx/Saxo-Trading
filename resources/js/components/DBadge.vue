<template>
    <div class="dbadge">
        {{counter}}
    </div>
</template>

<script>

export default {
    name: 'DBadge',
    props: {
        counter: Number,
        animated: Boolean,
    },

    data() {
        return {

        }
    }
}
</script>

<style lang="scss">
    .dbadge {
        width: 21px;
        height: 18px;
        display: flex;
        justify-content: center;
        border-radius: 3px;
        align-items: center;
        background: #a7a7a7;
        color: #161616;
    }
</style>
