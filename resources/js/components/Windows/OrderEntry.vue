<template>
  <div class="w-100 h-100 d-flex justify-content-center">
     <!-- SENDING TOPBAR !-->
     <portal :to="windID">
      <i class="font-icon icon_new_order"></i>

      <div style="margin-left:5px" class="topbar-component custom">
         <span @click="toggleList" class="stock-select-btn">{{currStockWin.name}} <v-icon>mdi-chevron-down</v-icon></span>

          <div v-if="listShownWin" class="selectDropdown">
            <InstrumentsSelect @stockSelected="stockSelected"/>
          </div>
      </div>
    </portal>
    <!-- END OF TOPBAR !-->

    <div class="order-entry p-2" style="height: 100%; width:100%;">
      <template v-if="currStockWin == null">
        <b-skeleton-img no-aspect height="100%"></b-skeleton-img>
      </template>
      <template v-else>

        <b-button-group class='button-group-el mb-2' style='width: 100% !important;'>
          <b-button class='no-focus-outline' @click='handleBtnSetter(0)' :class='{active: activeBtnWin === 0}'>
            Instant
          </b-button>

          <b-button class='no-focus-outline' @click='handleBtnSetter(1)' :class='{active: activeBtnWin === 1}'>
            Limit
          </b-button>

          <b-button class='no-focus-outline' @click='handleBtnSetter(2)' :class='{active: activeBtnWin === 2}'>
            Stop
          </b-button>
        </b-button-group>

        <div :class="`watch ${currStockWin.closed == true ? 'closed' : ''}`" style="min-width: 100% !important;">
          <div class="watch-topbar d-flex w-100 mb-1">
            <div class='mr-auto d-flex'>
              Change:&thinsp;
              <div class="center" :style="$store.getters.get_stock(currStockWin.api_name).price.change < 0 ? 'color:#d33623' : 'color:#109a21'">
                {{$store.getters.get_stock(currStockWin.api_name).price.change | changePercent}}
              </div>
            </div>
            <div class='ml-auto w-50 d-flex'>
              <span class="mr-auto ml-1">L: {{ currStockWin.category == 'forex' ? parseFloat($store.getters.get_stock(currStockWin.api_name).price.low).toFixed(5) : parseFloat($store.getters.get_stock(currStockWin.api_name).price.low).toFixed(2) }}</span>
              <span class="ml-auto">H: {{ currStockWin.category == 'forex' ? parseFloat($store.getters.get_stock(currStockWin.api_name).price.high).toFixed(5) : parseFloat($store.getters.get_stock(currStockWin.api_name).price.high).toFixed(2) }}</span>
            </div>
          </div>

          <div class="watch-body mt-1">
            <div class="sell-zone"  :style="{
                background: !isAskActiveWin && !currStockWin.closed ? '#d81720 !important' : ($store.state.light_theme ? (currStockWin.closed  ? '#ebeef1 !important' : '') : (currStockWin.closed  ? '#424242 !important' : '')),
                color: !isAskActiveWin  && !currStockWin.closed ? '#fff !important' : ''
              }"
              :disabled='currStockWin.closed'
              @click='changeSt(0)'
              >
              <div class="butt_content">
                <span class="butt_method w-100 text-center">Bid</span>
                <span class="butt_price text-center" v-html="$options.filters.formatPrice($store.getters.get_stock(currStockWin.api_name).price.sell, currStockWin.category)"></span>
              </div>
            </div>

             <div class="buy-zone noselect" :style="{
                background: isAskActiveWin && !currStockWin.closed ? '#19a337 !important' : ($store.state.light_theme ? (currStockWin.closed  ? '#ebeef1 !important' : '') : (currStockWin.closed  ? '#424242 !important' : '')),
                color: isAskActiveWin && !currStockWin.closed ? '#fff !important' : ''
              }"
              :disabled='currStockWin.closed'
              @click='changeSt(1)'
              >
              <div class="butt_content">
                <span class="butt_method w-100 text-center">Ask</span>
                <span class="butt_price text-center" v-html="$options.filters.formatPrice($store.getters.get_stock(currStockWin.api_name).price.buy, currStockWin.category)"></span>
              </div>
            </div>

            <span class="spread">
              {{ parseFloat(spread(currStockWin)) }}
            </span>
          </div>

          <div v-if="currStockWin.closed" class="opensafter">
            <span>Market opens again in {{currStockWin.opens_in | after}}</span>
          </div>

          <b-container id='bid-ask-settings' class='m-0 p-0 mt-2' style="min-width: 100% !important;">
            <b-row class='m-0 p-0'>
              <b-col class='p-0 col-6' v-show="activeBtnWin != 0">
                Entry Price:
                <div style='padding-right: 2px'>
                  <b-form-input
                  id="entryPrice"
                  :value="currStockWin.price[isAskActive ? 'buy' : 'sell'].toFixed(currStockWin.price.scale == 100 ? 2 : 5)"
                  size='sm'
                  :step="(currStockWin.price.scale == 100) ? 0.01 : 0.00001"
                  :style="{color: !entryPriceState ? 'red !important' : ''}"
                  :class="[{'redtext': !entryPriceState}, 'cus-dark-input', 'no-focus-outline']"
                  v-on:input="entryPrice = $event"
                  v-model="entryPrice"
                  type='number'>
                </b-form-input>
              </div>
            </b-col>
            <b-col class='p-0 col-6'>
              <div :class="(activeBtnWin != 0) ? 'ml-3' : ''" style="display:flex; justify-content:space-between; flex-direction:row; align-items:center">

                <div>
                  <span style="margin-bottom: 6px;">Volume</span>
                  <b-form-input
                  plain
                  type='text'
                  size='sm'
                  max='7'
                  class='cus-dark-input no-focus-outline'
                  v-model="selectedVolumeWin">
                </b-form-input>
              </div>
            </div>
          </b-col>
          </b-row>
          </b-container>

          <div class='margin-require-market mt-2' v-show="activeBtnWin === 0">
            Margin required:
            <span class="m-req" :style="$store.getters.get_user.margin.free < getMarginRequired ? 'color:red' : 'color:green'">
              {{ parseFloat(getMarginRequired).toFixed(5) | currency_locale($store.getters.get_user_data('symbol')) }}
            </span>
          </div>

          <b-container class='margin-require-market m-0 p-0 mt-2' v-show="activeBtnWin != 0" style="min-width: 100% !important;">
            Margin required:

            <b-row class='mt-2 m-0 p-0'>
              <b-col class='p-0 col-6'>
                Duration:
                <b-form-select style='font-size: 12px' plain type='text' size='sm' class='cus-dark-input no-focus-outline' v-model="selectedDurationWin" :options="durationOptionsWin"></b-form-select>
              </b-col>

              <b-col class='p-0 col-6' v-show="activeBtnWin != 0 && selectedDurationWin === 1">
                <div style='padding-right: 4px;'>
                  <!--
                  <b-form-datepicker style='margin-top: 24px; margin-left: 2px; font-size: 12px; color: #fff !important;' placeholder="" :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }" class='cus-dark-input no-focus-outline' v-model="dateValueWin"> <template v-slot:button-content> </template> </b-form-datepicker>
                  -->
                  <input type="datetime-local" style="margin-top: 24px; margin-left: 2px; font-size: 12px; color: #fff !important;" v-model="dateValueWin" class="cus-dark-input no-focus-outline form-control" />
                </div>
              </b-col>

            </b-row>
          </b-container>

          <b-container id='loss-profit-info' class='m-0 p-0 mt-2' style="min-width: 100% !important;">
            <b-row class='mt-2 mb-2 m-0 p-0'>
              <b-col class='p-0 col-6'>
                <b-form-checkbox id="checkbox-1-win" v-model="stopLossWinBool" name="checkbox-1-win" >Stop Loss </b-form-checkbox>

                <div v-show="stopLossWinBool" class='margin-right: 1px !important;'>
                  Pips:
                  <b-form-input value="0" size='sm' class='cus-dark-input no-focus-outline mb-1' type='number' v-model="sl_pipsWin" ></b-form-input>

                  Estimated Price:
                  <b-form-input :value='stopLoss_estimatedPrice' size='sm' class='cus-dark-input no-focus-outline mb-1' readonly ></b-form-input>

                  Estimated Loss (€):
                  <b-form-input :value='stopLoss_estimatedProfit' size='sm' class='cus-dark-input no-focus-outline mb-1' readonly ></b-form-input>

                  Account Loss (%):
                  <div>{{ (stopLoss_estimatedProfit / $store.getters.get_user_data('balance') * 100).toFixed(2) }}%</div>
                </div>
              </b-col>
              <b-col class='p-0 col-6'>
                <b-form-checkbox id="checkbox-2-win" v-model="takeProfitWinBool" name="checkbox-2-win" >
                  Take Profit
                </b-form-checkbox>

                <div v-show="takeProfitWinBool" style='margin-left: 2px;'>
                  Pips:
                  <b-form-input value="329" size='sm' class='cus-dark-input no-focus-outline mb-1' type='number' v-model="tp_pipsWin" ></b-form-input>

                  Estimated Price:
                  <b-form-input :value='takeProfit_estimatedPrice' size='sm' class='cus-dark-input no-focus-outline mb-1' readonly ></b-form-input>

                  Estimated Profit (€):
                  <b-form-input :value='takeProfit_estimatedProfit' size='sm' class='cus-dark-input no-focus-outline mb-1' readonly ></b-form-input>

                  Account Profit (%):
                  <div>{{ (takeProfit_estimatedProfit / $store.getters.get_user_data('balance') * 100).toFixed(2) }}%</div>
                </div>
              </b-col>
              <b-col>
                <div></div>
              </b-col>
            </b-row>
          </b-container>
          <div class="watch-footer mt-2 m-0 p-0">
            <button
              v-if="!currStockWin.closed"
              @click="trade"
              id='pl-order-btn'
              class='btn w-100 no-focus-outline'
              :style="{
                background: isAskActiveWin ? '#19a337 !important' : '#d81720 !important',
                color: '#fff !important'
              }"
              >
              <span v-if="!loadingSubmitWin"> Place&thinsp; {{ isAskActiveWin ? 'Buy' : 'Sell' }} {{ activeLabelWin }} &thinsp;Order</span>

              <span v-else class="spinner-grow">
                <span class="sr-only">Loading...</span>
              </span>
            </button>

            <button
              v-else
              id='pl-order-btn'
              class='btn w-100 no-focus-outline disabled-btn'
              >
              <span v-if="!loadingSubmitWin"> Place Order
              </span>

              <span v-else class="spinner-grow">
                <span class="sr-only">Loading...</span>
              </span>


              </button>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import InstrumentsSelect from '../InstrumentsSelect.vue';

