<template>
  <div :key="cellId" class="cell">
    <div v-if="shz && !isDragged" class="drop-zones">
      <drop v-for="zone in zones" :key="zone" @drop="drop($event, zone)" :class="zone" :accepts-data="(id) => id !== cellId" />
    </div>

    <drag @dragstart="onDrag" @dragend="onDrop" :data="cellId" :wnd='cellId' handle=".window-topbar" class="window-frame">

      <div class="window-topbar d-flex justify-content-between">

        <i style="font-size:1.4em !important" class="font-icon icon_order_drag_point"></i>

         <portal-target class="controls-left d-flex" :name="cellId">
         </portal-target>

        <div class="controls-right d-flex">
          <button class='btn-ctrl' @click="minimize" title='Minimize'>
            <v-icon>mdi-minus</v-icon>
          </button>
          <button class='btn-ctrl' @click="maximize" title='Maximize'>
            <v-icon v-if="!$store.state.workspace[$store.getters.get_active_workspace].prev">mdi-fullscreen</v-icon>
            <v-icon v-else>mdi-fullscreen-exit</v-icon>
          </button>
          <button class='btn-ctrl' @click="remove" title='Close'>
            <v-icon>mdi-close</v-icon>
          </button>
        </div>
      </div>

      <div :key="cellId" class="window_content">
        <component :key="cellId" ref="loaded" :is="loadedComponent" :windID="cellId"></component>
      </div>
    </drag>
  </div>
</template>

<script>
import { Drag, Drop } from "vue-easy-dnd"

export default {
  name: "Cell",

  data: () => ({
    maximized: false,

    zones: ["replace", "top", "bottom", "left", "right"],

    shz: false,
    isDragged: false,
    options: {
    },

  }),
  props: {
    cellId: String,
    color: Object,
  },
  methods: {
    onDrag() {
      this.showZones();
    },

    onDrop() {
      this.hideZones();

      let current = document.querySelector(`[wnd="${this.cellId}"]`);
      var cpy = current;

      current.style.position = 'relative';
      current.style.top = "auto"
      current.style.left = "auto"
    },


    showZones() {
      this.isDragged = true
      this.$store.commit('show_zones')
    },
    hideZones() {
      this.isDragged = false
      this.$store.commit('hide_zones')
    },
    drop(e, zone) {
      // Bad practice, move to Vuex
      //this.$root.$emit("move", e.data, this.cellId, zone)
      this.$grid.move(e.data, this.cellId, zone)
    },
    remove() {
      //this.$root.$emit("remove", this.cellId)
      this.$grid.remove(this.cellId)
    },

    minimize() {
      this.$store.dispatch('min_window', {loaded_component: this.color, id: this.cellId})
      this.remove()
    },

    maximize(e) {
      if(this.$store.state.workspace[this.$store.getters.get_active_workspace].prev == undefined)
        this.$store.state.workspace[this.$store.getters.get_active_workspace].prev = null

      if(this.$store.state.workspace[this.$store.getters.get_active_workspace].prev == null) {
        let cnt = this.$grid.findCellAndParentById(this.cellId)
        if(cnt.length > 0)
        cnt = cnt[0]

        let workspace = this.$store.getters.get_workspace_data({wid: this.$store.getters.get_active_workspace})
        this.$store.state.workspace[this.$store.getters.get_active_workspace].prev = workspace;
        this.$store.commit('update_workspace_data', {wid: this.$store.getters.get_active_workspace, data: null})

        if(cnt.color.additional == undefined)
        cnt.color.additional = null

        const cell = { id: this.cellId, type: "cell", color: { component : cnt.color.component, additional : cnt.color.additional}, }

        this.$store.commit('update_workspace_data', {wid: this.$store.getters.get_active_workspace, data: cell})
      } else {
        this.$store.commit('update_workspace_data', {wid: this.$store.getters.get_active_workspace, data: this.$store.state.workspace[this.$store.getters.get_active_workspace].prev})
        this.$store.state.workspace[this.$store.getters.get_active_workspace].prev = null;
        this.$store.commit('workspace_updated')
      }
    },
  },

  mounted() {
    let current = document.querySelector(`[wnd="${this.cellId}"]`);
    let topbar = current.querySelector(`.window-topbar`);

    topbar.addEventListener('dblclick', this.maximize);
  },

  created() {
    const that = this
    this.$store.subscribe((mutation, state) => {
      if(mutation.type == 'show_zones') {
        that.shz = true
      }
      if(mutation.type == 'hide_zones') {
        that.shz = false
      }
    })
  },
  components: {
    Drag,
    Drop,
  },
  computed: {
    topbarComponent() {
      return () => import(`${this.com}`)
    },

    loadedComponent () {
      return () => import(`../Windows/${this.color.component}`)
    },
  },
}
</script>

<style lang="scss">
@import '~/variables.scss';

