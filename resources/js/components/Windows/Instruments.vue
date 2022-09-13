<template>
  <v-container class="w-100 h-100 fluid pa-0 instruments_comp col-12">
    <!-- SENDING TOPBAR !-->
     <portal :to="windID">
      <i class="font-icon icon_all_inst"></i>
      <span>Instruments</span>
    </portal>
    <!-- END OF TOPBAR !-->

    <v-list class="w-100">
      <v-list-item>
        <b-form-input v-model="search" placeholder="Search for instruments.." class="instruments_search" size="sm"></b-form-input>
      </v-list-item>

      <v-list-group v-for="(items, index) in filterInstruments" :key="index">
        <template v-slot:activator>
          <v-list-item-title>{{ index.toUpperCase() }}</v-list-item-title>
        </template>

        <v-list-item  @click="clickedInstrument(stock.name, $event)" v-for="(stock, s_index) in items" :key="stock.api_name" link>

          <template v-if="showWatchFor == stock.name">
            <v-list-item-title>
              <div :class="`watch no-hover-effect ${$store.getters.get_stock(stock.api_name).closed == true ? 'closed' : ''}`">
                <div class="watch-topbar">
                  <div class="left">
                    <span class="symbol">{{stock.name}}</span>
                  </div>
                  <div class="center" :style="$store.getters.get_stock(stock.api_name).price.change < 0 ? 'color:#d33623' : 'color:#109a21'">
                    {{$store.getters.get_stock(stock.name).price.change | changePercent}}
                  </div>

                  <div class="right">
                    <v-icon class="icon" @click="iconOrderClick(index, stock.api_name)">mdi-credit-card-plus-outline</v-icon>
                    <v-icon class="icon" @click="iconChartClick(index, stock.api_name)">mdi-chart-areaspline</v-icon>
                  </div>

                </div>

                <div class="watch-body">

                  <div class="sell-zone">
                    <div @click="order('sell', stock.api_name)" class="butt_content">
                      <span class="butt_method">Bid</span>
                      <span class="butt_price" v-html="$options.filters.formatPrice($store.getters.get_stock(stock.api_name).price.sell)"></span>
                      <span class="butt_lot">L:{{parseFloat($store.getters.get_stock(stock.api_name).price.low).toFixed(5)}}</span>
                    </div>
                  </div>

                  <div class="buy-zone">
                    <div @click="order('buy', stock.name)" class="butt_content">
                      <span class="butt_method">Ask</span>
                      <span class="butt_price" v-html="$options.filters.formatPrice($store.getters.get_stock(stock.api_name).price.buy)"></span>
                      <span class="butt_lot">H:{{parseFloat($store.getters.get_stock(stock.api_name).price.high).toFixed(5)}}</span>
                    </div>
                  </div>

                  <span class="spread">
                    {{ spread(stock) }}
                  </span>

                </div>

                <div class="watch-footer mt-1">
                  <v-select
                  :ref="_uid + '?' + index + '?' + s_index"
                  class="cus-dark-input no-focus-outline"
                  :options="[0.01,1,5,10,15,20,30,50,100]"
                  v-model="selectedVolume"
                  v-on:search:blur="volChange(index, s_index)"
                  :clearSearchOnBlur="clearSearchOnBlur"
                  placeholder="0.01"
                  taggable>
                </v-select>

              </div>
              <div v-if="$store.getters.get_stock(stock.name).closed" class="opensafter">
                <span>Opens in {{$store.getters.get_stock(stock.name).opens_in | after}}</span>
              </div>

            </div>
          </v-list-item-title>
        </template>
        <template v-else>
          <v-list-item-title>
            <div @dblclick="$bidModal.show(stock.api_name, 0, false)" class="minify-watch">
              <div class="left">
                <div @click="menuClicked(stock.name)" @mouseleave="menuClicked(null)" class="options">
                  <span class="icon"><v-icon>mdi-dots-vertical</v-icon></span>
                  <div style="position: relative; margin-left:-10px">
                    <Dropdown v-on:handleClick="itemMenuClicked" :items="additMenuItems" :data="stock.name" v-show="menu_opened == stock.name" />
                  </div>
                </div>

                <div style="color: #109a21" v-if="$store.getters.get_stock(stock.api_name).price.change > 0">
                  <v-icon>mdi-arrow-top-right</v-icon>
                </div>
                <div style="color: #d33623" v-else>
                  <v-icon>mdi-arrow-bottom-right</v-icon>
                </div>

                <span title="Instrument">{{stock.name}}</span>
              </div>

              <div title="Change%" :style = "$store.getters.get_stock(stock.api_name).price.change >= 0 ? 'color: #109a21' : 'color: #d33623'" class="center">
                {{stock.price.change | formatPercent}}%
              </div>

              <div class="right">
                <span :style="stock.price.change < 0 ? 'color:red' : 'color:green'" title="Ask price" v-html="$options.filters.formatPrice($store.getters.get_stock(stock.api_name).price.sell, stock.category)"/>
                <span title="Spread">{{ spread(stock.api_name) }}</span>
                <span :style="stock.price.change < 0 ? 'color:red' : 'color:green'" title="Bid price" v-html="$options.filters.formatPrice($store.getters.get_stock(stock.api_name).price.sell, stock.category)"/>
              </div>

            </div>
          </v-list-item-title>
        </template>

      </v-list-item>


    </v-list-group>
  </v-list>
