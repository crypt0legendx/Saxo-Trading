<template>
  <b-modal id='search-modal' ref="search-modal" size="lg" hide-footer no-close-on-backdrop centered no-fade>
    <template v-slot:modal-title><v-icon>mdi-magnify</v-icon> Search</template>
    <template v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
    <template v-slot:default>
      <v-container class="sel-ins-cnt">
        <div class="search">
          <input v-model='stocks_search' placeholder="Search for instruments..."/>
        </div>

        <b-button-group class='button-group-el mb-2' style='width: 100% !important;'>
          <b-button class='no-focus-outline' @click="onSectionChange(0)" :class='{active: activeBtn === 0}'>
            Instruments
          </b-button>

          <b-button class='no-focus-outline' @click="onSectionChange(1)" :class='{active: activeBtn === 1}'>
            Positions
          </b-button>

          <b-button class='no-focus-outline' @click="onSectionChange(2)" :class='{active: activeBtn === 2}'>
            Orders
          </b-button>
        </b-button-group>

        <template v-if="activeBtn == 0">
          <div class="stocks-list">

            <v-data-table v-columns-resizable :headers="instruments_headers" :items="populateData" :items-per-page="999999999" class="datatable dark" :mobile-breakpoint="0" @click:row="rowClick"> >
              <template v-slot:item.info="{ item }">
                <span @click.stop="$instInfo.show(item.instrument)" class="action_btns">
                  <v-icon>mdi-information-outline</v-icon>
                </span>
              </template>

              <template v-slot:item.actions="{ item }">
                <span @click.stop="stockClick(item.instrument, 0)" class="action_btns">
                  <v-icon>mdi-credit-card-plus-outline</v-icon>
                </span>
                <span @click.stop="stockClick(item.instrument, 1)" class="active_btns">
                  <v-icon>mdi-chart-areaspline</v-icon>
                </span>
              </template>

              <template v-slot:item.change="{ item }">
                <span :style="item.change > 0 ? 'color:green' : 'color:red'">{{item.change}}%</span>
              </template>

              <template v-slot:item.bid="{ item }">
                <span style="color: green">{{item.bid}}</span>
              </template>

              <template v-slot:item.spread="{ item }">
                <span :style="'color:' + liveColor(item.instrument, `spread`, item.spread)">{{item.spread}}</span>
              </template>

              <template v-slot:item.ask="{ item }">
                <span style="color: red">{{ item.ask }}</span>
              </template>
            </v-data-table>
          </div>
        </template>

        <template v-if="activeBtn == 1">
          <div class="stocks-list">
            <v-data-table v-columns-resizable :headers="positions_headers" :items="populateData" :items-per-page="999999999" class="datatable dark" :mobile-breakpoint="0" >
              <template v-slot:item.edit="{ item }">
                <div class="d-flex justify-content-center align-items-center">
                  <v-icon v-on:click="$posInfo.show(item.pid.substring(1))" class="edit">mdi-information-outline</v-icon>
                  <v-icon v-on:click="editOrder(item.pid)" class="edit">mdi-vector-polyline-edit</v-icon>
                </div>
              </template>
              <template v-slot:item.pl="{ item }">
                <span :style="'color:' + liveColorPosition(item.pid, `pl`, parseFloat(item.pl.toFixed(2)))">{{ parseFloat(item.pl.toFixed(2)) > 0 ? '+' : ''}} {{ item.pl.toLocaleString('en-US', { style: 'currency', currency: $store.getters.get_user_data('symbol')}) }}</span>
              </template>
            </v-data-table>
          </div>
        </template>
      </v-container>
    </template>
  </b-modal>
</template>

<script>
import {searchmodal} from '../../plugins/modals.js';

