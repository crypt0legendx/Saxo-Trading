<template>
  <b-modal id="bid-ask-modal" ref="bid-ask-modal" no-close-on-backdrop centered hide-footer no-fade >
  <template v-if="loaded" v-slot:modal-title>
    <span @click="toggleList" class="stock-select-btn"> {{ currStock.name }} <v-icon>mdi-chevron-down</v-icon></span>

    <div v-if="listShown" class="selectDropdown">
      <InstrumentsSelect @stockSelected="stockSelected"/>
    </div>

  </template>
  <template v-if="loaded" v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
  <template v-if="loaded" v-slot:default>
    <b-button-group class='button-group-el mb-2' style='width: 100% !important;'>
      <b-button
      class='no-focus-outline'
      @click='handleBtnSetter(0)'
      :class='{active: activeBtn === 0}'>Market
    </b-button>

    <b-button
    class='no-focus-outline'
    @click='handleBtnSetter(1)'
    :class='{active: activeBtn === 1}'>Limit
  </b-button>

  <b-button
  class='no-focus-outline'
  @click='handleBtnSetter(2)'
  :class='{active: activeBtn === 2}'>Stop
</b-button>
</b-button-group>
<div v-if="loaded" :class="`watch ${currStock.closed == true ? 'closed' : ''}`">
  <div class="watch-topbar d-flex w-100 mb-1">
    <div class='mr-auto d-flex'>
      Change:&thinsp;
      <div class="center" :style="currStock.price.change < 0 ? 'color:#d33623' : 'color:#109a21'">
        {{$store.getters.get_stock(currStock.api_name).price.change | changePercent}}
      </div>
    </div>
    <div class='ml-auto w-50 d-flex'>
      <span class="mr-auto ml-1">L: {{parseFloat($store.getters.get_stock(currStock.api_name).price.low).toFixed(2)}}</span>
      <span class="ml-auto">H: {{parseFloat($store.getters.get_stock(currStock.api_name).price.high).toFixed(2)}}</span>
    </div>
  </div>

  <div class="watch-body mt-1">
    <div class="sell-zone"  :style="{
      background: !isAskActive && !currStock.closed ? '#d81720 !important' : ($store.state.light_theme ? (currStock.closed  ? '#ebeef1 !important' : '#ebeef1 !important') : (currStock.closed  ? '#424242 !important' : '#424242 !important')),
      color: !isAskActive  && !currStock.closed ? '#fff !important' : ''
    }"
    :disabled='currStock.closed'
    @click='changeSt(0)'
    >
    <div class="butt_content">
      <span class="butt_method w-100 text-center">Bid</span>
      <span
      class="butt_price text-center"
      v-html="$options.filters.formatPrice($store.getters.get_stock(currStock.api_name).price.sell)"
      ></span>
    </div>
  </div>

  <div class="buy-zone noselect" :style="{
    background: isAskActive && !currStock.closed ? '#19a337 !important' : ($store.state.light_theme ? (currStock.closed  ? '#ebeef1 !important' : '#ebeef1 !important') : (currStock.closed  ? '#424242 !important' : '#424242 !important')),
    color: isAskActive && !currStock.closed ? '#fff !important' : ''
  }"
  :disabled='currStock.closed'
  @click='changeSt(1)'
  >
  <div class="butt_content">
    <span class="butt_method w-100 text-center">Ask</span>
    <span
    class="butt_price text-center"
    v-html="$options.filters.formatPrice($store.getters.get_stock(currStock.api_name).price.buy)"
    ></span>
  </div>
</div>
</div>

<div v-if="currStock.closed" class="opensafter">
  <span>Market opens again in {{currStock.opens_in | after}}</span>
</div>

<b-container id='bid-ask-settings' class='m-0 p-0 mt-2'>
  <b-row class='m-0 p-0'>
    <b-col class='p-0 col-6' v-show="activeBtn != 0">
      Entry Price:
      <div style='padding-right: 2px'>
        <b-input
        :value="currStock.price[isAskActive ? 'buy' : 'sell'].toFixed(currStock.price.scale == 100 ? 2 : 5)"
        size='sm'
        :step="(currStock.price.scale == 100) ? 0.01 : 0.00001"
        :style="{color: !entryPriceState ? 'red !important' : ''}"
        :class="[{'redtext': !entryPriceState}, 'cus-dark-input', 'no-focus-outline']"
        v-on:input="entryPrice = $event"
        v-model="entryPrice"
        type='number'>
      </b-input>
    </div>
  </b-col>
  <b-col class='p-0 col-6'>
    <div :class="(activeBtn != 0) ? 'ml-3' : ''" style="display:flex; justify-content:space-between; flex-direction:row; align-items:center">

      <div>
        <span style="margin-bottom: 6px;">Volume</span>
        <b-form-input
        plain
        type='text'
        size='sm'
        max='7'
        class='cus-dark-input no-focus-outline'
        v-model="selectedVolume">
      </b-form-input>
    </div>
  </div>
