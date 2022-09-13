<template>
    <Fragment>
        <Fragment v-for="(item, index) in items" :key="index">
            <drag :data="'swap-' + index">
                <drop @drop="onDrop($event.data, index)">
                    <slot name="item" :item="item" :index="index" />
                </drop>
            </drag>
        </Fragment>
    </Fragment>
</template>

<script>
import { Drag, Drop } from "vue-easy-dnd"
import { Fragment } from "vue-fragment"

export default {
    name: "SwapList",
    components: {
        Fragment,
        Drag,
        Drop
    },
    props: {
        items: Array
    },
    methods: {
        onDrop(drag, drop) {
            if(drag.startsWith && drag.startsWith("swap-")) {
                this.$emit("reorder", {
                    drag: drag.split("-")[1],
                    drop
                })
            }
        }
    }
}
</script>