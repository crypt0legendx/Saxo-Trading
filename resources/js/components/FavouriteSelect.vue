<template>
  <div>
    <span :class="['stock-select-btn', {'open': showStatus}]" @click.stop="toggle">{{current}}
        <i v-if="!showStatus" class="font-icon icon_arrow_small_down"></i>
        <i v-else class="font-icon icon_arrow_small_up"></i>
    </span>

    <vue-context ref="select" @close="showStatus = false">
        <div class="menu fav-select">
            <div :key="index" 
                 v-for="(cat,index) in $store.state.user.vue_data.favourites" 
                 :class="['menu-item', {'selected': cat.name == current}]"
                 @click="changeCurrent(cat.name)"
                 > 
                <div class="menu-item-icon">
                      <i :class="['font-icon', {'icon_watchlists_favorite': cat.name == 'Favourites'}, {'icon_watchlists': cat.name != 'Favourites'}]"></i>
                </div>

                <span class="menu-item-text">{{cat.name}}</span> 

                <div class="menu-item-children">
                  <i @click="edit(cat.name)" class="font-icon icon_edit_balance"></i>
                  <i @click="remove(cat.name)" class="font-icon icon_win_close"></i>
                </div>
            </div>

            <hr>
            
            <div class="menu-item"> 
                <div class="menu-item-icon">
                      <i class="font-icon icon_action_add"></i>
                </div>

                <span class="menu-item-text">Create New Watchlist</span> 
            </div>

        </div>
    </vue-context>
  </div>     
</template>

<script>
//TODO Edit/new functionality
import VueContext from 'vue-context';
export default {
    name: "FavouriteSelect",
    components: { VueContext },

    props : {
      current: String,
    },

    data: function() {
      return {
        showStatus: false,
      }
    },

    methods : {
    
    edit(e) {
        this.$emit('onEdit', e)
    },

    remove(e) {
        this.$store.commit('rm_fav_cat', e);
        this.$emit('onRemove', e)
    },

     changeCurrent(e) {
         this.$emit('onSelect', e)
     },

     toggle() {
        this.showStatus = !this.showStatus
        if(this.showStatus) this.$refs.select.open({clientY: 66})
            else
                this.$refs.select.close()
     }
    },

    mounted() {
    },

    computed: {
      
    }
}

</script>

<style lang="scss">
    .stock-select-btn.open {
        background: #585858;
    }

    .fav-select {
      width:200px;

      .menu-item-children {
          margin-right:5px;
      }

      .selected {
          cursor:default;
          background:#585858;
          
          .menu-item-children {
              display:none;
          }
      }
    }

</style>