</b-col>
</b-row>
</b-container>

<div class='margin-require-market mt-2' v-show="activeBtn === 0">
  Margin required:
  <span :style="$store.getters.get_user.margin.free < getMarginRequired ? 'color:red' : 'color:green'" class="m-req"> {{ parseFloat(getMarginRequired).toFixed(5) | currency_locale($store.getters.get_user_data('symbol')) }} </span>
</div>

<b-container v-if="loaded" class='margin-require-market m-0 p-0 mt-2' v-show="activeBtn != 0">
  Margin required:

  <b-row class='mt-2 m-0 p-0'>
    <b-col class='p-0 col-6'>
      Duration:
      <b-form-select
      style='font-size: 12px'
      plain
      type='text'
      size='sm'
      class='cus-dark-input no-focus-outline'
      v-model="selectedDuration" :options="durationOptions">
    </b-form-select>
  </b-col>
  <b-col class='p-0 col-6' v-show="activeBtn != 0 && selectedDuration === 1">
    <div style='padding-right: 4px;'>
      <input type="datetime-local" style="margin-top: 24px; margin-left: 2px; font-size: 12px; color: #fff !important;" v-model="dateValue" class="cus-dark-input no-focus-outline form-control" />
    </div>
</b-col>
</b-row>
</b-container>

<b-container  v-if="loaded" id='loss-profit-info' class=' m-0 p-0 mt-2'>
  <b-row class='mt-2 mb-2 m-0 p-0'>
    <b-col class='p-0 col-6'>
      <b-form-checkbox
      id="checkbox-1"
      v-model="stopLossBool"
      name="checkbox-1"
      >Stop Loss
    </b-form-checkbox>

    <div v-show="stopLossBool" class='margin-right: 1px !important;'>
      Pips:
      <b-form-input
      value="0"
      size='sm'
      class='cus-dark-input no-focus-outline mb-1'
      type='number'
      v-model="sl_pips"
      ></b-form-input>

      Estimated Price:
      <b-form-input
      :value='stopLoss_estimatedPrice'
      size='sm'
      class='cus-dark-input no-focus-outline mb-1'
      readonly
      ></b-form-input>

      Estimated Loss (€):
      <b-form-input
      :value='stopLoss_estimatedProfit'
      size='sm'
      class='cus-dark-input no-focus-outline mb-1'
      readonly
      ></b-form-input>

      Account Loss (%):
      <div>{{ (stopLoss_estimatedProfit / $store.getters.get_user_data('balance') * 100).toFixed(2) }}%</div>
    </div>
  </b-col>
  <b-col class='p-0 col-6'>
    <b-form-checkbox
    id="checkbox-2"
    v-model="takeProfitBool"
    name="checkbox-2"
    >Take Profit
  </b-form-checkbox>

  <div v-show="takeProfitBool" style='margin-left: 2px;'>
    Pips:
    <b-form-input
    value="0"
    size='sm'
    class='cus-dark-input no-focus-outline mb-1'
    type='number'
    v-model="tp_pips"
    ></b-form-input>

    Estimated Price:
    <b-form-input
    :value='takeProfit_estimatedPrice'
    size='sm'
    class='cus-dark-input no-focus-outline mb-1'
    readonly
    ></b-form-input>

    Estimated Profit (€):
    <b-form-input
    :value='takeProfit_estimatedProfit'
    size='sm'
    class='cus-dark-input no-focus-outline mb-1'
    readonly
    ></b-form-input>

    Account Profit (%):
    <div>{{ (takeProfit_estimatedProfit / $store.getters.get_user_data('balance') * 100).toFixed(2) }}%</div>
  </div>
</b-col>
<b-col>
  <div>

  </div>
</b-col>
</b-row>
</b-container>
<div v-if="loaded" class="watch-footer mt-2 m-0 p-0">
  <button
  v-if="!currStock.closed"
  @click="trade"
  id='pl-order-btn'
  class='btn w-100 no-focus-outline'
  :style="{
    background: isAskActive ? '#19a337 !important' : '#d81720 !important',
    color: '#fff !important',
    transition: 'none !important'
  }"
  >
  <span v-if="!loadingSubmit"> Place&thinsp;
    {{
      isAskActive ? 'Buy' : 'Sell'
    }}
    {{ activeLabel }}
    &thinsp;Order
  </span>

  <span v-else class="spinner-grow">
    <span class="sr-only">Loading...</span>
  </span>


