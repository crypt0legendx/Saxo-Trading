<template>
  <div ref='dropdown' :style="dropdown_style" class="dropdown__">
    <div :style="items_style" class="list-content">
        <div v-for="(item, index) in items" @click="handleItemClick(index)" class="--custom stock-entry">
          <span style="width:100%" v-html="fitem(item)"></span>
        </div>

    </div>
  </div>
</template>

<style lang="scss" scoped>
@import '../scss/_variables.scss';

.dropdown__ {
  border-radius:5px;
  position: fixed;
  z-index: 99999;
  margin-top:-2px;
  margin: 0 !important;
  padding: 0 !important;
}

.list-content {
    background: $bg;
    max-height:158px;
    overflow-y:auto;
    margin: 0 !important;
    padding: 0 !important;
    text-align: left !important;

    &::-webkit-scrollbar {
      width: 5px;
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

.stock-entry {

    display:flex;
    justify-content:space-between !important;
    padding:3px 6px !important;
    margin: 0 !important;
    width: 100%;
    font-size: 13px !important;

    span {
      font-size:12px;
    }

    &:hover {
      background: $navbar_menus_bg_hover;
    }
}

.--custom {
  display:flex;
  width:180px;
  height:35px;
  font-size:13px;
  font-family: 'Roboto', sans-serif;
  align-items:center;
  justify-content:center;
}

.search_stocks {
  height:100%;
  width:100%;
  background: $bg;
  border:none;
  padding:5px;
  color:$topbar_fcolor;
  transition: .2s all;

  &:focus {
    border:none;
    outline:none;
  }
}

</style>

<script>
export default {
    name: "Dropdown",

    props : {
      items: Array,
      data : String,
      dropdown_style: String,
      items_style: String,
      expect: String,
    },

    data: function() {
      return {

      }
    },

    methods : {
      handleItemClick(id) {
        if(typeof(this.items[id]) != 'string')
          this.$emit('handleClick', {id: id, data: this.data, args: this.items[id].args})
        else
          this.$emit('handleClick', {id: id, data: this.data})
      },

       fitem(i) {
        if(typeof(i) == 'string')
          return i

        return i.content
      },
    },

    created() {

    },

    computed: {

    },

    filters: {

    }
}

</script>
