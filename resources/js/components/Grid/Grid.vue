<template>
    <div class="grid">
        <grid-elelement
            v-if="workspace_data"
            :data="{ size: 1, el: workspace_data }"
            :index="0"
        />
        <div class="drop-zones">
            <drop
                v-for="zone in dropZones"
                :key="zone"
                @drop="$grid.drop($event, zone)"
                :class="zone"
            />
        </div>
    </div>

</template>

<script>
import { Drop } from "vue-easy-dnd"
import GridElelement from "./GridElelement.vue"
import {grid} from '../../plugins/grid.js';

export default {
    components: { Drop, GridElelement },
    name: "Grid",
    data: () => ({
        dropZones: ["top", "bottom", "left", "right"],
        workspace_data: null
    }),

    methods: {

    },


    beforeMount() {
      let th = this

      if(this.$store.state.done.workspace_loaded)
        th.workspace_data = th.$store.getters.get_workspace_data({wid: th.$store.getters.get_active_workspace})

      this.$store.subscribe((mutation, state) => {
        if(mutation.type == 'update_whole_workspace'
            || mutation.type == 'update_workspace_data'
              || mutation.type == 'set_active_workspace') {
              th.workspace_data = th.$store.getters.get_workspace_data({wid: th.$store.getters.get_active_workspace})
            }
      })
    },

    mounted() {
        // Bad practice move to Vuex
        //this.$grid.$on("move", this.$grid.move)
        //this.$grid.$on("remove", this.$grid.remove)
    },
    destroyed() {
        // Bad practice move to Vuex
        //this.$grid.$off("move", this.$grid.move)
        //this.$grid.$off("remove", this.$grid.remove)
    },
}
</script>

<style scoped>
.grid {
    width: 100%;
    height: 100%;

    position: relative;
}
.button {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 1;
}

.drop-zones > * {
    position: absolute;
}

.top {
    top: 0;
    left: 5%;
    right: 5%;
    height: 5%;
    z-index: -9999;
}

.bottom {
    bottom: 0;
    left: 5%;
    right: 5%;
    height: 5%;
    z-index: -9999;
    left: 0;
}

.left {
    top: 0;
    width: 5%;
    bottom: 0;
    z-index: -9999;
}

.right {
    top: 0;
    right: 0;
    width: 5%;
    bottom: 0;
    z-index: -9999;
}
</style>
