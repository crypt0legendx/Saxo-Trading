<template>
  <div style="height: 100%" @contextmenu.prevent="$refs.menu.open($event)">

    <vue-context ref="menu">
      <div class="menu context-menu">
        <div @click="$bidModal.show(stock.api_name, 1, true)" class="menu-item">
          <div class="menu-item-icon">
            <i class="font-icon icon_order_arrow_buy_limit "></i>
          </div>
          <span class="menu-item-text">Buy Limit at {{ stock.price.buy.toFixed(stock.price.scale == 100 ? 2 : 5) }}</span>
          <div class="menu-item-children"></div>
        </div>
        <div @click="$bidModal.show(stock.api_name, 1, false)" class="menu-item">
          <div class="menu-item-icon">
            <i class="font-icon icon_order_arrow_sell_stop "></i>
          </div>
          <span class="menu-item-text">Sell Stop {{ stock.price.sell.toFixed(stock.price.scale == 100 ? 2 : 5) }}</span>
          <div class="menu-item-children"></div>
        </div>
        <div @click="$bidModal.show(stock.api_name, 0)" class="menu-item">
          <div class="menu-item-icon">
            <i class="font-icon icon_new_order "></i>
          </div>
          <span class="menu-item-text">Create New Order</span>
          <div class="menu-item-children"></div>
        </div>
        <hr>
        <div @click="addAlert({ enabled: true, stock: stock.api_name, dropdown: false, alertOn: 'ask-lower', priceValue: stock.price.buy.toFixed(stock.price.scale == 100 ? 2 : 5), comment:'', emailNotif: false, state: 1 })" class="menu-item">
          <div class="menu-item-icon">
            <i class="font-icon icon_bell "></i>
          </div>
          <span class="menu-item-text">Set alert when Ask ≤ {{$store.getters.get_stock(stock.api_name).price.buy.toFixed($store.getters.get_stock(stock.api_name).price.scale == 100 ? 2 : 5)}} </span>
          <div class="menu-item-children"></div>
        </div>
        <div @click="addAlert({ enabled: true, stock: stock.api_name, dropdown: false, alertOn: 'bid-lower', priceValue: stock.price.sell.toFixed(stock.price.scale == 100 ? 2 : 5), comment:'', emailNotif: false, state: 1 })" class="menu-item">
          <div class="menu-item-icon">
            <i class="font-icon icon_bell "></i>
          </div>
          <span class="menu-item-text">Set alert when Bid ≤ {{$store.getters.get_stock(stock.api_name).price.sell.toFixed($store.getters.get_stock(stock.api_name).price.scale == 100 ? 2 : 5)}}</span>
          <div class="menu-item-children"></div>
        </div>
        <hr>
        <div class="menu-item v-context__sub">
          <ul class="menu v-context" style="display: none; margin-left: 10px">
            <div class="menu instrument-picker">
              <InstrumentsPicker @stockSelected="changeInstrument" />
            </div>
          </ul>
          <div class="menu-item-icon">
            <i class="font-icon icon_new_chart "></i>
          </div>
          <span class="menu-item-text">Instrument</span>
          <i class="font-icon icon_arrow_small_right "></i>
        </div>
        <div class="menu-item v-context__sub">
          <ul class="menu v-context" style="display: none; margin-left: 10px">
            <div class="menu period-menu">
              <div @click="setPeriod(1, 0)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_periods_m1 "></i> </div> <span class="menu-item-text">1 minute</span> <div class="menu-item-children"></div> </div>
              <div @click="setPeriod(5, 1)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_periods_m2 "></i> </div> <span class="menu-item-text">5 minutes</span> <div class="menu-item-children"></div> </div>
              <div @click="setPeriod(15, 2)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_periods_m15 "></i> </div> <span class="menu-item-text">15 minutes</span> <div class="menu-item-children"></div> </div>
              <div @click="setPeriod(30, 3)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_periods_m30 "></i> </div> <span class="menu-item-text">30 minutes</span> <div class="menu-item-children"></div> </div>
              <div @click="setPeriod(60, 4)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_periods_h1 "></i> </div> <span class="menu-item-text">1 hour</span> <div class="menu-item-children"></div> </div>
            </div>
          </ul>
          <div class="menu-item-icon">
            <i :class="'font-icon' + (period == 60) ? 'icon_periods_h1' : 'icon_periods_m' + period"></i>
          </div>
          <span class="menu-item-text">Period</span>
          <i class="font-icon icon_arrow_small_right "></i>
        </div>
        <div class="menu-item v-context__sub">
          <ul class="menu v-context" style="display: none; margin-left: 10px">
            <div class="menu plot-menu">
              <div @click="setType(0)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_chart_type_bar "></i> </div> <span class="menu-item-text">Bar Chart</span> <div class="menu-item-children"></div> </div>
              <div @click="setType(1)" class="menu-item selected"> <div class="menu-item-icon"> <i class="font-icon icon_chart_type_candles "></i> </div> <span class="menu-item-text">Candlesticks</span> <div class="menu-item-children"></div> </div>
              <div @click="setType(2)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_chart_ha_candles "></i> </div> <span class="menu-item-text">Heikin Ashi</span> <div class="menu-item-children"></div> </div>
              <div @click="setType(3)" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_chart_type_hlc "></i></div> <span class="menu-item-text">HLC Chart</span> <div class="menu-item-children"></div> </div>
              <div @click="setType(4)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_chart_type_line "></i> </div> <span class="menu-item-text">Line Chart</span> <div class="menu-item-children"></div> </div>
              <div @click="setType(5)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_chart_type_dots "></i> </div> <span class="menu-item-text">Dots Chart</span> <div class="menu-item-children"></div> </div>
            </div>
          </ul>
          <div class="menu-item-icon">
            <i class="font-icon icon_chart_type_candles "></i>
          </div>
          <span class="menu-item-text">Chart Type</span>
          <i class="font-icon icon_arrow_small_right "></i>
        </div>
        <hr>
        <div class="menu-item">
          <div class="menu-item-icon">
            <i class="font-icon icon_panning "></i>
          </div>
          <span class="menu-item-text">Panning</span>
          <div class="menu-item-children"></div>
        </div>
        <div class="menu-item">
          <div class="menu-item-icon">
            <i class="font-icon icon_crosshair "></i>
          </div>
          <span class="menu-item-text">Crosshair</span>
          <div class="menu-item-children"></div>
        </div>
        <hr>
        <div class="menu-item v-context__sub">
          <ul class="menu v-context" style="display: none; margin-left: 10px">
            <div class="menu tools-menu">
              <div @click="selectTool('LineTool:Segment')" class="menu-item selected"> <div class="menu-item-icon"><i class="font-icon icon_lines_trend "></i></div> <span class="menu-item-text">Trendline</span> <div class="menu-item-children"></div> </div>
              <div @click="selectTool('Retracement:Fibonacci')" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_fibo_fan "></i></div> <span class="menu-item-text">Fibonacci Retracement</span> <div class="menu-item-children"></div> </div>
              <div @click="selectTool('Fan:Fibonacci')" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_fibo_arc-57 "></i></div> <span class="menu-item-text">Fibonacci Fan</span> <div class="menu-item-children"></div> </div>
              <div @click="selectTool('Arc:Fibonacci')" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_fibo_arc-96 "></i></div> <span class="menu-item-text">Fibonacci Arc</span> <div class="menu-item-children"></div> </div>
              <div @click="selectTool('Timeline:Fibonacci')" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_fibo_timez "></i></div> <span class="menu-item-text">Fibonacci Timezone</span> <div class="menu-item-children"></div> </div>
              <div @click="selectTool('VerticalLine:Extended')" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_vert_market "></i></div> <span class="menu-item-text">Vertical Marker</span> <div class="menu-item-children"></div> </div>
              <div @click="selectTool('HorizontalLine:Extended')" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_hor_market "></i></div> <span class="menu-item-text">Horizontal Marker</span> <div class="menu-item-children"></div> </div>
              <div @click="selectTool('Rectangle:Default')" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_rectangle "></i></div> <span class="menu-item-text">Rectangle</span> <div class="menu-item-children"></div> </div>
              <div @click="selectTool('Elipse:Drawing')" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_ellipse "></i></div> <span class="menu-item-text">Ellipse</span> <div class="menu-item-children"></div> </div>
              <div @click="selectTool('ABCD:Default')" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_abcd_pattern "></i></div> <span class="menu-item-text">ABCD Pattern</span> <div class="menu-item-children"></div> </div>
            </div>
          </ul>
          <div class="menu-item-icon">
            <i class="font-icon icon_lines_trend "></i>
          </div>
          <span class="menu-item-text">Add Drawing</span>
          <i class="font-icon icon_arrow_small_right "></i>
        </div>
        <div @click="openIndicatorMenu" class="menu-item">
          <div class="menu-item-icon">
            <i class="font-icon icon_indicators "></i>
          </div>
          <span class="menu-item-text">Add Indicator</span>
          <div class="menu-item-children"></div>
        </div>
        <div class="menu-item v-context__sub">
          <ul v-if="objects != null && objects.length > 0" class="menu v-context" style="display: none; margin-left: 10px">
            <div v-for="object in objects" class="menu objects-menu" :key="object.name">
              <div @click="manageObject(object)" class="menu-item">
                <div class="menu-item-icon">
                  <i class="font-icon icon_lines_fibo_arc-96"></i>
                </div>
                <span class="menu-item-text">{{ object.name }}</span>
                <div class="menu-item-children">
                  <i @click="manageObject(object, true)" class="font-icon icon_win_close"></i>
                </div>
              </div>
            </div>
          </ul>
          <ul v-else class="menu v-context" style="display: none; margin-left: 10px">
            <div class="menu">
              <hr>
              <span class="menu-item-text"> </span>
              <span class="menu-item-text">No objects present on chart</span>
              <span class="menu-item-text"> </span>
              <hr>
            </div>
          </ul>
          <div class="menu-item-icon">
            <i class="font-icon icon_graph_objects "></i>
          </div>
          <span class="menu-item-text">Objects</span>
          <i class="font-icon icon_arrow_small_right "></i>
        </div>
      </div>
    </vue-context>

    <trading-vue
    v-if="loaded"
    ref="tv"
    :key="wndID"
    :id="wndID"
    :titleTxt="title"
    :data="chart"
    :overlays="overlays"
    :width="this.width"
    :height="this.height"
    :color-back="colors.back"
    :color-grid="colors.grid"
    :color-text="colors.text"
    :color-candle-up="colors.colorCandleUp"
    :color-candle-dw="colors.candle_dw"
    :color-vol-up="colors.colorVolUp"
    :color-vol-dw="colors.colorVolDw"
    :toolbar="false"
    :legend-buttons="[this.icons.settingsIcon, this.icons.closeIcon]"
    :instrument="stock.api_name"
    :extensions="ext"
    />

    <div v-else class="loadingScreen">
      <div class="overlay">
        <svg width="57" height="57" viewBox="0 0 57 57" xmlns="http://www.w3.org/2000/svg">
          <g fill="none" fill-rule="evenodd">
            <g transform="translate(1 1)" stroke-width="3">
              <circle cx="5" cy="50" r="5">
                <animate attributeName="cy" begin="0s" dur="2.2s" values="50;5;50;50" calcMode="linear" repeatCount="indefinite" /> <animate attributeName="cx" begin="0s" dur="2.2s" values="5;27;49;5" calcMode="linear" repeatCount="indefinite" />
              </circle>
              <circle cx="27" cy="5" r="5"> <animate attributeName="cy" begin="0s" dur="2.2s" from="5" to="5" values="5;50;50;5" calcMode="linear" repeatCount="indefinite" /> <animate attributeName="cx" begin="0s" dur="2.2s" from="27" to="27" values="27;49;5;27" calcMode="linear" repeatCount="indefinite" /> </circle>
              <circle cx="49" cy="50" r="5"> <animate attributeName="cy" begin="0s" dur="2.2s" values="50;50;5;50" calcMode="linear" repeatCount="indefinite" /> <animate attributeName="cx" from="49" to="49" begin="0s" dur="2.2s" values="49;5;27;49" calcMode="linear" repeatCount="indefinite" /> </circle>
            </g>
          </g>
        </svg>
      </div>
      <div class="loadingContent"></div>
    </div>

  </div>