export default {
  name: "OrderEntry",
  components: {
    InstrumentsSelect,
  },

  data() {
    return {
      entryPrice: 0,
      loadingSubmitWin: false,
      listShownWin: false,
      currStockWin: null,
      activeBtnWin: 0,
      activeLabelWin: 'Market',
      stopLossWin: false,
      takeProfitWin: false,
      isAskActiveWin: true,
      selectedVolumeWin: 0.01,
      selectedDurationWin: 0,
      durationOptionsWin: [
        {
          value: 0,
          text: 'Good Till Cancelled'
        },
        {
          value: 1,
          text: 'Good Till Date'
        },
      ],
      dateValueWin: new Date(),
      stopLossWinBool: false,
      takeProfitWinBool: false,
      useParamsAsDefaultWin: false,
      entryPrice: null,

      sl_pipsWin: 10,
      tp_pipsWin: 10,
      takeProfit_estimatedPrice: 0,
      takeProfit_estimatedProfit: 0,
      stopLoss_estimatedPrice: 0,
      stopLoss_estimatedProfit: 0,


    }
  },

  props: {
    windID: Number,
  },

  watch: { //demo
    tp_pipsWin() {
      this.profits()
    },
    sl_pipsWin() {
      this.profits()
    },
    selectedVolume() {
      this.profits()
    },
  },

  methods: {
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
    toggleList() {
      this.listShownWin = !this.listShownWin;
    },
    trade() {
      let stock = this.currStockWin

      if(!stock) return; //todo error

      this.loadingSubmitWin = true

      if(!this.stopLossWinBool)
      this.sl_pipsWin = 0;

      if(!this.takeProfitWinBool)
      this.tp_pipsWin = 0;

      var order = {
        stock_id : stock.api_name,
        volume : this.selectedVolumeWin,
        type : this.isAskActiveWin ? 'buy' : 'sell',
        trade_type : this.activeLabelWin,
        sl : this.sl_pipsWin,
        tp: this.tp_pipsWin,
      }

      let th = this;
      var type = th.isAskActiveWin ? 'Ask on' : 'Bid on'

      if(this.activeBtnWin != 0)
      {
        order.entryPrice = this.entryPrice
        order.cancel = (this.dateValueWin == '' || this.selectedDurationWin == 0) ? null : this.dateValueWin
      }

      this.$store.dispatch('createOrder', order).then((res) => {
        if(res.error != undefined) throw res

        th.$sound.play('success.mp3')
        this.$dtoast.pop({
            preset: "success",
            heading: "Success!",
            content: `Placed ${type} PID#${res.id} ${this.selectedVolumeWin} of ${stock.name} at ${stock.price[order.type]}`,
          })
        th.$notifAgent.push(`${type} ${stock.name} has been placed!`, 'positions');

        th.loadingSubmitWin = false
      }).catch(msg => {
        th.$sound.play('error.mp3')
        this.$dtoast.pop({
          preset: "error",
          heading: "Insufficient Funds",
          content: `Cannot ${type} ${this.selectedVolumeWin} of ${stock.name} because of insufficient funds.`,
        })
        th.$notifAgent.push(msg.error, 'positions');
        th.loadingSubmitWin = false
      })

    },

    toggleList() {
      this.listShownWin = !this.listShownWin;
    },

    handleBtnSetter(btnIndex) {
      this.activeBtnWin = btnIndex;
      this.stopLossWinBool = false;
      this.takeProfitWinBool = false;

      if (btnIndex === 0)
      this.activeLabelWin = 'Market';
      else if (btnIndex === 1)
      this.activeLabelWin = 'Limit';
      else
      this.activeLabelWin = 'Stop';
    },
    stockSelected(stock) {
      this.currStockWin = this.get_stock(stock.api_name)
      this.toggleList();
    },
    changeSt(el) {
      if(el === 0)
      this.isAskActiveWin = false;
      else
      this.isAskActiveWin = true;
    },
    profits() {
      let stock = this.currStockWin
      let estimatedPriceP = 0, estimatedProfitP = 0, estimatedPriceL = 0, estimatedProfitL = 0

      if(this.isAskActiveWin === true)
      {
        // Take Profit
        estimatedPriceP + stock.price.buy + this.tp_pipsWin / stock.price.scale
        estimatedProfitP = (estimatedPriceP - stock.price.buy) * this.selectedVolumeWin * stock.lot / this.$store.getters.get_user_data('leverage')

        // Stop Loss
        estimatedPriceL = stock.price.buy - this.sl_pipsWin / stock.price.scale
        estimatedProfitL = (estimatedPriceL - stock.price.buy) * this.selectedVolumeWin * stock.lot / this.$store.getters.get_user_data('leverage')
      }
      else
      {
        // Take Profit
        estimatedPriceP = stock.price.buy - this.tp_pipsWin / stock.price.scale
        estimatedProfitP = (stock.price.buy - estimatedPriceP) * this.selectedVolumeWin * stock.lot / this.$store.getters.get_user_data('leverage')

        // Stop Loss
        estimatedPriceL = stock.price.buy + this.sl_pipsWin / stock.price.scale
        estimatedProfitL = (stock.price.buy - estimatedPriceL) * this.selectedVolumeWin * stock.lot / this.$store.getters.get_user_data('leverage')
      }

      this.takeProfit_estimatedPrice = estimatedPriceP.toFixed(5)
      this.takeProfit_estimatedProfit = estimatedProfitP.toFixed(2)
      this.stopLoss_estimatedPrice = estimatedPriceL.toFixed(5)
      this.stopLoss_estimatedProfit = Math.abs(estimatedProfitL).toFixed(2)
    },
  },
  computed: {
    ...mapGetters([
      'get_stock'
    ]),

     entryPriceState() {
        var required = this.$store.getters.get_stock(this.currStockWin.api_name).price[this.isAskActiveWin ? 'buy' : 'sell'] -
                        this.$store.getters.get_stock(this.currStockWin.api_name).price[this.isAskActiveWin ? 'buy' : 'sell'] / this.currStockWin.price.scale

        if(this.activeBtnWin == 1) {
          if(this.entryPrice > required)
            return false;

          return true
        } else if(this.activeBtnWin == 2) {
          if(this.entryPrice < required)
            return false;

          return true
        }
    },

    getMarginRequired() {
      const user = this.$store.getters.get_user
      let stock = this.currStockWin

      if (!stock)
      return '--';

      var type = this.isAskActiveWin ? 'buy' : 'sell';

      var price = 0;
      if (type == 'buy')
      price = stock.price.buy;
      else
      price = stock.price.sell;


      var required = stock.lot * price * this.selectedVolumeWin * this.$store.getters.get_symbol_rate / user.leverage;

      //todo - convert to user symbol
      return required;
    },
  },

  filters: {
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

      if (val >= 0)
      val = '+' + val;

      return val + '%';
    }
  },

  created() {

    this.currStockWin = this.get_stock(this.$store.getters.get_stocks[0].api_name)
    this.profits()

    this.entryPrice = parseFloat(this.currStockWin.price[this.isAskActiveWin ? 'buy' : 'sell'].toFixed(this.currStockWin.price.scale == 100 ? 2 : 5))
  },
}
</script>