.v-move {
  color: #7b7b7b;
  padding: 1px 5px;
}


.cell {
  width: 100%;
  height: 100%;
  position: relative;
}

.drop-zones > * {
  position: absolute;
  z-index: 999;
}
.drop-zones > *:hover {
  background: #216da7;
  opacity: 0.3;
}

.top {
  top: 0;
  left: 0;
  right: 0;
  height: 25%;
}

.bottom {
  bottom: 0;
  left: 0;
  right: 0;
  height: 25%;
}

.left{
  top: 0;
  left: 0;
  width: 25%;
  bottom: 0;
}

.right {
  top: 0;
  right: 0;
  width: 25%;
  bottom: 0;
}

.replace {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}

.removeBtn {
  position: absolute;
  z-index: 2;
}

.window-frame {
  display: table;
  table-layout: fixed;
  height:100%;
  width:100%;
  user-select:none;

  .window_content {

    height: calc(100% - 25px);
    overflow-y:auto;
    overflow-x:auto;

    &::-webkit-scrollbar {
      width: 5px;
      height: 5px;
    }

    /* Track */
    &::-webkit-scrollbar-track {
      background: none;
    }

    /* Handle */
    &::-webkit-scrollbar-thumb {
      background: #888;
    }

    /* Handle on hover */
    &::-webkit-scrollbar-thumb:hover {
      background: #555;
    }

  }

  .window-topbar {
    overflow:visible;
    width: 100%;
    height: 25px;
    background: $bg;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    cursor:move;

    .controls-left,
    .controls-right {
      color: #a7a7a7;
      position: relative;

      .btn-ctrl {
        color: #a7a7a7;
        margin: 0 5px !important;
        padding: 0 !important;
        background-color: transparent !important;
        outline: hidden;
        border: none;
      }

      .btn-ctrl:hover {
        color: #cccccc !important;
      }
    }

    .controls-left {
      overflow:visible;
      cursor: move;
      display: flex;
      flex: 1;
      align-items: center;
      text-overflow: ellipsis;
      white-space: nowrap;
      margin-right:3px;

      i {
        font-size:1.25em;
      }
    }

    .controls-right {
      right: 5px;
      z-index: 999;
      padding-left:5px;

      .dropdown-btn-ctrl-small {
        .dropdown-toggle {
          color: #a7a7a7;
          padding: 0 !important;
          background: transparent !important;
          border: none;
          outline:none !important;
          outline-width: 0 !important;
          box-shadow: none;
          -moz-box-shadow: none;
          -webkit-box-shadow: none;
        }

        .dropdown-menu {
          z-index: 999;
          min-width: 0 !important;
          max-height: fit-content !important;
          border: 1px solid $navbar_menus_bg;
          border-radius: 4px;
          padding: 0 !important;
          background-color: $navbar_menus_bg_hover;

          .dropdown-item {
            color: #a7a7a7;
            padding: 4px 6px !important;
          }

          .dropdown-item:hover {
            background-color: transparent;
            color: #cccccc;
          }
        }
      }
    }

    .dropdown-menu {
      max-height: fit-content !important;
      border: 1px solid $navbar_menus_bg;
      border-radius: 4px;
      padding: 0 !important;
      background-color: $navbar_menus_bg_hover;

      .dropdown-item {
        color: #a7a7a7;
        padding: 4px 6px !important;
      }

      .dropdown-item:hover {
        background-color: transparent;
        color: #cccccc;
      }

      .dropdown-menu {
        min-width: 0 !important;
        max-height: fit-content !important;
        border: 1px solid $navbar_menus_bg;
        border-radius: 4px;
        padding: 0 !important;
        background-color: $navbar_menus_bg_hover;

        .dropdown-item {
          color: #a7a7a7;
          padding: 4px 6px !important;
        }

        .dropdown-item:hover {
          background-color: transparent;
          color: #cccccc;
        }
      }
    }
  }
}

.topbar-heading {
  margin-right:15px;
}

.topbar-component.custom {
  display:flex;
  align-items: center;
}

.topbar-component .selectDropdown {
  position: fixed;
  margin-top:230px;
}

.topbar-component.select {
  select {
    -webkit-appearance: button;
    padding:0px 5px;
    outline: none;
    border:none;
    height:22px;
    width:120px;
    font-size:13px;

    &::-webkit-scrollbar {
        width: 4px;
        height:10px;
    }

    &::-webkit-scrollbar-track {
      border: none;

    }

    &::-webkit-scrollbar-thumb {
      border: none;
    }
  }
}

.topbar-component.switch {

}

.topbar-component {
  margin:0 5px;
}

.topbar-component.btn-prim button{
  -webkit-appearance: button;
  padding:0px;
  background: #216da7;
  color: #fff;
  outline: none;
  border:none;
  height:19px;
  width:80px;
  font-size:12px;
  transition: all .5s;
  &:hover {
    opacity: .7;
  }
}

</style>