</button>
<button
v-else
id='pl-order-btn'
class='btn w-100 no-focus-outline disabled-btn'
>
<span v-if="!loadingSubmit"> Place Order
</span>

<span v-else class="spinner-grow">
  <span class="sr-only">Loading...</span>
</span>


</button>

<b-form-checkbox
id="checkbox-3"
class='mt-2 mb-2'
v-model="useParamsAsDefault"
name="checkbox-3"
style="color: #a7a7a7 !important;"
>Use parameters as default
</b-form-checkbox>
</div>
</div>
</template>
</b-modal>
</template>

<style lang='scss'>
@import '~/variables';
@import '~/modal_css';

#bid-ask-modal .modal-dialog .modal-content {
  opacity: 0;
}

.opensafter {
  font-size:13px;
}



.light-theme .m-req {
  color: black !important;
}

.m-req {
  font-weight: 600;
  color: #f2f2f2;
  letter-spacing: 0.8px;
}
.selectDropdown {
  position:absolute;
  z-index:999;
  padding:5px;
  background: $bg;
  margin-left:-5px;
  box-shadow: rgb(0 0 0 / 45%) 1px 1px 5px 0px !important;
}


#bid-ask-modal .modal-dialog {
  justify-content: center !important;
  .modal-content {
    justify-content: center !important;
    min-height: var(--modal-max-height);
    max-height: 1080px;
  }
}

#bid-ask-modal .modal-dialog .modal-content {
  display:flex !important;
  justify-content: center !important;
  max-width: 300px !important;
  min-width: 300px !important;
  width: 300px !important;

  .modal-body {
    font-size: 13px;
    min-height: var(--modal-max-height);
  }
}