<style lang="scss">
@import '~/variables.scss';

.light-theme .m-req {
  color: black !important;
}


.order-entry {
  max-width: calc(100% - 50px);
  .m-req {
    font-weight: 600;
    color: #f2f2f2;
    letter-spacing: 0.8px;
  }

  .m-req.red-text {
    color: red;
  }

  font-size: 13px !important;

  .btn-group {
    .btn {
      font-size: 13px;
      border: none;
      padding: 6px 22px !important;
      border-radius: 2px !important;
    }
  }

  .cus-dark-input {
    margin-top: 5px;
    background-color: #353535 !important;
    border: none;
    border-radius: 2px;
    color: #a7a7a7 !important;
    max-height: 30px !important;
    height: 30px !important;
  }

  .cus-dark-input option:hover {
    background-color: #000 !important;
  }


  .container .row .col div {
    .cus-dark-input {
      margin-top: 5px;
      border: none;
      border-radius: 2px;
      max-height: 30px !important;
      height: 30px !important;
    }

    .b-form-datepicker .form-control {
      font-size: 13px !important;
      line-height: 1.5 !important;
      text-align: left;
      color: $topbar_secondary_fcolor;
    }
  }

  .button-group-el {
    font-size: 12.5px;
  }

  .watch  {
    color: white;
    height: 110px;
    display: flex;
    flex-direction: column;
    width: 100%;
    border-radius: 1px;

    .watch-footer {
      #pl-order-btn {
        border-radius: 2px;
        padding: 5px 0px;
        font-size: 13px !important;
      }
    }

    .watch-body {
      position: relative;
      display: flex;
      flex-direction: row;
      width: 100% !important;
      max-width:100% !important;
      height: 80px;

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
        min-width: 100% !important;
        text-align: center !important;
        display: flex;
        flex-direction: column;
        margin: 3.5px;

        .butt_method {
          font-size: 13px;
          font-weight: 400;
        }

        .butt_lot {
          font-size: 13px;
          opacity: .7;
        }

        .butt_price {
          font-size: 17px;
          font-weight: 300;
          margin-top: 2.5px;
          margin-bottom: 0px;
          font-family: "Roboto";
          letter-spacing: 2px;

          span {
            font-size: 24px;
          }
        }
      }

      .buy-zone,
      .sell-zone {
        max-height: 62px !important;
        background-color: #424242 !important;
        height: 62px !important;
        color: #a7a7a7 !important;
      }

      .buy-zone {
        width: 100%;
        background: #19a337;
        margin-left: 1px;
        cursor: pointer;

        &:hover {
          background: #1eb940;
        }
      }

      .sell-zone {
        width: 100%;
        background: #d81720;
        margin-right: 1px;
        cursor: pointer;


        &:hover {
          background: #da2b33;
        }
      }
    }


    .watch-topbar {
      font-weight: 400;

      .right {
        /*margin-right:40px; */

        i {
          margin: 0 2px;
        }
      }

      .center {
        font-size: 13px;
      }

      .symbol {
        margin-left: 15px;
        font-size: 13px;
      }
    }
  }

  .watch:hover {
    background: transparent !important;
  }
}
</style>
