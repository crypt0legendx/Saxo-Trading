<template>
    <div
        class="wrapper"
        :style="{
            flex: data.size,
        }"
    >
        <cell
            v-if="data.el.type === 'cell'"
            :color="data.el.color"
            :key="data.el.id"
            :cellId="data.el.id"
        />
        <container
            v-if="data.el.type === 'container'"
            :orientation="data.el.orientation"
            :first="data.el.first"
            :second="data.el.second"
            :key="data.el.id"
            @resize="onResize"
        />
    </div>
</template>

<script>
import Cell from "./Cell.vue"
import Container from "./Container.vue"

export default {
    name: "GridElement",
    props: {
        data: Object,
        index: Number,
    },
    components: { Container, Cell },

    methods : {
      onResize(data) {
        this.$store.state.done.window_resized = !this.$store.state.done.window_resized
        this.$store.dispatch('sendWorkspace')
      }
    },
}
</script>

<style scoped>
.wrapper {
    height: 100%;
}
</style>
