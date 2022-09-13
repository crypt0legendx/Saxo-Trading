<template>
    <div
        class="splitter"
        :class="{
            vertical: vertical,
            horizontal: horizontal,
        }"
        @mousedown="dragStart"
    >
        <div
            v-if="isDragged"
            class="splitter-drag"
            :style="{
                transform: dragTransform,
            }"
        ></div>
    </div>
</template>

<script>
export default {
    name: "Splitter",
    props: {
        orientation: String,
    },
    data: () => ({
        isDragged: false,
        dragPosition: 0,
        initialDragPosition: 0,
    }),
    computed: {
        horizontal() {
            return this.orientation === "horizontal"
        },
        vertical() {
            return this.orientation === "vertical"
        },
        dragTransform() {
            return `translate${this.horizontal ? "X" : "Y"}(${
                this.dragPosition
            }px)`
        },
    },
    methods: {
        dragStart(e) {
            document.addEventListener("mousemove", this.drag, false)
            document.addEventListener("mouseup", this.dragEnd)

            this.isDragged = true
            this.initialDragPosition = this.horizontal ? e.pageX : e.pageY
            this.dragPosition = 0
        },
        drag(e) {
            this.dragPosition =
                (this.horizontal ? e.pageX : e.pageY) - this.initialDragPosition
        },
        dragEnd() {
            document.removeEventListener("mousemove", this.drag, false)
            document.removeEventListener("mouseup", this.dragEnd)

            this.isDragged = false

            this.$emit("resize", this.dragPosition)

        },
    },
}
</script>

<style scoped>
.splitter {
    user-select: none;
    background: black;
    z-index: 1;
}

.splitter:hover {
    background-color: lightblue;
}

.vertical {
    height: 5px;
    width: 100%;
    cursor: ns-resize;
}

.horizontal {
    width: 5px;
    height: 100%;
    cursor: ew-resize;
}

.splitter-drag {
    width: 100%;
    height: 100%;
    background-color: lightblue;
}
</style>