</template>

<script>
import VueContext from 'vue-context';
import TradingVue from "trading-vue-js";
import Overlays from "tvjs-overlays";
import XP from "tvjs-xp";
import { DataCube, Utils } from "trading-vue-js";
import indicators from "./indicators";
import customIndicators from "./custom-indicators";
import AddWin from "./AddWin.vue";
import DrawingTools from './overlays'
import InstrumentsPicker from "../InstrumentsSelect.vue";
import ChartTypes from './types'

export default {
  name: "app",
  components: { TradingVue, InstrumentsPicker, VueContext },

  props: {
    wndID: String,
    stock: Object,
    link: String
  },

  computed: {
    title()
    {
      return (this.stock.name !== undefined) ? this.stock.name : this.stock
    },
    windowInstance()
    {
      return this.$grid.getWndID(this.wndID)
    },
    link() {
      let stock = this.stock
      if(stock.category == 'forex')
      {
        let symbol = stock.api_name.match(/.{1,3}/g)
        return 'https://www.alphavantage.co/query?function=FX_INTRADAY&outputsize=full&from_symbol=' + symbol[0] + '&to_symbol=' + symbol[1] + '&interval=' + this.period + 'min&apikey=L7P5YCPP9ZA8I36Q'
      }
      else if(stock.category == 'crypto')
      {
        let symbol = stock.api_name.match(/.{1,3}/g)
        return 'https://www.alphavantage.co/query?function=CRYPTO_INTRADAY&outputsize=full&symbol=' + symbol[0] + '&market=' + symbol[1] + '&interval=' + this.period + 'min&apikey=L7P5YCPP9ZA8I36Q'
      }
      else
      return 'https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol=' + stock.api_name + '&interval=' + this.period + 'min&outputsize=full&apikey=L7P5YCPP9ZA8I36Q'
    },
  },

  methods: {
    manageObject(object, del) {
      if(!del) del = false

      this.$store.commit('broadcast', {
        event: 'chart_internal_event',
        wndID: this.wndID,
        body: {event: 'object-selected', client: true,  args: [object.grid_id, object.name, object.id]}
      })

      if(del)
      this.$store.commit('broadcast', {
        event: 'chart_internal_event',
        wndID: this.wndID,
        body: {event: 'tool-selected', client: true, args: ['System:Remove']}
      })
    },


    onResize() {
      this.width = this.$el.parentElement.offsetWidth
      this.height = this.$el.parentElement.offsetHeight
    },
    toggleLightTheme(l) {
      if(!l) {
        this.colors.back = '#161616'
        this.colors.grid = '#333333'
        this.colors.text = '#a7a7a7'
        this.colors.cross = '#dd64ef'
        this.colors.candle_dw = '#d33623'
        this.colors.wick_dw = '#d33623'
      }
      else
      {
        this.colors.back = '#fff'
        this.colors.grid = '#eee'
        this.colors.text = '#333'
        this.colors.candle_dw = '#d33623'
        this.colors.wick_dw = '#d33623'
      }
    },

    selectTool(tool) {
      let ev = {event: 'tool-selected', category: 'drawings', client: true,  args: [tool]}
      this.$store.commit('broadcast', {
        event: 'chart_internal_event',
        wndID: this.wndID,
        body: ev
      })
    },

    addAlert(a) {
      let alerts = this.$store.state.user.alerts.savedAlerts
      alerts.push(a)

      this.$http.post(`/api/user/update/account/alerts`, { value : alerts }).then((res) => {
         this.$dtoast.pop({
            preset: "info",
            heading: "Price Alert Created",
            content: `Created ${this.stock.name} ${a.alertOn.includes('bid') ? 'Bid' : 'Ask'} ≤ ${a.priceValue} price alert`,
          })
      }).catch(error => {
         this.$dtoast.pop({
            preset: "error",
            heading: "Unexpected Error",
            content: `This notification cannot be created right now`,
          })
        this.loading = false
      })
    },

    async fetchData()
    {
      if(this.$store.state.cached['Charts Data'] == undefined)
      this.$store.state.cached['Charts Data'] = []

      if(this.$store.state.cached['Charts Data'][this.$props.stock.api_name] == undefined)
      this.$store.state.cached['Charts Data'][this.$props.stock.api_name] = {}

      if(this.period == 600) // 600 - default, translates to 60.. used to differentiate between redraw & actual period change
      if(this.$store.state.cached['Charts Data'][this.$props.stock.api_name].period != undefined)
      this.period = this.$store.state.cached['Charts Data'][this.$props.stock.api_name].period
      else
      this.period = 60;

      if(this.$store.state.cached['Charts Data'][this.$props.stock.api_name].data != undefined &&
      this.period == this.$store.state.cached['Charts Data'][this.$props.stock.api_name].period) {
        this.$emit('doneLoading', true)
        return this.$store.state.cached['Charts Data'][this.$props.stock.api_name].data;
      }

      'use strict';
      var out = null

      if(this.stock.category == 'forex')
      {
        const data = await this.$http.get(this.link).then(res => res.data)
        let res = Object.keys(data['Time Series FX (' + this.period + 'min)']).map((key) =>[(new Date(key)).getTime()].concat(Object.values(data['Time Series FX (' + this.period + 'min)'][key]).map((value) => parseFloat(value))).concat(0))

        out = res.reverse()
      }

      if(this.stock.category == 'stock')
      {
        const data = await this.$http.get(this.link).then(res => res.data)
        let res = Object.keys(data['Time Series (60min)']).map((key) => [(new Date(key)).getTime()].concat(Object.values(data['Time Series (' + this.period + 'min)'][key]).map((value) => parseFloat(value))))

        out = res.reverse()
      }

      if(this.stock.category == 'crypto')
      {
        const data = await this.$http.get(this.link).then(res => res.data)
        let res = Object.keys(data['Time Series Crypto (60min)']).map((key) => [(new Date(key)).getTime()].concat(Object.values(data['Time Series Crypto (' + this.period + 'min)'][key]).map((value) => parseFloat(value))))

        out = res.reverse()
      }

      this.$store.state.cached['Charts Data'][this.$props.stock.api_name].data = out
      this.$store.state.cached['Charts Data'][this.$props.stock.api_name].period = this.period

      this.$emit('doneLoading', true)
      return out;
    },

    async setPeriod(period, idm) {
      this.loaded = false
      this.period = period
      this.chart.set('chart.data', await this.fetchData())
      this.loaded = true

      //ticks
      this.$store.commit('broadcast', {
        event: 'periodChange',
        wndID: this.wndID,
        body: {
          value: period,
          menu_id: idm,
        }
      })
    },

    async setType(id) {
      var type = "XOhlcBars"

      switch(id) {
        case 0:
        type = 'XOhlcBars'
        break;
        case 1:
        type = "Candles"
        break;
        case 2:
        type = 'Heikin'
        break;
        case 3:
        type = 'HLC'
        break;
        case 4:
        type = 'Spline'
        break;
        case 5:
        type = "Dots"
        break;
      }

      this.chart.set("chart.type", type);
      this.chart.set("chart.settings", {
        upBarColor: "#109a21",
        downBarColor: "#d33623",
        showVolume: false,
      });

      this.$store.commit('broadcast', {
        event: 'typeChange',
        wndID: this.wndID,
        body: {
          value: id,
        }
      })

    },
    openIndicatorMenu()
    {
      this.$store.commit('broadcast', {
        event: 'chart_internal_event',
        wndID: this.wndID,
        body: {
          event: 'legend-button-click',
          args: [{ button: "add", dataIndex: 0, grid: 0, overlay: "", type: "onchart", }],
          client: true
        }
      })
    },
    addEMA(color, length) {
      this.chart.add("onchart", {
        type: "EMA",
        name: "EMA, " + length,
        data: [],
        settings: {
          lineColor: color,
          length: length,
          lineWidth: 1.5,
        },
      });
    },
    addSMA(color, length) {
      this.chart.add("onchart", {
        type: "SMA",
        name: "SMA, " + length,
        data: [],
        settings: {
          lineColor: color,
          length: length,
          lineWidth: 1.5,
        },
      });
    },
    async changeInstrument(stock) {
      this.stock = stock
      this.loaded = false
      this.chart.set('chart.data', await this.fetchData())
      this.$emit('stockChanged', stock.name)
      this.loaded = true
    },

    updateOverlays(reRender = false) {
      this.objects = []
      const that = this
      const windowOverlays = this.windowInstance.color.additional.overlays

      windowOverlays.forEach((overlay) => {
        if(overlay.type == "RangeTool")
          this.pushObjectToLists(overlay, true)
        if(reRender)
          that.chart.add('onchart', overlay)
        if(overlay.data[0].drawing === true)
          that.objects.push({ id: overlay.settings.$uuid, name: overlay.name, grid_id: overlay.grid.id, drawing: overlay.data[0].drawing })
      })
    }
  },


  async created() {
    const that = this
    this.toggleLightTheme(this.$store.state.light_theme)

    this.stock = this.$store.getters.get_stock(this.stock)

    this.unsubscribe = this.$store.subscribe(async (mutation, state) => {
      if(this.stock == null) return
      if(typeof(this.stock) != 'object')
      this.stock = this.$store.getters.get_stock(this.stock)

      if(mutation.type == "broadcast") {
        if(this.wndID != mutation.payload.wndID || !mutation.payload.body) return

        if(mutation.payload.body.event == "stock_selected") {
          this.stock = this.$store.getters.get_stock(mutation.payload.stock)
          this.$emit('stockChanged', mutation.payload.stock)
          this.loaded = false
          this.chart.set('chart.data', await this.fetchData())
          this.loaded = true
        }

        if(mutation.payload.body.event == "drawing-mode-off" || mutation.payload.body.event == "remove-layer-meta") {
          if(that.chart == null) return
          const overlays = that.chart.get('onchart')
          that.$grid.updateCellById(that.wndID, 'overlays', overlays)
          that.updateOverlays()
        }
      }
    })

    this.$on('custom-event', (d) => {
      if(d.wndID != this.wndID) return

      switch(d.event)
      {
        case 'quick_order':
        {
          var order = {
            stock_id : this.stock.api_name,
            volume : d.args.vol,
            type : d.args.direction,
            trade_type : 'market',
            sl : 0,
            tp: 0,
          }

          let th = this;

          var direction = d.args.vol == "buy" ? 'Ask' : 'Bid'

          this.$store.dispatch('createOrder', order).then((res) => {
            if(res.error != undefined) throw res

            this.$sound.play('success.mp3')
            this.$dtoast.pop({
              preset: "success",
              heading: "Success!",
              content: `Placed ${direction} PID#${res.id} ${d.args.vol} of ${this.stock.api_name} at ${this.stock.price[d.args.direction]}`,
            })

            this.$notifAgent.push(`${direction} ${this.stock.api_name} has been placed!`, 'positions');

            this.$store.dispatch('add_order', res)
          }).catch(error => {
            this.$sound.play('error.mp3')
             this.$dtoast.pop({
              preset: "error",
              heading: "Insufficent Funds",
              content: `Cannot ${direction} ${d.args.vol} of ${this.stock.name} because of insufficient funds.`,
             })
            this.$notifAgent.push(error.error, 'positions');
          })

        }
        break

        case 'new_order':
        this.$bidModal.show(this.stock.api_name, 1, true)
        break

        case 'period':
        this.setPeriod(d.args.value, d.args.menu_item)
        break

        case 'type':
        this.setType(d.args.value)
        break

        case 'add_indicator':
        bus.send("legend-button-click", {
          args: [{
            button: "add",
            dataIndex: 0,
            grid: 0,
            overlay: "",
            type: "onchart",
          }],
        })
        break
      }
    })

    let data = await this.fetchData()

    let volume = data
    volume = volume.map((value, index) => {
      return [value[0], value[5], value[1] < value[4]]
    })

    let dataCube = { "chart": { "data": data, "indexBased" : true, "settings": { "showVolume": false }, "grid": {} }, "onchart": [], "offchart": [] }

    this.chart = new DataCube(dataCube)

    // On Chart
    this.updateOverlays(1)

    // Off Chart
    if(this.stock.category != 'forex')
    this.chart.add('offchart', { name: "Tick Volume", type: "Volume", data: volume, settings: {} })

    this.loaded = true

    window.addEventListener("resize", this.onResize);
    this.onResize();
  },
  beforeDestroy() {
    this.unsubscribe()
    window.removeEventListener("resize", this.onResize);
  },
  watch: {
    '$store.state.light_theme': function() {
      this.toggleLightTheme(this.$store.state.light_theme)
    }
  },
  data() {
    return {
      unsubscribe: null,
      icons: {
        settingsIcon: {
          name: "settings",
          icon:
          "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACHklEQVR4nJWSQWsTURSFz503cTKTnRvLQASJbuzGRUQFF1m4qoig5MWkNdBtf4My6UZRCG76BxxmKLxRd9qF4FYMLkRwE0Eq0lFmEQoldZLOvOtCU0PbYL2bd+/iO5x77gOOUZ7nGQCglDrh+/7ZP70AAONfMDPTpM+y7HmpVPoUBMEFKWWulBJ0DJiISCulXmdZ5hQKhWdE9CBN0ytLS0sfZjpgZup0OkREOoqiDSI6ZZrmtXq9/mQ8Ht+zLOt9GIbnjhSYwKurqzqKog0ApweDwWUp5U/f90umad7SWr8oFArJoRUO2H4F4EyaptV2uz3sdrt2uVzuAfgmpVw4FOIRtitJklxst9tDpZRdLpd7zLwppVxQSglmJpqGiYgBIIoiRUTnd3Z2ri4vL28rpWwAPWbebDQaN5jZAMBEtH8i8jyP5ufnrSzLnrquW9/a2rq0uLjY832/ZNv2W63114MwABDw+1NIKfMgCG4KIdaEEI+Y+T4z32HmrhDiu5Ty+kH4UAamaZoAfkgp1/I8fzg3N/cGwOdZMACY04PWOrVtu7q+vv7Rsiw7SZKg2WzenQVPdjdc1xVxHHOlUjnpOE51b2+vKITIoih6ubKyQv1+n+I43odrtRpqtVp+lOB/F4VheNtxnNZoNMoBCK21mCgzsyYinj4xEelisSh2d3cft1qtd6bWepuZv2it80mozH+dTfqpVwMQhmEMAeAXxtYsQLbV5E8AAAAASUVORK5CYII=",
        },
        closeIcon: {
          name: "remove",
          icon:
          "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABVklEQVR4nK1Tu27CMBS9xkkGfoK/YOiWTiyVOnSIEIQuwA+w5zvYWuTc6zyGSt3rrX8EQkngdiimbpWkUtUzRToP+Rw7AP+JJEkGeZ7LLj7Pc5kkyeDXoLaQvuArSUQbIloBAGy3W9/y9puIVkS0cT0CAICZB0IIRsQ73/dfTqfTYxRFO2tcr9e11jqWUj7VdX0/nU5fmVkIIc7f+gMAKKXCoijOWuvYclrruCiKs1IqdLXXE1gYY7wwDBulVDgcDt+qqnoAAAiCoDwcDrez2cxYTece9thpmo6zLDtmWXZM03T8cxeLziuRUo6Yec/MeynlqEvXWkFrHQdB8Nw0zQ0AgOd571VVLaIo2nVWsMMQ0dwdDOBrWCKau9or7J0i4rIsS7ZmY4xnjPFsSFmWjIhL1wMAn+/gErBAxIk1u9Uu/AQRF66nFW1kr8Gt0idk5t6f7U/4AEJG1VgdgANOAAAAAElFTkSuQmCC",
        },
      },
      ext: Object.values(XP),
      width: window.innerWidth,
      height: window.innerHeight,
      chart: null,
      period: 600, // 600 - default translates to 60. i use this so we can differentiate between redraw event & actual change of period
      loaded: false,
      objects: null,
      colors: {
        back: "#161616",
        grid: "#333333",
        text: "#fff",
        colorCandleUp: "#109a21",
        candle_dw: "#d33623",
        colorVolUp: "#3089c8",
        colorVolDw: "#d33623",
      },
      overlays: Object.values(Overlays).concat(Object.values(ChartTypes)).concat(Object.values(customIndicators)).concat(Object.values(DrawingTools)),
    };
  },
}
</script>

