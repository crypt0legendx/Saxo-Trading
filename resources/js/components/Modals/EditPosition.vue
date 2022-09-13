<template>
  <b-modal
  id='edit-position-modal'
  ref="edit-position-modal"
  size="sm"
  hide-footer
  no-close-on-backdrop
  centered
  no-fade
  >

  <template v-slot:modal-title>{{order.stock.api_name}}
    <span style="color:gray">(PID#{{order.order_id}})</span>
  </template>
  <template v-if="loaded" v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
  <template v-if="loaded" v-slot:default>
    <div v-if="loaded" :class="`watch ${currStock.closed == true ? 'closed' : ''}`">
      <div class="watch-topbar d-flex w-100 mb-1 justify-content-between align-items-center">
        <div class='mr-auto d-flex flex-column'>
          <div>
            <span class="z1" style="font-size:14px;"> {{isAskActive ? 'Buy' : 'Sell'}} {{selectedVolume}} </span>&thinsp;
            <div>
              at <span v-html="$options.filters.formatPrice(currStock.price.sell, currStock.category)"></span>
            </div>
          </div>

          <div style="margin-top:10px;">
            Change:&thinsp;
            <div class="center" :style="currStock.price.change < 0 ? 'color:#d33623' : 'color:#109a21'">
              {{currStock.price.change | changePercent}}
            </div>
          </div>
        </div>
        <div class="f-grow-half d-flex justify-content-center flex-column">
          <button v-if="!currStock.closed" class="btn-close-order" :class="order.pl >= 0 ? 'positive' : 'negative' " @click="closeOrder">Close</button>
          <button v-else title="Market is closed" class="btn-close-order disabled">Close</button>

          <div class='w-100 d-flex justify-content-between ml-auto mr-auto'>
            <span class="mr-auto ml-1">L: {{parseFloat(currStock.price.low).toFixed(2)}}</span>
            <span class="ml-auto">H: {{parseFloat(currStock.price.high).toFixed(2)}}</span>
          </div>
        </div>
      </div>

      <div class="watch-body mt-1">
        <div class="sell-zone"  :style="{
          background: !isAskActive ? '#d81720 !important' : '',
          color: !isAskActive ? '#fff !important' : '',
        'pointer-events': currStock.closed ? 'none' : ''
        }"
        @click='changeSt(0)'
        >
        <div class="butt_content">
          <span class="butt_method w-100 text-center">Bid</span>
          <span
          class="butt_price text-center"
          v-html="$options.filters.formatPrice(currStock.price.sell, currStock.category)"
          ></span>
        </div>
      </div>

      <div class="buy-zone" :style="{
        background: isAskActive ? '#19a337 !important' : '',
        color: isAskActive ? '#fff !important' : '',
        'pointer-events': currStock.closed ? 'none' : ''
      }"
      @click='changeSt(1)'
      >
      <div class="butt_content">
        <span class="butt_method w-100 text-center">Ask</span>
        <span
        class="butt_price text-center"
        v-html="$options.filters.formatPrice(currStock.price.buy, currStock.category)"
        ></span>
      </div>
    </div>
  </div>

  <div v-if="currStock.closed" class="opensafter">
    <span>Opens in {{currStock.opens_in | after}}</span>
  </div>

  <b-container id='bid-ask-settings' class='m-0 p-0 mt-2'>
    <b-row class='m-0 p-0'>
      <b-col class='p-0 col-12' v-show="activeBtn != 0">
        Entry Price:
        <div style='padding-right: 2px'>
          <b-form-input
          value="329"
          size='sm'
          class='cus-dark-input no-focus-outline'
          type='number'>
        </b-form-input>
      </div>
    </b-col>
    <b-col class='mt-2 p-0 col-6'>
      <div style="display:flex; flex-direction:column;">
        <div style="width: 110px;">
          Deviation in points
          <b-form-select
          plain
          type='text'
          size='sm'
          class='cus-dark-input no-focus-outline'
          v-model="deviationVol" :options="deviationOptions">
        </b-form-select>
      </div>
      <div style="width: 110px;">
        Volume
        <b-form-select
        plain
        type='text'
        size='sm'
        class='cus-dark-input no-focus-outline'
        v-model="selectedVolume" :options="volumeOptions">
      </b-form-select>
    </div>
  </div>