.modal-body {

  #bid-ask-settings,
  .margin-require-market {
    color: #a7a7a7 !important;

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
  }

  .container .row .col div {
    color: #a7a7a7 !important;

    .cus-dark-input {
      margin-top: 5px;
      background-color: #353535 !important;
      border: none;
      border-radius: 2px;
      color: #a7a7a7 !important;
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

  .watch {
    color:white;
    height:110px;
    display:flex;
    flex-direction:column;
    width:100%;
    min-width:210px;
    max-width:300px;
    border-radius:1px;

    .watch-footer {
      #pl-order-btn {
        border-radius: 2px;
        padding: 5px 0px;
        font-size: 13px !important;
      }
    }

    .watch-body {
      display:flex;
      flex-direction:row;
      width:100%;
      height:80px;

      .butt_content {
        display:flex;
        flex-direction:column;
        margin:3.5px;

        .butt_method {
          font-size:13px;
          font-weight: 400;
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

      .buy-zone,
      .sell-zone {
        max-height: 62px !important;
        height: 62px !important;
        background-color: #424242 !important;
        color: #a7a7a7 !important;
      }

      .buy-zone {
        width: 100%;
        background: #19a337;
        margin-left: 1px;

        cursor:pointer;
        &:hover {
          background: #1eb940;
        }
      }

      .sell-zone {
        width: 100%;
        background : #d81720;
        margin-right: 1px;
        cursor:pointer;


        &:hover {
          background : #da2b33;
        }
      }
    }


    .watch-topbar {
      color: #a6a6a6cc;
      font-weight: 400;

      .right {
        /*margin-right:40px; */

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

    }

  }

  .watch:hover {
    background: transparent !important;
  }
}


.redtext {
  color:red !important;
}
</style>

<script>
import {bidAskModal} from '../../plugins/modals.js';
import InstrumentsSelect from '../InstrumentsSelect.vue';

export default {
  name: 'BidAskModal',
  components: {
    InstrumentsSelect
  },

  data() {
    return {
      loaded: false,
      loadingSubmit: false,

      listShown: false,
      currStock:  null,
      activeBtn: 0,
      activeLabel: 'Market',
      stopLoss: false,
      takeProfit: false,
      isAskActive: true,
      entryPrice: 0,
      selectedVolume: 0.01,
      entryPrice: null,

      volumeOptions: [
        0.01, 0.1, 1, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 200, 300, 400, 500, 600
      ],
      selectedDuration: 0,
      durationOptions: [
        { value: 0, text: 'Good Till Cancelled' },
        { value: 1, text: 'Good Till Date' },
      ],
      dateValue: new Date(),
      stopLossBool: false,
      takeProfitBool: false,
      useParamsAsDefault: false,

      sl_pips : 10,
      tp_pips : 10,
      takeProfit_estimatedPrice: 0,
      takeProfit_estimatedProfit: 0,
      stopLoss_estimatedPrice: 0,
      stopLoss_estimatedProfit: 0,
    }
  },

  computed : {
      entryPriceState() {
        var required = this.$store.getters.get_stock(this.currStock.api_name).price[this.isAskActive ? 'buy' : 'sell'] - this.$store.getters.get_stock(this.currStock.api_name).price[this.isAskActive ? 'buy' : 'sell'] / this.currStock.price.scale

        if(this.activeBtn == 1) {
          if(this.entryPrice >= required)
            return false;

          return true
        } else if(this.activeBtn == 2) {
          if(this.entryPrice <= required)
            return false;

          return true
        }
    },

    getMarginRequired() {
      const user = this.$store.getters.get_user
      var stock = this.currStock

      if(!stock)
      return '--';

      var type = this.isAskActive ? 'buy' : 'sell';

      var price = 0;
      if(type == 'buy')
      price = stock.price.buy;
      else
      price = stock.price.sell;


      var required = stock.lot * price * this.selectedVolume / user.leverage;

      //todo - convert to user symbol
      return required;
    },
  },

  methods: {
    trade() {
      var stock = this.currStock;

      if(!stock) return; //todo error

      this.loadingSubmit = true

      if(!this.stopLossBool)
      this.sl_pips = 0;

      if(!this.takeProfitBool)
      this.tp_pips = 0;

      var order = {
        stock_id : stock.api_name,
        volume : this.selectedVolume,
        type : this.isAskActive ? 'buy' : 'sell',
        trade_type : this.activeLabel,
        sl : this.sl_pips,
        tp: this.tp_pips,
      }

      if(this.activeBtn != 0)
      {
        order.entryPrice = this.entryPrice
        order.cancel = (this.dateValue == '' || this.selectedDuration == 0) ? null : this.dateValue
      }

      let th = this;
      var type = th.isAskActive ? 'Ask on' : 'Bid on'

      this.$store.dispatch('createOrder', order).then((res) => {
        if(res.error != undefined) throw res

        th.$sound.play('success.mp3')
        this.$dtoast.pop({
            preset: "success",
            heading: "Success!",
            content: `Placed ${type} PID#${res.id} ${this.selectedVolume} of ${stock.name} at ${stock.price[order.type]}`,
          })

        th.$notifAgent.push(`Placed ${type} PID#${res.id} ${this.selectedVolume} of ${stock.name} at ${stock.price[order.type]}`, 'positions');

        th.loadingSubmit = false
      }).catch(msg => {
        th.$sound.play('error.mp3')
         this.$dtoast.pop({
            preset: "error",
            heading: "Insufficent Funds",
            content: `Cannot ${type} ${this.selectedVolume} of ${stock.name} because of insufficient funds.`,
          })

        th.$notifAgent.push(msg.error, 'positions');
        th.loadingSubmit = false
      })

    },

    demoheightadj(h) {
      //pls never ever let this get to production.. demo only
      document.head.insertAdjacentHTML("beforeend", `<style>body{--modal-max-height: ${h}px}</style>`)
    },

    toggleList() {
      this.listShown = !this.listShown;
    },

    showModal() {

      if(this.$store.getters.get_stocks.length > 0)
          this.entryPrice = parseFloat(this.currStock.price[this.isAskActive ? 'buy' : 'sell'].toFixed(this.currStock.price.scale == 100 ? 2 : 5))

      this.$refs['bid-ask-modal'].show()
      setTimeout(() => {
        document.querySelector('#bid-ask-modal___BV_modal_outer_ #bid-ask-modal').classList.toggle('light-theme', this.$store.state.light_theme);
        document.querySelector('#bid-ask-modal___BV_modal_outer_ #bid-ask-modal .modal-dialog .modal-content').style.opacity = '1';
      }, 150);
    },
    handleBtnSetter(btnIndex) {
      this.activeBtn = btnIndex;

      if(btnIndex === 0) {
        this.activeLabel = 'Market';
        this.stopLossBool = false;
        this.takeProfitBool = false;

        this.demoheightadj(340); //demo
      }
      else if(btnIndex === 1) {
        this.activeLabel = 'Limit';
        this.stopLossBool = false;
        this.takeProfitBool = false;

        this.demoheightadj(400) //demo
      }
      else {
        this.activeLabel = 'Stop';
        this.stopLossBool = false;
        this.takeProfitBool = false;

        this.demoheightadj(400) //demo
      }
    },
    stockSelected(stock) {
      this.currStock = stock;
      this.toggleList();
    },
    changeSt(el) {
      if(el === 0)
      this.isAskActive = false;
      else
      this.isAskActive = true;
    },
    profits() {
      var stock = this.currStock;
      let estimatedPriceP = 0, estimatedProfitP = 0, estimatedPriceL = 0, estimatedProfitL = 0

      if(this.isAskActive === true)
      {
        // Take Profit
        estimatedPriceP = stock.price.buy + this.tp_pips / stock.price.scale
        estimatedProfitP = (estimatedPriceP - stock.price.buy) * this.selectedVolume * stock.lot / this.$store.getters.get_user_data('leverage')

        // Stop Loss
        estimatedPriceL = stock.price.buy - this.sl_pips / stock.price.scale
        estimatedProfitL = (estimatedPriceL - stock.price.buy) * this.selectedVolume * stock.lot / this.$store.getters.get_user_data('leverage')
      }
      else
      {
        // Take Profit
        estimatedPriceP = stock.price.buy - this.tp_pips / stock.price.scale
        estimatedProfitP = (stock.price.buy - estimatedPriceP) * this.selectedVolume * stock.lot / this.$store.getters.get_user_data('leverage')

        // Stop Loss
        estimatedPriceL = stock.price.buy + this.sl_pips / stock.price.scale
        estimatedProfitL = (stock.price.buy - estimatedPriceL) * this.selectedVolume * stock.lot / this.$store.getters.get_user_data('leverage')
      }

      this.takeProfit_estimatedPrice = estimatedPriceP.toFixed(5)
      this.takeProfit_estimatedProfit = estimatedProfitP.toFixed(2)
      this.stopLoss_estimatedPrice = estimatedPriceL.toFixed(5)
      this.stopLoss_estimatedProfit = Math.abs(estimatedProfitL.toFixed(2))
    },
  },
  filters : {
    after(secs) {
      var date = new Date(0);
      date.setSeconds(secs);
      return date.toISOString().substr(11, 8);
    },
    formatPrice(val) {
      val = parseFloat(val).toFixed(5).toLocaleString('en-US');

      var vf = [];
      let vfr = val.length;

      for(var v = 0; v < vfr; v++) {
        if(v == vfr - 2)
        vf.push('<span>');

        if(v == vfr)
        vf.push('</span>');

        vf.push(val[v]);
      }

      return vf.join('');
    },

    changePercent(val) {
      val = parseFloat(val).toFixed(2);

      if(val >= 0)
      val = '+' + val;


      return val + '%';
    }
  },

  watch: { //demo
    tp_pips() {
      this.profits()
    },
    sl_pips() {
      this.profits()
    },
    selectedVolume() {
      this.profits()
    },
    'stopLossBool': {
      handler: function (val, oldVal) {
        this.profits()
        if(val == true) {
          if(this.activeLabel == 'Market')
          this.demoheightadj(550);
          else
          this.demoheightadj(610);
        }
        else if(val == false && this.takeProfitBool == false) {
          if(this.activeLabel == 'Market')
          this.demoheightadj(340);
          else
          this.demoheightadj(400);
        }
      },
      deep: true
    },

    'takeProfitBool': {
      handler: function (val, oldVal) {
        this.profits()
        if(val == true)
        {
          if(this.activeLabel == 'Market')
          this.demoheightadj(550);
          else
          this.demoheightadj(610);
        }
        else if(val == false && this.stopLossBool == false)
        {
          if(this.activeLabel == 'Market')
          this.demoheightadj(370);
          else
          this.demoheightadj(400);
        }
      },
      deep: true
    }
  },

  created() {

    //pls never ever let this get to production.. demo only
    this.demoheightadj(340)
  },

  beforeMount() {
    const that = this

    if(this.$store.state.done.stocksAdded) {
      this.currStock = that.$store.getters.get_stocks[0]
      that.loaded = true
    } else {
      this.$store.subscribe((mutation, state) => {
        if(mutation.type == 'stocksDone') {
          this.currStock = that.$store.getters.get_stocks[0]
          that.loaded = true
        }
      })
    }

    bidAskModal.event.$on('show-bid-ask-modal', (data) => {
      this.showModal();

      var symbol = data.data;
      var active_nav = data.active_nav;
      var direction = data.direction;

      if(active_nav != undefined)
      this.handleBtnSetter(active_nav);

      if(direction != undefined)
      this.isAskActive = direction;


      if(symbol != undefined)
      this.currStock =  that.$store.getters.get_stock(symbol);
    })
  }
}
</script>