export default {
  name: 'SearchModal',
  methods: {

    rowClick(row) {
        this.stockClick(row.instrument, 1)
    },

    editOrder(id) {
      id = id.substring(1)
      let order = this.$store.getters.get_order(id)
      if(!order) return
      this.$editPos.show(order);
    },

    liveColor(stock, prop, val) {
      var color = 'gray';

      if(this.$store.state.cached[stock] == undefined)
        this.$store.state.cached[stock] = []

      if(this.$store.state.cached[stock][prop] == undefined)
        this.$store.state.cached[stock][prop] = 0


      if(val > this.$store.state.cached[stock][prop])
        color = 'green';

      if(val < this.$store.state.cached[stock][prop])
        color = 'red';


      this.$store.state.cached[stock][prop] = val

      return color;
    },

    liveColorPosition(pos, prop, val) {
      var color = 'gray';

      if(this.$store.state.cached[pos] == undefined)
        this.$store.state.cached[pos] = []

      if(this.$store.state.cached[pos][prop] == undefined)
        this.$store.state.cached[pos][prop] = 0


      if(val > this.$store.state.cached[pos][prop])
        color = 'green';

      if(val < this.$store.state.cached[pos][prop])
        color = 'red';


      this.$store.state.cached[pos][prop] = val

      return color;
    },


    spread(symb) {
      try {
        var stock = this.$store.getters.get_stock(symb)
        if(!stock) return 0.00

        var pip = Math.abs(stock.price.buy - stock.price.sell) * stock.price.scale

        return parseFloat(pip.toFixed(2))
      } catch(err) {
        return 0.00
      }
    },
    stockClick(stock, type) {
      if(type == 1) {
        this.$grid.add({
          component: 'Chart',
          additional : {
            symbol : stock,
            overlays : []
          }
        })
        this.hideModal();

      } else {
        this.$bidModal.show(stock)
        this.hideModal();
      }

    },

    onSectionChange(val) {
      this.activeBtn = val
    },

    showModal() {
      this.$refs['search-modal'].show();
      setTimeout(() => {
        document.querySelector('#search-modal___BV_modal_outer_ #search-modal').classList.toggle('light-theme', this.$store.state.light_theme);
        document.querySelector('#search-modal___BV_modal_outer_ #search-modal .modal-dialog .modal-content').style.opacity = '1';
      }, 150);
    },

    hideModal() {
      this.$refs['search-modal'].hide()
    },

  },
  beforeMount() {
    searchmodal.event.$on('show-search-modal', () => {
      this.showModal();
    })
  },

  data: function() {
    return {
      stocks_search: '',
      activeBtn: 0,

      instr_actions : `
      <span class="action_btns">
      <i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-credit-card-plus-outline theme--light"></i>
      <i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-chart-areaspline theme--light"></i>
      </span>
      `,

      instruments_headers: [
        { text: '', value: 'info', align: 'left', width: '10%'},
        { text: 'Instrument', value: 'instrument', align: 'left', width: '15%'},
        { text: 'Forex', value: 'category' , align: 'center', width: '15%' },
        { text: 'Change', value: 'change' , align: 'center', width: '15%' },
        { text: 'Bid', value: 'bid', align: 'center', width: '15%' },
        { text: 'Spread', value: 'spread', align: 'center', width: '15%' },
        { text: 'Ask', value: 'ask' , align: 'center', width: '15%' },
        { text: ``, value: 'actions' , align: 'center', width: '10%' },
      ],

      positions_headers: [
        { text: 'PID', value: 'pid', align: 'left', width: '10%' },
        { text: 'Instrument', value: 'instrument' , align: 'center', width: '25%' },
        { text: 'Direction', value: 'direction' , align: 'center', width: '15%' },
        { text: 'Volume', value: 'volume', align: 'center', width: '25%' },
        { text: 'Profit/Loss', value: 'pl', align: 'center', width: '25%' },
        { text: 'Edit', value: 'edit', align: 'center', width: '25%' },
      ],


      items: [
      ],
    }
  },


  computed: {
    stocks()
    {
      return this.$store.getters.get_stocks
    },
    populateData() {
      let source = this.$store.getters.get_stocks;

      if(this.activeBtn != 0)
      source = this.$store.getters.get_orders;

      var final = []
      if(this.activeBtn == 0) {
        final = []
        var th = this
        this.stocks.forEach((entry) => {
          var obj = {
            instrument: entry.name,
            api_name: entry.api_name,
            category: entry.category.toUpperCase(),
            change: entry.price.change,
            bid: entry.price.sell.toLocaleString('en-US', { style: 'currency', currency: th.$store.getters.get_user_data('symbol')}),
            spread: th.spread(entry.name),
            ask: entry.price.buy.toLocaleString('en-US', { style: 'currency', currency: th.$store.getters.get_user_data('symbol')}),
            actions: '',
          }

          final.push(obj)
        })
      } else if(this.activeBtn == 1) {
        final = []
        var th = this
        source.forEach((entry) => {
          if(entry.close_price != null)
            return

          var obj = {
            pid: '#' + entry.order_id,
            instrument: entry.stock.name,
            direction: entry.type == 'buy' ? 'Buy' : 'Sell',
            volume: entry.volume,
            pl: entry.pl,
          }

          final.push(obj);
        })
      }

      return final.filter(stock => {
        let concentrate = (stock.instrument + stock.api_name)
        return concentrate.toLowerCase().includes(this.stocks_search.toLowerCase())
      })
    },
  },

}
</script>

<style lang='scss' scoped>
  .stocks-list {
    max-height: 100%;
  }
</style>

<style lang='scss'>
@import '~/variables.scss';
@import '~/modal_css';
@import '~/datatables.scss';

#search-modal .modal-dialog .modal-content {
  opacity: 0;
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
.stocks-list {
  overflow-y: auto;
  max-height: 100%;
  font-size:12px;

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

  .stock {
    cursor:pointer;
    margin-top:2px;
    font-family: 'Roboto';
    padding:8px;
    display:flex;
    flex-direction:row;
    justify-content:space-between;
    align-items:center;

    color:$select_f;
    background: $input_search_stocks_bg;

    .stock_name {
      font-size:15px;
    }

    &:hover {
      background: $stock_hover;
    }

    .category {
      display:flex;
      align-items:center;
      font-size:14px;

      i {
        font-size:20px;
      }
    }
  }
}


.sel-ins-cnt {
  padding-left:0 !important;
  padding-right:0 !important;
}

.search {
  height:40px;
  width:100%;

  input {
    width:100%;
    background: $select_bg;
    color: $select_f;
    outline: none;
    border: none;
    padding: 5px 10px;
    font-size: 14px;
    height:35px;
  }
}
</style>