</b-col>
<b-col class='mt-2 p-0 col-6'>
  <div class="justify-content-center" style="display:flex; flex-direction:column; height:100%;">
    <span>Net Profit/Loss:</span>
    <span :style="order.pl > 0 ? 'color: green' : 'color: red'">{{order.pl > 0 ? '+' : ''}} {{ parseFloat(order.pl).toFixed(5) | currency_locale($store.getters.get_user_data('symbol')) }}</span>
  </div>
</b-col>
</b-row>
</b-container>


<b-container v-if="loaded" class='margin-require-market m-0 p-0 mt-2' v-show="activeBtn != 0">
  Margin required:

  <b-row class='mt-2 m-0 p-0'>
    <b-col class='p-0 col-6'>
      Duration:
      <b-form-select
      style='font-size: 12px'
      type='text'
      size='sm'
      class='cus-dark-input no-focus-outline'
      plain
      v-model="selectedDuration" :options="durationOptions">
    </b-form-select>
  </b-col>
  <b-col class='p-0 col-6' v-show="activeBtn != 0 && selectedDuration === 1">
    <div style='padding-right: 4px;'>
      <b-form-datepicker
      style='margin-top: 24px; margin-left: 2px; font-size: 12px; color: #fff !important;'
      placeholder=""
      :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
      class='cus-dark-input no-focus-outline'
      v-model="dateValue">
      <template v-slot:button-content>
      </template>
    </b-form-datepicker>
  </div>
</b-col>
</b-row>
</b-container>

