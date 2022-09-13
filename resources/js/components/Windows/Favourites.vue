<template>
  <v-container class="w-100 h-100 fluid fav-cont col-12">
      <!-- SENDING TOPBAR !-->
     <portal :to="windID">
      <i class="font-icon icon_watchlists_favorite"></i>

      <div style="margin-left:5px" class="topbar-component custom">
          <FavouriteSelect 
              :current="$grid.getWndID(this.windID).color.additional.data"
              @onSelect="favChanged"
            />
      </div>
    </portal>
    <!-- END OF TOPBAR !-->

    <template v-if="!$store.state.done.stocksAdded">
      <b-skeleton-img no-aspect height="100%"></b-skeleton-img>
    </template>

    <b-row v-else class="watchlist d-flex pt-1 pb-5" style="position:relative">
      <div v-for="(fav,index) in datashow" style="" :class="`watch ${fav.closed == true ? 'closed' : ''}`" :key="fav.symbol">
        <div class="watch-topbar">
          <div class="left">
            <span class="icon" @click="menuClicked(index)"><v-icon>mdi-dots-vertical</v-icon></span>
            <div style="margin-bottom: -20px; margin-left:-15px; margin-right:10px">
              <Dropdown v-on:handleClick="itemMenuClicked" :items="menu_items" :data="{ fav: fav, cat: $grid.getWndID(windID).color.additional.data }" v-show="menu_opened == index" />
            </div>
            <span class="symbol">{{ fav.name }}</span>
          </div>

          <span :style="fav.price.change < 0 ? 'color:#d33623' : 'color:#109a21'"><v-icon>mdi-{{ fav.price.change < 0 ? "arrow-bottom-right" : "arrow-top-right" }}</v-icon></span>

          <div class="center" :style="fav.price.change < 0 ? 'color:#d33623' : 'color:#109a21'">
            {{fav.price.change | changePercent}}
          </div>

          <div class="right">
            <v-icon class="icon" @click="iconChartClick(index, fav.api_name)">mdi-credit-card-plus-outline</v-icon>
            <v-icon class="icon" @click="iconOrderClick(index, fav.api_name)">mdi-chart-areaspline</v-icon>
          </div>

        </div>

        <div class="watch-body">

          <div class="sell-zone pl-2">
            <div @click="order('sell', fav, index)" class="butt_content">
              <span class="butt_method">Bid</span>
              <span class="butt_price" v-html="$options.filters.formatPrice(fav.price.sell, fav.category)"></span>
              <span class="butt_lot">L:{{parseFloat(fav.price.low).toFixed(5)}}</span>
            </div>
          </div>

          <div class="buy-zone pr-2">
            <div @click="order('buy', fav, index)" class="butt_content">
              <span class="butt_method">Ask</span>
              <span class="butt_price" v-html="$options.filters.formatPrice(fav.price.buy, fav.category)"></span>
              <span class="butt_lot">H:{{parseFloat(fav.price.high).toFixed(5)}}</span>
            </div>
          </div>

          <span class="spread">
            {{ parseFloat(spread(fav)) }}
          </span>

        </div>

        <div class="watch-footer mt-1">
          <v-select
          v-if="!fav.closed"
          :ref="_uid + '?' + index"
          v-on:search:blur="volChange(index)"
          v-on:search:focus="volChange(index)"
          class="cus-dark-input no-focus-outline pl-2 pr-2"
          :options="[0.01,1,5,10,15,20,30,50,100]"
          @input="vchangevol($event, index)"
          :clearSearchOnBlur="clearSearchOnBlur"
          placeholder="0.01"
          taggable
          >
        </v-select>

        <div v-else class="opensafter">
          <span>Market will open in: {{ fav.opens_in | after}}</span>
        </div>
      </div>
    </div>

    <div @click="addfavmodal()" class="watch --placeholder">
      <v-icon>mdi-plus-circle</v-icon>
    </div>
    </b-row>
  </div>
</v-container>
</template>

<script>
import Dropdown from '../Dropdown'
import FavouriteSelect from '../FavouriteSelect.vue'
import moment from 'moment'

