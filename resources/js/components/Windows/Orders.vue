<template>
  <div class="h-100 pa-0">
    <!-- SENDING TOPBAR !-->
     <portal :to="windID">
      <i class="topbar-heading font-icon icon_positions_orders"></i>

      <div class="topbar-component switch">
        <DSwitch
          left="Positions"
          right="Orders"
          :active="currTable == 'pos' ? 'left' : 'right'"
          @onSelect="topbarSwitchSelect"
        ></DSwitch>
      </div>

      <div class="topbar-component custom">
        <DBadge
          :counter="ordersCount"
        ></DBadge>
      </div>

      <div class="topbar-component select">
        <select v-model="direction">
          <option value="all" selected>All</option>
          <option value="buy">Buy</option>
          <option value="sell">Sell</option>
        </select>
      </div>

      <div class="topbar-component custom">
        <DSearch
          v-model="searchbox"
        ></DSearch>
      </div>
    </portal>
    <!-- END OF TOPBAR !-->

    <div class="h-100" v-if="currTable == 'pos'">

      <v-data-table ref="posTable"
                    dblclick:row="dblclickRow"
                    :headers="headers"
                    :items="filterSearchPositions"
                    :search="searchbox"
                    :items-per-page="999999999999"
                    style="height: 100%;"
                    class="datatable dark"
                    :mobile-breakpoint="0">

      <template v-slot:item.direction='{item}'>
        <span :style="{color: item.direction == 'buy' ? '#19a337' : '#d81720'}">
          {{ item.direction == 'buy' ? 'Buy' : 'Sell' }}
        </span>
      </template>

      <template v-slot:item.actions_edit="{item}">
        <div class="d-flex justify-content-center align-items-center">
          <v-icon @click="$posInfo.show(item.order_id)" class="edit">mdi-information-outline</v-icon>
          <v-icon @click="editOrder(item.order_id)" class="edit">mdi-vector-polyline-edit</v-icon>
        </div>
      </template>

      <template slot="header.actions" slot-scope="props">
        <span @click="closeall" v-html="closeall_btn"></span>
      </template>

      <template v-slot:item.created="{ item }">
        {{ item.created.toLocaleString() }}
      </template>

      <template v-slot:item.pips="{ item }">
        <span v-if="item.pips < 0" style="color: #d81720">
          {{ Number(item.pips).toFixed(2) }}
        </span>
        <span v-else style="color: #19a337">
          +{{ Number(item.pips).toFixed(2) }}
        </span>
      </template>

      <template v-slot:item.gross_profit="{ item }">
        {{ item.pl.toLocaleString('en-US', { minimumFractionDigits: 2, style: 'currency', currency: $store.getters.get_user_data('symbol') }) }}
      </template>

      <template v-slot:item.net_profit="{ item }">
        <span v-if="item.net_profit[0] == '-'" style="color: #d81720">
          {{ item.pl.toLocaleString('en-US', { minimumFractionDigits: 2, style: 'currency', currency: $store.getters.get_user_data('symbol') }) }}
        </span>
        <span v-else style="color: #19a337">
          +{{ item.pl.toLocaleString('en-US', { minimumFractionDigits: 2, style: 'currency', currency: $store.getters.get_user_data('symbol') }), }}
        </span>
      </template>

      <template v-slot:item.actions="{ item }">
        <div v-if="!$store.getters.get_stock(item.stock.api_name).closed">
          <button v-on:click="closeOrder(item.order_id)" v-if="item.net_profit[0] == '-'" class="btn-close negative">
            Close
          </button>
          <button v-on:click="closeOrder(item.order_id)" v-else class="btn-close positive">
            Close
          </button>
        </div>
        <div v-else>
          <button title="Market is closed" class="btn-close positive disabled">
            Close
          </button>
        </div>

      </template>
    </v-data-table>

  </div>
  <div class="h-100" v-else>
    <v-data-table ref="ordTable" :headers="headers" :search="searchbox" :items="filterSearchOrders" :items-per-page="999999999999" class="datatable dark h-100" :mobile-breakpoint="0">

      <template v-slot:item.direction='{item}'>
        <span :style="{color: item.direction == 'buy' ? '#19a337' : '#d81720'}">
          {{ item.direction == 'buy' ? 'Buy' : 'Sell' }}
        </span>
      </template>

    <template v-slot:item.created="{ item }">
      {{ item.created.toLocaleString() }}
    </template>

    <template v-slot:item.actions="{ item }">
      <button v-on:click="closeOrder(item.order_id)" v-if="item.net_profit[0] == '-'" class="btn-close negative">
        Close
      </button>
      <button v-on:click="closeOrder(item.order_id)" v-else class="btn-close positive">
        Close
      </button>
    </template>

  </v-data-table>
  </div>