<b-container  v-if="loaded" class=' m-0 p-0 mt-2'>
  <b-row class='mt-2 mb-2 m-0 p-0'>
    <b-col class='p-0 col-6'>
      <b-form-checkbox
      id="checkbox-1"
      v-model="stopLossBool"
      name="checkbox-1"
      >Stop Loss
    </b-form-checkbox>

    <div v-show="stopLossBool" style='margin-right: 1px !important;'>
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
    color: '#fff !important'
  }"
  >
  <span v-if="!loadingSubmit"> Edit&thinsp;
    {{
      isAskActive ? 'Ask' : 'Bid'
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
class='btn w-100 no-focus-outline'
style="background: #424242 !important; color: gray !important"
>
<span v-if="!loadingSubmit"> Place Order
</span>

<span v-else class="spinner-grow">
  <span class="sr-only">Loading...</span>
</span>


</button>

</div>
</div>
</template>
</b-modal>
</template>

<script>
import {editPos} from '../../plugins/modals.js';

export default {
  name: 'EditPositionModal',
  computed : {
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
        if(val == true) {
          if(this.activeLabel == 'Market')
          this.demoheightadj(562);
          else
          this.demoheightadj(670);
        }
        else if(val == false && this.takeProfitBool == false) {
          if(this.activeLabel == 'Market')
          this.demoheightadj(390);
          else
          this.demoheightadj(485);
        }
      },
      deep: true
    },

    'takeProfitBool': {
      handler: function (val, oldVal) {

        if(val == true)
        {
          if(this.activeLabel == 'Market')
          this.demoheightadj(562);
          else
          this.demoheightadj(670);
        }
        else if(val == false && this.stopLossBool == false)
        {
          if(this.activeLabel == 'Market')
          this.demoheightadj(390);
          else
          this.demoheightadj(485);
        }
      },
      deep: true
    }
  },

  created() {

    //pls never ever let this get to production.. demo only
    this.demoheightadj(390); //demo
  },

  methods: {
    closeOrder() {
      if(!this.order) return

      let th = this
      this.$store.dispatch('closeOrder', this.order.order_id).then((res) => {
        th.$sound.play('success.mp3')
        this.$dtoast.pop({
          preset: "success",
          heading: "Success!",
          content: `Closed position PID#${this.order.order_id} ${this.order.volume} of ${this.order.stock.name} at ${this.order.stock.price}`,
        })
      
        th.$store.dispatch('set_order', {id: this.order.order_id, order: res})
        th.hideModal();
      }).catch(error => {
        th.$sound.play('error.mp3')
        this.$dtoast.pop({
          preset: "error",
          heading: "Unexpected Error",
          content: `Cannot close position ${this.order.direction} PID#${this.order.order_id} ${this.order.volume} of ${this.order.stock.name}`,
        })
        console.log(error)
      })
    },


    trade() {
      this.loadingSubmit = true

      if(this.stopLossBool)
      this.order.sl = this.sl_pips;

      if(this.takeProfitBool)
      this.order.tp = this.tp_pips;

      let th = this;
      var type = th.isAskActive ? 'Ask on' : 'Bid on'

      this.order.type = this.isAskActive ? 'buy' : 'sell'
      this.order.volume = this.selectedVolume;

      this.$store.dispatch('editOrder', this.order).then((res) => {
        res = res.data

        if(res == -1) {
          th.$sound.play('error.mp3')
           this.$dtoast.pop({
            preset: "error",
            heading: "Unexpected Error",
            content: `Couldn't find position PID#${this.order.order_id}`,
          })
          th.$notifAgent.push(`Edit on order [PID:${th.order.order_id}] failed.`, 'positions');
          th.loadingSubmit = false
          return;
        }

        if(res == -2) {
          th.$sound.play('error.mp3')
          this.$dtoast.pop({
            preset: "error",
            heading: "Insufficent Funds",
            content: `Edit on position ${this.order.direction} PID#${this.order.order_id} of ${this.order.stock.name} failed. Insufficient funds`,
           })
          th.$notifAgent.push(`Edit on order [PID:${th.order.order_id}] failed. No balance`, 'positions');
          th.loadingSubmit = false
          return;
        }

        if(res.id == undefined) {
          th.$sound.play('error.mp3')
           this.$dtoast.pop({
            preset: "error",
            heading: "Unexpected Error",
            content: `Coulnd't edit position PID#${this.order.order_id} of ${this.order.stock.name}.`,
          })
          th.$notifAgent.push(`Edit on order [PID:${th.order.order_id}] failed. Unexpected error`, 'positions');
          th.loadingSubmit = false
          return;
        }


        th.$sound.play('success.mp3')
        this.$dtoast.pop({
          preset: "success",
          heading: "Success!",
          content: `Position PID#${this.order.order_id} ${this.order.volume} of ${this.order.stock.name} has been edited`,
        })

        th.$notifAgent.push(`Edit on order [PID:${th.order.order_id}] succeeded.`, 'positions');

        th.loadingSubmit = false
        th.$store.dispatch('set_order', {id: res.id, order: res})
        th.hideModal();
      }).catch(error => {
        console.log(error)
        th.$sound.play('error.mp3')
         this.$dtoast.pop({
            preset: "error",
            heading: "Unexpected Error",
            content: `Coulnd't edit position PID#${this.order.order_id} of ${this.order.stock.name}.`,
          })
        th.$notifAgent.push(`Edit on order [PID:${th.order.order_id}] failed. Unexpected error`, 'positions');
        th.loadingSubmit = false
        return;
      })

    },

    demoheightadj(h) {
      //pls never ever let this get to production.. demo only
      document.head.insertAdjacentHTML("beforeend", `<style>body{--modal-ep-max-height: ${h}px}</style>`)
    },

    toggleList() {
      this.listShown = !this.listShown;
    },

    showModal() {
      this.$refs['edit-position-modal'].show()
      setTimeout(() => {
        document.querySelector('#edit-position-modal___BV_modal_outer_ #edit-position-modal').classList.toggle('light-theme', this.$store.state.light_theme);
        document.querySelector('#edit-position-modal___BV_modal_outer_ #edit-position-modal .modal-dialog .modal-content').style.opacity = '1';
      }, 150);
    },

    hideModal() {
      this.$refs['edit-position-modal'].hide()
    },
    handleBtnSetter(btnIndex) {
      this.activeBtn = btnIndex;

      if(btnIndex === 0) {
        this.activeLabel = 'Market';
        this.stopLossBool = false;
        this.takeProfitBool = false;

        this.demoheightadj(390); //demo
      }
      else if(btnIndex === 1) {
        this.activeLabel = 'Limit';
        this.stopLossBool = false;
        this.takeProfitBool = false;

        this.demoheightadj(485) //demo
      }
      else {
        this.activeLabel = 'Stop';
        this.stopLossBool = false;
        this.takeProfitBool = false;

        this.demoheightadj(485) //demo
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
        estimatedProfitP = (estimatedPriceP - stock.price.buy) * this.selectedVolume * stock.lot

        // Stop Loss
        estimatedPriceL = stock.price.buy + this.sl_pips / stock.price.scale
        estimatedProfitL = (estimatedPriceL - stock.price.buy) * this.selectedVolume * stock.lot
      }
      else
      {
        // Take Profit
        estimatedPriceP = stock.price.buy + this.tp_pips / stock.price.scale
        estimatedProfitP = (stock.price.buy - estimatedPriceP) * this.selectedVolume * stock.lot

        // Stop Loss
        estimatedPriceL = stock.price.buy + this.sl_pips / stock.price.scale
        estimatedProfitL = (stock.price.buy - estimatedPriceL) * this.selectedVolume * stock.lot
      }

      this.takeProfit_estimatedPrice = estimatedPriceP.toFixed(5)
      this.takeProfit_estimatedProfit = estimatedProfitP.toFixed(2)
      this.stopLoss_estimatedPrice = estimatedPriceL.toFixed(5)
      this.stopLoss_estimatedProfit = estimatedProfitL.toFixed(2)
    },

    showModal() {
      this.$refs['edit-position-modal'].show()
      setTimeout(() => {
        document.querySelector('#edit-position-modal___BV_modal_outer_ #edit-position-modal').classList.toggle('light-theme', this.$store.state.light_theme);
        document.querySelector('#edit-position-modal___BV_modal_outer_ #edit-position-modal .modal-dialog .modal-content').style.opacity = '1';
      }, 150);
    },

    hideModal() {
      this.$refs['edit-position-modal'].hide()
    },
  },

  beforeMount() {
    editPos.event.$on('show-edit-position-modal', (data) => {
      this.showModal();
      this.order = data;
      this.currStock = this.$store.getters.get_stock(data.stock.api_name)
      this.volumeOptions.unshift(this.order.volume)
      this.selectedVolume = this.order.volume
      this.loaded = true
    })
  },

  data: function() {
    return {
      order : null,
      loaded: false,
      loadingSubmit: false,
      currStock: null,

      listShown: false,
      activeBtn: 0,
      activeLabel: 'Market',
      stopLoss: false,
      takeProfit: false,
      isAskActive: true,
      selectedVolume: 1,
      volumeOptions: [
        1, 5 , 10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 200, 390, 400, 562, 600
      ],
      selectedDuration: 0,
      durationOptions: [
        { value: 0, text: 'Good Till Cancelled' },
        { value: 1, text: 'Good Till Date' },
      ],

      deviationVol:0,
      deviationOptions: [
        0,1,2,3,4,5,10
      ],

      dateValue: '',
      stopLossBool: false,
      takeProfitBool: false,
      useParamsAsDefault: false,

      sl_pips : 0,
      tp_pips : 0,
      takeProfit_estimatedPrice: 0,
      takeProfit_estimatedProfit: 0,
      stopLoss_estimatedPrice: 0,
      stopLoss_estimatedProfit: 0,
    }
  },

}
</script>

