<template>
    <div class="w-100 h-100">
     <!-- SENDING TOPBAR !-->
     <portal :to="windID">
      <i class="font-icon icon_new_order"></i>

      <div style="margin-left:5px" class="topbar-component custom">
         <span @click="stockChangeShow = !stockChangeShow" class="stock-select-btn">{{$grid.getWndID(this.windID).color.additional.symbol}} <v-icon>mdi-chevron-down</v-icon></span>

          <div v-if="stockChangeShow" class="selectDropdown">
            <InstrumentsSelect @stockSelected="stockChanged"/>
          </div>
      </div>
    </portal>
    <!-- END OF TOPBAR !-->

    <Graph
           :key="$store.state.done.window_resized || stock"
           :stock="stock"
           :link="link" :wndID="windID"
           @doneLoading="loadedChart = true"
           @stockChanged="stockChanged" />
  </div>
</template>


<style lang="scss">
  .quick {
    position: absolute;
    z-index:999;
    margin: 0.52em 3.9em;
  }
</style>

<script>
import Graph from '../Graph/Graph.vue';
import InstrumentsSelect from '../InstrumentsSelect.vue';

export default {
  name : "Chart",

  components: {
    Graph,InstrumentsSelect
  },

  data () {
    return {
      stock: this.$grid.getWndID(this.windID).color.additional.symbol,
      stockChangeShow: false,
      loadedChart : false,
    }
  },

  props : {
    windID : String,
  },

  methods: {
    stockChanged(e) {
      if(typeof(e) === 'object')
        e = e.api_name

       this.$grid.updateCellById(this.windID, 'symbol', e)

       this.stockChangeShow = false
       this.stock = e
    },
  },

  mounted() {

  },

  created() {

  },

  beforeDestroy() {
    console.log('Im gay and my ID is', this.windID)
  }
}
</script>