</v-container>
</template>

<script>
import Dropdown from '../Dropdown'
export default {
  name : "Instruments",
  data () {
    return {
      search: '',
      showWatchFor: '',
      menu_opened : null,
      selectedVolume : 0.01,
      controls : {
        left : `
        <i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-format-list-bulleted theme--light"></i>
        <span style="font-size:13px;">Instruments</span>
        `,

        right : ``,
      },
      instruments : null
    }
  },
  components: {
    Dropdown,
  },

  props : {
    windID : Number,
  },

  methods: {
    volChange(cat, stock) {
      var current = this.$refs[this._uid + '?' + cat + '?' + stock][0].$el
      var search = current.querySelector('[type="search"]')

      this.selectedVolume = search.value
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
    clearSearchOnBlur : function ( clearSearchOnSelect, multiple ) {
      return false
    },
    iconOrderClick(index, symbol) {
      this.$grid.add({
        component: 'Chart',
        additional : {
          'symbol': symbol
        }
      })
    },

    iconChartClick(index, symbol) {
      this.$bidModal.show()
    },


    menuClicked(id) {
      if(id == this.menu_opened)
      this.menu_opened = null
      else
      this.menu_opened = id
    },

    itemMenuClicked(did) {
      this.menu_opened = null
      var id = did.id;
      var stock = did.data;
      if(did.args != undefined) {
        //assuming its add to wl

        this.$store.dispatch('addtoFavs', {catid: did.args[0], symbol: stock}).then(() => {
                this.$sound.play('success.mp3')
                this.$dtoast.pop({
                  preset: "info",
                  heading: `Watchlist Updated`,
                  content:  `You added ${stock} to the watchlist`,
                 })
                
        }).catch(error => {
                this.$sound.play('error.mp3')
                this.$dtoast.pop({
                  preset: "error",
                  heading: `Watchlist Error`,
                  content: `Instrument ${stock} is already added in this watchlist`,
                 })
         })
      }

      if(id == 0) {
        this.$bidModal.show(stock)
      } else if(id == 1) {
        this.$grid.add({ component: 'Chart', additional : { 'symbol': stock } })
      } else if(id == 2) {
        this.$instInfo.show(did.data);
      }

    },

    order(direction, symbol) {
      var stock = this.$store.getters.get_stock(symbol);
      if(!stock) return; //todo error

      var order = {
        stock_id : stock.api_name,
        volume : this.selectedVolume,
        type : direction,
        trade_type : 'market',
        sl : 0,
        tp: 0,
      }

      let th = this;

      direction = direction == "buy" ? 'Ask' : 'Bid'

      this.$store.dispatch('createOrder', order).then((res) => {
        if(res.error != undefined) throw res

        th.$sound.play('success.mp3')
        this.$dtoast.pop({
          preset: "success",
          heading: "Success!",
          content: `Placed ${direction} PID#${res.id} ${this.selectedVolume} of ${stock.name} at ${stock.price[order.type]}`,
        })
        
       th.$notifAgent.push(`Placed ${direction} PID#${res.id} ${this.selectedVolume} of ${stock.name} at ${stock.price[order.type]}`, 'positions');

        th.loadingSubmit = false
      }).catch(msg => {
        th.$sound.play('error.mp3')
        this.$dtoast.pop({
          preset: "error",
          heading: "Insufficent Funds",
          content: `Cannot ${direction} ${this.selectedVolume} of ${stock.name} because of insufficient funds.`,
        })
        th.$notifAgent.push(msg.error, 'positions');
        th.loadingSubmit = false
      })
    },

    clickedInstrument(symb, e) {
      if(e.target.classList.contains('v-icon')
      || e.target.classList.contains('cus-dark-input')
      || e.target.classList.contains('options')
      || e.target.classList.contains('vs__search')
      || e.target.classList.contains('vs__dropdown-toggle'))
      return

      if(this.showWatchFor == symb) {
        this.showWatchFor = null
        return
      }
      this.showWatchFor = symb
    },


    stocks() {
      const stocks = this.$store.getters.get_stocks
      this.instruments = this.groupBy(stocks, 'category')
    },

    groupBy(xs, key) {
      return xs.reduce(function(rv, x) {
        (rv[x[key]] = rv[x[key]] || []).push(x)
        return rv
      }, {})
    },
  },

  filters : {
    after(secs) {
      var date = new Date(0);
      date.setSeconds(secs);
      return date.toISOString().substr(11, 8);
    },

    formatPercent(val) {
      if(val >= 0)
      return '+' + val

      return val;
    },

    formatPrice(value, category)
    {
      if(isNaN(value))
        return 0.00

      if(category == 'forex')
        return value.toFixed(5)
      else
        return value.toFixed(2)
    },

    changePercent(val) {
      val = parseFloat(val).toFixed(2);

      if(val >= 0)
      val = '+' + val;


      return val + '%';
    }
  },

  computed: {
    additMenuItems() {
      var def = ['<i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-card-plus-outline theme--light"></i> Create New Order',
                 '<i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-finance theme--light"></i> Open Chart',
                 '<i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-information-outline theme--light"></i> Information']

       this.$store.state.user.vue_data.favourites.forEach((w, i) => {
        let item = {}
        item.content = '<i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-clipboard-list-outline theme--light"></i> '
        item.content += 'Add to ' + w.name

        item.args = []
        item.args.push(i)

        def.push(item)
       })
       return def
    },


    filterInstruments() {
      if(this.search != '')
      {
        let res = {}
        for(let key in this.instruments)
        {
          res[key] = this.instruments[key].filter((m) => {
            return m.api_name.toLowerCase().indexOf(this.search.toLowerCase()) !== -1
          })

          if(res[key].length == 0)
          delete res[key]
        }
        return res
      }
      else
        return this.instruments
    }
  },


  created() {
    this.$emit('sendControls', this.controls)
    this.stocks()
    const favorites = this.$store.state.user.vue_data.favourites

    // favorites.forEach((fav) => {
    //   this.menu_items.unshift('<i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-text-box-outline theme--light"></i> Add to ' + fav.name)
    // })
  },
}

</script>

<style lang="scss">
@import '~/variables.scss';

.opensafter {
  font-size:13px;
  color: #dddddd;
}


.v-select {
    background-color: #282828;
    padding: 0px !important;

    .vs__selected {
      margin: 0 !important;
    }

    .vs__dropdown-toggle {
        height: 30px !important;
        padding-left: 4px !important;
        padding-right: 4px !important;
        font-size:14px;
        margin:0 !important;

        .vs__selected-options {
            padding-top:0px;
            font-size:13px;
        }

        .vs__selected-options .vs__selected,
        .vs__search {
            color: #cccccc !important;
        }

        .vs__actions {
            display:none;
          }
    }

    .vs__dropdown-menu {
background: #282828 !important;
        li,
        option {
  background: transparent !important;
            font-size:14px;
            transition: all .1s ease-in-out;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
            border-bottom: 1px solid #202020;
            color: #cccccc !important;
        }

        li:hover,
        li:focus,
        li:active,
        option:hover,
        option:focus,
        option:active {
            background-color: #202020 !important;
        }
    }

    .vs__dropdown-menu::-webkit-scrollbar {
        opacity: .4 !important;
        width: 5px;
        transition: all .1s ease-in-out;
    }

    .vs__dropdown-menu::-webkit-scrollbar-track {
        box-shadow: inset 0 0 6px rgba(0, 0, 0, 0);
    }

    .vs__dropdown-menu::-webkit-scrollbar-thumb {
        background-color: #424242;
        border-radius: 2px;
    }
}

.instruments_comp {

  .instruments_search {
    width: 100%;
    outline: none;
    border: none;
    padding: 5px 10px;
    font-size: 14px;
    height: 35px;

    &:focus, &:active {
      outline: none;
      border: none;
    }
  }

  .options {
    font-size:15px;
    padding: 0 2px;
    cursor:pointer;
  }

  .minify-watch {
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-direction:row;

    padding: 0px;

    .left {
      display:flex;
      flex-direction:row;
      min-width: 150px;
      text-align:left;

      div {
        margin: 0 5px;
      }

      span {
        margin: 0 5px;
      }
    }

    .center {
      text-align:center;
    }

    .right {
      min-width: 400px;
      text-align: center;

      span {
        display: inline-grid;
        min-width: 120px;
      }
    }
  }

  .cus-dark-input {
    border: none;
    border-radius: 2px;
    max-height: 25px !important;
    -moz-appearance: textfield;

    &::-webkit-outer-spin-button,
    &::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    input {
      color: #a7a7a7;
    }



  }

  .--placeholder {
    display:flex;
    justify-content:center;
    align-items:center;
    background: $placeholderbutton_bg;
    cursor:pointer;

    i {
      font-size:36px;
      color:white;
      opacity:.5;
    }
  }

  .watch {
    min-width: 100%;

    .watch-footer {
      height:30px;
    }

    .icon {
      cursor:pointer;
      color:$topbar_fcolor;
      background:none;
      outline:none;
      border:none;
      box-shadow:none;

      &:hover, &:active, &:focus {
        background: $bg;
      }
    }

    user-select:none;
    color:white;
    height:100%;
    display:flex;
    flex-direction:column;
    width:100%;
    padding:0 5px;
    border-radius:1px;

    .watch-body {
      position:relative;
      display:flex;
      flex-direction:row;
      width:100%;
      height:80px;

      .spread {
        position: absolute;
        align-self: center;
        justify-self: center;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        padding: 1px 6px;
        font-size: 13px;
        min-width: 30px;
        background: #2c2c2c;
        border-radius: 2px;
        color: #dedede;
      }

      .butt_content {
        display:flex;
        flex-direction:column;
        margin:3.5px;

        .butt_method {
          font-size:13px;
          font-weight: 500;
        }

        .butt_lot {
          font-size:13px;
          opacity : .7;
        }

        .butt_price {
          font-size:17px;
          font-weight:300;
          margin-top:2.5px;
          margin-bottom:0px;
          font-family: "Roboto";
          letter-spacing:2px;

          span {
            font-size:24px;
          }
        }
      }

      .buy-zone {
        width: 100%;
        text-align: right;
        margin-left: 2px;
        display: flex;
        justify-content: flex-end;

        .butt_content {
          float:right;
          padding-right: 10px;
        }

        cursor:pointer;
      }

      .sell-zone {
        width: 100%;
        margin-right: 2px;
        cursor:pointer;
      }
    }


    .watch-topbar {
      justify-content:space-between;
      color: $select_f;
      align-items:center;
      text-align:center;
      background: none;

      .left {

      }

      .right {
        margin-right:40px;

        i {
          margin:0 2px;
        }
      }

      .center {
        font-size:13px;
        margin-left: 40px;
      }

      .symbol {
        margin-left:15px;
        font-size:13px;
      }

      display:flex;
      flex-direction:row;
    }


    &:hover {

    }
  }

  .v-list {
    min-width: fit-content;
  }

  .v-list-item {
    user-select:none;
    display: flex;
    padding: 10px 15px;
    font-family: Roboto;
    font-size: 13px;

    &:focus {
      border:none !important;
      outline: none !important;
    }

  }

  .v-list-group__items {
    .v-list-item {
      .v-list-item__title {
        width:100%;
      }

      background: rgb(25,25,25);
      cursor:pointer;

      div {
        padding-left:5px
      }

      &:hover {
        background: rgba(255,255,255, 0.05) !important;
      }
    }
  }
}
</style>