<style lang='scss'>
@import '~/variables';
@import '~/modal_css';

#edit-position-modal .modal-dialog .modal-content {
  opacity: 0;
}

.btn-close-order.disabled {
  filter: grayscale(100%);
  border-style:unset;
  opacity:0.7;
  cursor:not-allowed;
}

.btn-close-order {
  font-size: 14px;
  cursor: pointer;
  width: 100%;
  height: 30px;
  background: none;
  border-radius: 2px;
  color: #fff !important;
  background: #d81720;
  margin-bottom:5px;
  transition: .1s ease-in-out;
}

.btn-close-order.positive {
  background: transparent;
  border: 1px solid green;
  color: #a7a7a7;

  &:hover {
    background: green;
  }
}

.btn-close-order.negative {
  background: transparent;
  border: 1px solid #d81720;

  &:hover {
    background: #d81720;
    border-color: #d81720 !important;
  }
}

.f-grow-half {
  flex-grow:0.6;
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
}

.stock-select-btn {
  padding:5px;
  cursor:pointer;
  user-select:none;

  &:hover {
    background: $stock_hover;
  }
}

#edit-position-modal .modal-dialog {
  justify-content: center !important;
  .modal-content {
    justify-content: center !important;
    min-height: var(--modal-ep-max-height);
    max-height: 1080px;
  }
}

#edit-position-modal .modal-dialog .modal-content {
  display:flex !important;
  justify-content: center !important;
  max-width: 390px !important;
  min-width: 390px !important;
  width: 390px !important;
  .modal-body {
    font-size: 13px;
    min-height: var(--modal-ep-max-height);
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
      text-align: center;
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
    max-width:390px;
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
          font-weight:390;
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


</style>