</div>
</template>

<script>
import {tableresizable} from '../../plugins/table-resizable.js'
import DSwitch from '../DSwitch.vue'
import DBadge from '../DBadge.vue'
import DSearch from '../DSearch.vue'

export default {
  name : "Orders",


  data () {
    return {
      searchbox: '',
      direction: 'all',
      currTable: 'pos',
      period: 'all_time',
      ordersCount: 0,

      closeall_btn: `
      <button class="btn-close closeall">
      Close All
      </button>
      `,


      edit : `

      `,

      actions_edit: `
      <button class="btn-close">
      Edit
      </button>
      `,

      headers: [
        { text: '', value: 'actions_edit', width: "5%" },
        { text: 'PID', value: 'order_id', align: 'd-none d-md-table-cell', width: "5%" },
        { text: 'Instrument', value: 'instrument', align: 'center', width: "10%" },
        { text: 'Direction', value: 'direction', align: 'center', width: "10%" },
        { text: 'Volume', value: 'volume', width: "10%" },
        { text: 'Price', value: 'price', width: "10%" },
        { text: 'Current Price', value: 'current_price', width: "10%" },
        { text: 'S/L', value: 'sl', width: "10%" },
        { text: 'T/P', value: 'tp', width: "10%" },
        { text: `Created (UTC ${this.$store.getters.get_user_data('time_zone')})`, value: 'created', width:"10%" },
        { text: 'Commission', value: 'commission', width: "10%" },
        { text: 'Swap', value: 'swap', width: "5%" },
        { text: 'Pips', value: 'pips', width: "5%" },
        { text: 'Gross Profit', value: 'gross_profit', width: "10%" },
        { text: 'Net Profit', value: 'net_profit', width: "10%" },
        { text: `<div></div>`, value: 'actions', width: "10%" },
      ],

      ord_headers: [

        { text: 'Instrument', value: 'instrument', align: 'center', width: '10%' },
        { text: 'Type', value: 'type', align: 'center', width: '10%' },
        { text: 'Direction', value: 'direction', width: '5%' },
        { text: 'Description', value: 'description', width: '10%' },
        { text: 'Volume', value: 'volume', width: '10%' },
        { text: 'Entry Price', value: 'price', width: '10%' },
        { text: 'Current Price', value: 'current_price', width: '10%' },
        { text: 'Distance', value: 'distance', width: '10%' },
        { text: 'S/L', value: 'sl', width: '5%' },
        { text: 'T/P', value: 'tp', width: '5%' },
        { text: 'Created', value: 'created', width: '15%' },
        { text: '', value: 'cancel', width: '5%' },
      ],
    }
  },

  components: {
    DSwitch, DBadge, DSearch
  },

  props : {
    windID : Number,
  },

  methods: {
    topbarSwitchSelect(e) {
      this.currTable = e == 'left' ? 'pos' : 'ord'
    },
    dblclickRow(e, row)
    {
        this.editOrder(row.item.order_id)
    },
    handleRowClick(row) {
        this.editOrder(row.order_id)
    },
    formatDate(date) {
      //  date = new Date(date.getTime() + date.getTimezoneOffset() * 60000 + (3600000 * this.$store.getters.get_user_data('time_zone')))
        return date.getFullYear() + '-' +
                 date.getMonth().toLocaleString('en-US', {minimumIntegerDigits: 2}) + '-' +
                 date.getDate().toLocaleString('en-US', {minimumIntegerDigits: 2}) + ' ' +
                 date.getHours().toLocaleString('en-US', {minimumIntegerDigits: 2}) + ':' +
                 date.getMinutes().toLocaleString('en-US', {minimumIntegerDigits: 2})
    },
    updateOrdersCount() {
      //
    },


    closeall() {
      this.$store.getters.get_orders.forEach((order) => {
        if(order.close_price != null)
          return

        this.closeOrder(order.order_id)
      })
    },

    editOrder(id) {
      let order = this.$store.getters.get_order(id)
      if(!order) return
      this.$editPos.show(order);
    },

    closeOrder(id) {
      let order = this.$store.getters.get_order(id)
      if(!order) return

      let th = this
      this.$store.dispatch('closeOrder', id).then( (res) => {
        th.$sound.play('success.mp3')
        this.$dtoast.pop({
            preset: "success",
            heading: "Success!",
            content: `Closed position PID#${id} ${order.volume} of ${order.stock.name} at ${order.stock.price}`,
          })

        th.$store.dispatch('set_order', {id: id, order: res})
      }).catch(error => {
        th.$sound.play('error.mp3')
        this.$dtoast.pop({
          preset: "error",
          heading: "Unexpected Error",
          content: `Cannot close position ${order.direction} PID#${id} ${order.volume} of ${order.stock.name}`,
        })
      })
    },
  },

  computed : {
    filterSearchPositions() {
      let data = this.$store.getters.get_orders.filter(pos => {
        if(pos.type != 'market') return false
        if(pos.close_price != null) return false
        return pos.direction.toLowerCase().includes(this.direction.toLowerCase()) || this.direction == 'all'
      })

      this.ordersCount = data.length
      this.updateOrdersCount()
      return data
    },

    filterSearchOrders() {
      let data = this.$store.getters.get_orders.filter(pos => {
        if(pos.type == 'market') return false
        return pos.direction.toLowerCase().includes(this.direction.toLowerCase()) || this.direction == 'all'
      })

      this.ordersCount = data.length
      this.updateOrdersCount()
      return data
    },
  },

  mounted() {
    this.$resizable_table.init(this.$refs.posTable.$el)
  },

  created() {

  },
}
</script>