<style lang="scss">
.v-context, .v-context ul {
  background-color: #515151;
  color: #a7a7a7;
  z-index: 24;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  padding: 1px 0;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  overflow-y: initial;
  hr {
    width: 100%;
    margin: 0;
    border: none;
    border-top: 1px solid #444;
  }
  .instrument-picker {
    margin-top: -40px;
  }
  .period-menu {
    margin-top: -100px;
  }
  .plot-menu {
    margin-top: -50px;
  }
  .tools-menu {
    margin-top: -175px;
  }
}
.menu-item {
    padding: 0;
    cursor: pointer;
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -ms-flex-direction: row;
    flex-direction: row;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    white-space: nowrap;
    background-color: #515151;
    color: #a7a7a7;
    padding: 1px 0;
    .menu-item-text {
      -webkit-box-flex: 1;
      -ms-flex: 1;
      flex: 1;
      height: 25px;
      line-height: 25px;
      margin-left: 2px;
      margin-right: 10px;
      font-size: 1rem;
    }
    .menu-item-icon {
      height: 25px;
      color: #a7a7a7;
    }
}
.menu-item .menu-item-icon>.font-icon {
    padding: 0;
    color: #a7a7a7;
}
.menu-item-icon {
    padding: 5px 7px;
    width: 30px;
}
.menu-item:not(.disabled):hover {
    -webkit-transition: none;
    transition: none;
    background-color: #5e5e5e;
    color: #ccc;
}
// Expanded Menu
.menu-expanded {
    position: absolute;
    top: 0;
    border-left: 1px solid #444;
}

.loadingScreen {
  .loadingContent {
    background:#0e0e0e;
    display: flex;
    position: absolute;
    width: 100%;
    height: 100%;
  }


  .overlay {
    display:flex;
    position:absolute;
    width:100%;
    height:100%;
    justify-content: center;
    align-items: center;
    z-index:9999;

    svg {
      width: 80px;
    }
  }
}

body {
  font-family: Roboto, Arial, sans-serif;
  font-weight: 400;
}

.t-vue-lbtn {
  color: rgb(66, 66, 66);
  width: 16px !important;
  height: 16px !important;
}

.add-win {
}

.b-skeleton-input {
  border: 0;
}
</style>