export default {
  name : "Favourites",

  components : {
    Dropdown,
    FavouriteSelect
  },

  data () {
    return {
      favChangeShow: false,
      current_tab : 'Loading data..',
      menu_opened : null,
      menu_items: [
        '<i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-card-plus-outline theme--light"></i> Create New Order',
        '<i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-finance theme--light"></i> Open Chart',
        '<i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-information-outline theme--light"></i> Information',
        '<i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-close theme--light"></i> Remove From Watchlist',
      ],

      controls : {
        left : `
        <i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-align-vertical-bottom theme--light"></i>
        <span style="font-size:13px;">
        `,

        right : ``,

      },
      selectedVolume : 0.01,

      individualVolumes: [],
    }
  },

  props : {
    windID : Number,
  },

  computed:  {
    datashow() {
      var loaded = this.$grid.getWndID(this.windID).color.additional.data
      var favsRaw = this.$store.getters.get_favs_by_cat(loaded)

      let favs = []
      favsRaw.forEach((fav) => {
        favs.push(this.$store.getters.get_stock(fav))
      })

      return favs
    }
  },

  methods : {
    favChanged(e) {
      //name of cat comes here
      if(this.$grid.getWndID(this.windID).color.additional.data == e) return
      this.$grid.updateCellById(this.windID, 'data', e)
    },


    timeLeft(o) {
      return o;
    },

    volChange(id) {
      var current = this.$refs[this._uid + '?' + id][0].$el
      var search = current.querySelector('[type="search"]')
      if(search.value == "") {
        if(!this.individualVolumes[id]) this.individualVolumes[id] = 0.01;
        return;
      }

      this.individualVolumes[id] = search.value
    },

    addfavmodal() {
      this.$instModal.show(this.$grid.getWndID(this.windID).color.additional.data)
    },

    spread(symb) {
      try {
        const stock = symb
          if(!stock) return 0.00

        var pip = Math.abs(stock.price.buy - stock.price.sell) * stock.price.scale

        return pip.toFixed(2)
      } catch(err) {
        return 0.00
      }
    },

    clearSearchOnBlur : function ( clearSearchOnSelect, multiple ) {
      return false
    },


    vchangevol(e, index) {
      this.individualVolumes[index] = e
    },

    iconOrderClick(index, symbol) {
      this.$grid.add({
        component: 'Chart',
        additional : {
          'symbol': symbol,
        }
      })
    },

    iconChartClick(index, symbol) {
      this.$bidModal.show(symbol)
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
      var stock = did.data.fav;
      var cat = did.data.cat;
      if(id == 0) {
        this.$bidModal.show(stock.api_name)
      } else if(id == 1) {
        this.$grid.add({
          component: 'Chart',
          additional : {
            symbol: stock.api_name,
          }
        })
      } else if(id == 2) {
        this.$instInfo.show(stock.api_name);
      } else if(id == 3) {
        this.$store.dispatch('rmFromFavs', {symbol: stock.api_name, cat: cat})
      }

    },

    order(direction, symbol, index) {

      this.selectedVolume = this.individualVolumes[index]
      if(this.selectedVolume == undefined || this.selectedVolume == NaN) this.selectedVolume = 0.01;
      //var stock = this.$store.getters.get_stock(symbol);
      var stock = symbol
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

        if(res.error) throw res.error

        th.$sound.play('success.mp3')
        this.$dtoast.pop({
            preset: "success",
            heading: "Success!",
            content: `Placed ${direction} PID#${res.id} ${this.selectedVolume} of ${stock.name} at ${stock.price[order.type]}`,
          })
        th.$notifAgent.push(`${direction} ${stock.name} has been placed!`, 'positions')
      }).catch(error => {
        th.$sound.play('error.mp3')
         this.$dtoast.pop({
          preset: "error",
          heading: "Insufficent Funds",
          content: `Cannot ${direction} ${this.selectedVolume} of ${stock.name} because of insufficient funds.`,
        })
        th.$notifAgent.push(error, 'positions')
      })
    },

  },

  filters : {
    after(secs) {
      var date = new Date(0);
      date.setSeconds(secs);
      return date.toISOString().substr(11, 8);
    },

    formatPrice(price, category)
    {
      // Convert the price to string and seperate both parts
      let array = []
      if(category == 'forex')
        array = price.toFixed(5).split(".")
      else
        array = price.toFixed(2).split(".")

      // Convert number parts to array so they could join later
      let number = array[0].split("")
      let decimals = array[1].split("")

      if(category == 'forex')
      {
        decimals.splice(2, 0, '<span>')
        decimals.splice(5, 0, '</span>')

        return number.concat('.', decimals).join("")
      }

      decimals.unshift('<span>')
      decimals.push('</span>')

      return number.concat('.', decimals).join("")

    },

    changePercent(val) {
      val = parseFloat(val).toFixed(2);

      if(val >= 0)
      val = '+' + val;


      return val + '%';
    }
  },


  created() {
    this.current_tab = this.$grid.getWndID(this.windID).color.additional.data
    this.controls.left = this.controls.left + this.current_tab + '</span>'
    this.$emit('sendControls', this.controls)
    let self = this;
  },
}
</script>

<style lang="scss">
@import '~/variables';

.watchlist {
.opensafter {
  font-size:13px;
}


.v-select {
  background-color: #282828;
  padding: 0px !important;
  z-index: 9999 !important;

  .vs__selected {
    margin: 0 !important;
  }

  .vs__no-options {
    display:none;
  }

  .vs__dropdown-toggle {
    height: 30px !important;
    margin:0 !important;
    border: none !important;

    .vs__selected-options {
      padding-top:0px;
      /*font-size:13px; */
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
      padding-top: 5px !important;
      padding-left: 10px !important;
      padding-bottom: 5px !important;
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

.cus-dark-input {
  z-index:0;
  border: none;
  border-radius: 2px;
  max-height: 28px !important;
  -moz-appearance: textfield;

  &::-webkit-outer-spin-button,
  &::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

}

.--placeholder {
  display:flex;
  background: transparent;
  justify-content:center;
  align-items:center;
  cursor:pointer;

  i {
    font-size: 42px;
    opacity:.5;
  }
}


.fav-cont {
}
.watch {
  .icon {
    cursor:pointer;
    background:none;
    outline:none;
    border:none;
    box-shadow:none;
  }

  user-select:none;
  display:flex;
  flex-direction:column;
  width:100%;
  min-width:210px;
  max-width:300px;
  margin: 5px 10px;
  border-radius:1px;

  .watch-body {
    position: relative;
    display:flex;
    flex-direction:row;
    max-width:300px;
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
      background: #1e1e1e;
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
      color: #fff;
      text-align: right;
      margin-left: 2px;
      display: flex;
      justify-content: flex-end;

      .butt_content {
        float:right;
      }

      cursor:pointer;
    }

    .sell-zone {
      width: 100%;
      margin-right: 2px;
      color: #fff;
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
      display: flex;
      align-items: center;
    }

    .right {
      margin-right:40px;

      i {
        margin:0 2px;
      }
    }

    .center {
      font-size:13px;
    }

    .symbol {
      margin-left:15px;
      font-size:13px;
    }

    display:flex;
    flex-direction:row;
  }
}
}
</style>