<style lang="scss">
@import '~/variables';
@import '~/datatables.scss';

.btn-close.disabled {
  border-color: gray;
  filter: grayscale(100%);
  opacity:0.7;
  cursor:not-allowed;
}


.actions {
    span {
      margin: 0px 5px;
    }
}

.positive {
  border: 1px solid #19a337;
  background: #19a337;
}

.negative {
  border: 1px solid #d81720;
  background: #d81720;
}

.cancel {
  border: 1px solid gray;
  background: gray;
}


.buy {
  background-color: #19a337 !important;
  color: #fff !important;
}

.sell {
  background-color: #d81720 !important;
  color: #fff !important;
}

.edit {
  margin: 0 3px;
  border: none;
  background: none;
  color: #a4a4a4;
  font-size: 16px;

  &:active, &:focus {
    border:none;
    outline: none;
  }

  &:hover {
    color:white;
  }

}

.buy,.sell,.edit {
  width: 60px;
  height: 25px;
  padding:3px;
}


.btn-close {
  outline: none;
  color: #fff;
  font-size: 12px;
  border-radius: 2px;
}

.btn-edit {
  border:1px solid #4885ed;
  outline: none;
  background: none;
  color: #a2a2a2;
  font-size: 12px;
  border-radius: 2px;

  &:hover {
    background: rgba(255,255,255,0.05);
  }
}

.closeall {
  border: none;
  background: #d81720 !important;
  color:white !important;

  &:hover {
    background: #c0392b;
  }
}

</style>
