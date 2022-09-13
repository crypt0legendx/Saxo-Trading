<template>
  <b-modal id='acc-perf-modal' ref="acc-perf-modal" size="lg" hide-footer no-close-on-backdrop centered no-fade>
    <template v-slot:modal-title>Account Performance</template>
    <template v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
    <template v-slot:default class='pb-0 mb-0'>
      <b-button-group v-show="loaded" class='stats-group'>
        <b-button class='no-focus-outline' @click='getStats(1)' :class='{active: activeInterval === 1}'>
          Today
        </b-button>

        <b-button class='no-focus-outline' @click='getStats(7)' :class='{active: activeInterval === 7}'>
          One Week
        </b-button>

        <b-button class='no-focus-outline' @click='getStats(31)' :class='{active: activeInterval === 31}'>
          One Month
        </b-button>

        <b-button class='no-focus-outline' @click='getStats(365)' :class='{active: activeInterval === 365}'>
          One Year
        </b-button>

        <b-button class='no-focus-outline' @click='getStats(-1)' :class='{active: activeInterval === -1}'>
          All Time
        </b-button>
      </b-button-group>

      <div v-if="loaded" class='pl-stats d-flex'>
        <p>Total P/L  <span :style="data.total.pl >= 0 ? 'color:green' : 'color:red'">{{ data.total.pl | locale($store.getters.get_user_data('symbol')) }}</span></p>
        <p>Average P/L   <span :style="(data.pl.profit + data.pl.loss) >= 0 ? 'color:green' : 'color:red'">{{ data.pl.profit + data.pl.loss | locale($store.getters.get_user_data('symbol')) }}</span></p>
      </div>

      <div id='performance-chart'>
        <apexchart ref="perf_chart" type="area" height="350" :options="chartOptions" :series="series"></apexchart>
      </div>

      <b-container v-if="loaded" id='performance-trades' class='justify-content-between w-100 p-0 pt-2'>
        <b-row>
          <b-col class='p-0 pl-2 pr-2' xs='12' sm='6' md='6' lg='6' xl='6'>
            <div class='metric'>

              <div class='metric-header w-100'>
                Total trades
                <span class='text-white'>
                  {{ data.total.profit + data.total.loss }}
                </span>
              </div>

              <div class='metric-body'>
                <div class='progress-data d-flex mt-2'>
                  <small class='mr-auto text-white'>
                    {{ (data.total.profit > 0 ? ((data.total.profit / (data.total.profit + data.total.loss)) * 100) : 0).toFixed(2) }}%
                    <span class='value'>({{ data.total.profit }})</span>
                  </small>
                  <small class='ml-auto text-white'>
                    {{ (data.total.loss > 0 ? ((data.total.loss / (data.total.profit + data.total.loss)) * 100) : 0).toFixed(2) }}%
                    <span class='value'>({{ data.total.loss }})</span>
                  </small>
                </div>
                <b-progress class="mt-2" :max="data.total.profit + data.total.loss" height="2px">
                  <b-progress-bar class="no-value" v-show="data.total.profit <= 0 && data.total.loss <= 0"></b-progress-bar>

                  <b-progress-bar class="profit-bg" :value="data.total.profit" v-show="data.total.profit > 0"></b-progress-bar>

                  <b-progress-bar class="loss-bg" :value="data.total.loss" v-show="data.total.loss > 0"></b-progress-bar>

                </b-progress>
              </div>

              <div class='metric-footer w-100 d-flex'>
                <small class='profit-col mr-auto'>Profit</small>
                <small class='mx-auto'>Break-even</small>
                <small class='loss-col ml-auto'>Loss</small>
              </div>
            </div>

            <div class='metric'>

              <div class='metric-header w-100'>
                Sum of trades
              </div>

              <div class='metric-body'>
                <div class='progress-data d-flex mt-2'>
                  <small class='mr-auto text-white'>
                    {{ data.sum.profit | locale($store.getters.get_user_data('symbol')) }}
                  </small>
                  <small class='ml-auto text-white'>
                    {{ data.sum.loss | locale($store.getters.get_user_data('symbol'))}}
                  </small>
                </div>
                <b-progress class="mt-2" :max="Math.abs(data.sum.profit + data.sum.loss)" height="2px">
                  <b-progress-bar class='no-value' v-show="data.sum.profit == 0 && data.sum.loss == 0"></b-progress-bar>

                  <b-progress-bar class='profit-bg' :value="Math.abs(data.sum.profit)" v-show="data.sum.profit > 0"></b-progress-bar>

                  <b-progress-bar class='loss-bg' :value="Math.abs(data.sum.loss)" v-show="data.sum.loss != 0"></b-progress-bar>
                </b-progress>
              </div>

              <div class='metric-footer w-100 d-flex'>
                <small class='profit-col mr-auto'>Profit</small>
                <small class='loss-col ml-auto'>Loss</small>
              </div>

            </div>

            <div class='metric'>
              <div class='metric-header w-100'>
                Average profit/loss per trade
              </div>
              <div class='metric-body'>
                <div class='progress-data d-flex mt-2'>
                  <small class='mr-auto text-white'>
                    {{ data.pl.profit | locale($store.getters.get_user_data('symbol')) }}
                  </small>
                  <small class='ml-auto text-white'>
                    {{ data.pl.loss | locale($store.getters.get_user_data('symbol')) }}
                  </small>
                </div>

                <b-progress class="mt-2" :max="Math.abs(data.pl.profit + data.pl.loss)" height="2px">
                  <b-progress-bar class='no-value' v-show="data.pl.profit === 0 && data.pl.loss === 0"> </b-progress-bar>

                  <b-progress-bar class='profit-bg' :value="Math.abs(data.pl.profit)" v-show="data.pl.profit > 0">
                  </b-progress-bar>

                  <b-progress-bar class='loss-bg' :value="Math.abs(data.pl.loss)" v-show="data.pl.loss != 0">
                  </b-progress-bar>
                </b-progress>
              </div>
              <div class='metric-footer w-100 d-flex'>
                <small class='profit-col mr-auto'>Profit</small>
                <small class='loss-col ml-auto'>Loss</small>
              </div>
            </div>
          </b-col>

          <b-col class='p-0 pl-2 pr-2' xs='12' sm='6' md='6' lg='6' xl='6'>
            <div class='metric'>
              <div class='metric-header w-100'>
                Profitable trades rate
                <span class='text-white'>
                  {{ data.rate.buy + data.rate.sell }}
                </span>
              </div>
              <div class='metric-body'>
                <div class='progress-data d-flex mt-2'>
                  <small class='mr-auto text-white'>
                    {{ ((data.rate.buy / (data.rate.buy + data.rate.sell)) * 100).toFixed(2) }}%
                    <span class='value'>({{ data.rate.buy }})</span>
                  </small>
                  <small class='ml-auto text-white'>
                    {{ ((data.rate.sell / (data.rate.buy + data.rate.sell)) * 100).toFixed(2) }}%
                    <span class='value'>({{ data.rate.sell }})</span>
                  </small>
                </div>
                <b-progress class="mt-2" :max="Math.abs(data.rate.buy + data.rate.sell)" height="2px">
                  <b-progress-bar class='no-value' v-show="data.rate.buy === 0 && data.rate.sell === 0"></b-progress-bar>

                  <b-progress-bar class='buy-bg' :value="data.rate.buy" v-show="data.rate.buy > 0"></b-progress-bar>

                  <b-progress-bar class='sell-bg' :value="data.rate.sell" v-show="data.rate.sell > 0"></b-progress-bar>
                </b-progress>
              </div>
              <div class='metric-footer w-100 d-flex'>
                <small class='buy-col mr-auto'>Buy</small>
                <small class='sell-col ml-auto'>Sell</small>
              </div>
            </div>


          </b-col>
        </b-row>
      </b-container>
    </template>
  </b-modal>
</template>

<script>
import _ from 'lodash'
import moment from 'moment'
import { accPerfModal } from '../../plugins/modals.js'

export default {
  name: 'AccountPerformanceModal',
  data() {
    return {

      series: [{
        name: 'Profit',
        data: [],
      }],

      chartOptions: {
        chart: {
          id: "chart2",
          type: "area",
          height: 230,
          foreColor: "#ccc",
          toolbar: {
            autoSelected: "pan",
            show: false
          }
        },
        colors: ["rgb(253, 161, 47)"],
        stroke: {
          width: 3
        },
        grid: {
          show:false,
          borderColor: "#525252",
          clipMarkers: false,
          yaxis: {
            lines: {
              show: false
            }
          }
        },
        dataLabels: {
          enabled: false
        },
        fill: {
          gradient: {
            enabled: true,
            opacityFrom: 0.55,
            opacityTo: 0
          }
        },
        markers: {
          size: 5,
          colors: ["rgb(253, 161, 47)"],
          strokeColor: "rgb(253, 161, 47)",
          strokeWidth: 3
        },
        tooltip: {
          theme: "dark"
        },

        yaxis: {
          tickAmount: 4,
        },

        xaxis: {
          type: "datetime",
          labels: {
            show: false,
          },

          axisBorder: {
            color: '#343434',
          },
          axisTicks: {
            color: '#343434',
          },

        },
      },
      data: null,
      activeInterval : 1,
      intervals: [1, 7, 31, 365, -1],
      loaded: false,
    }
  },

  created (){
    let th = this
    this.chartOptions.yaxis.labels = {
      formatter: function (value) {
        return value.toLocaleString('en-US', { style: 'currency', currency: th.$store.getters.get_user_data('symbol') })
      }
    }
  },

  methods: {
    nextInterval(interval) {
      let i = this.intervals.findIndex(value => value == interval)
      return this.intervals[i+1]
    },
    async getStats(days) {
      this.activeInterval = days
      this.data = { 'total' : { 'profit' : 0, 'loss' : 0, 'pl' : 0 }, 'sum' : { 'profit' : 0, 'loss' : 0 }, 'pl' : { 'profit' : 0, 'loss' : 0 }, 'rate' : { 'buy' : 0, 'sell' : 0 }, 'chart' : { 'dates' : null, 'data' : null } }
      const today = new Date()
      const orders = this.$store.getters.get_orders

      try
      {
        // Calculate shit
        let grouped = _.groupBy(orders, (item) => { const dt = new Date(item.created); const search = new Date(today.getTime() - (days * 24 * 60 * 60 * 1000)); if(dt > search) return 'data'; })
        grouped.data.forEach((order) => {
          // Total
          this.data['total'][order.pl > 0 ? 'profit' : 'loss'] += 1
          this.data['total']['pl'] += order.pl

          // Sum
          this.data['sum'][order.pl > 0 ? 'profit' : 'loss'] += order.pl

          // Rate
          this.data['rate'][order.direction] += 1
        })

        // Draw Chart
        let chart_data = []
        let chart_grouped = _.groupBy(orders, (item) => { const dt = new Date(item.created); const search = new Date(today.getTime() - (days * 24 * 60 * 60 * 1000)); if(dt > search) return moment(dt.getTime()).format('YYYY-MM-DD'); })
        for(var date in chart_grouped)
        {
          if(date == 'undefined')
          continue

          let pl = 0
          chart_grouped[date].forEach((order) => {
            pl += Math.round(order.pl * 100) / 100
          })

          chart_data.push({x: date, y: pl})
        }

        this.data.pl.profit = this.data.sum.profit / this.data.total.profit
        this.data.pl.loss = this.data.sum.loss / this.data.total.loss
        this.series = [{ name: 'Profit', data: chart_data}]

        return true
      }
      catch(ex) {
        this.series = [{ name: 'Profit', data: [] }]
        return false
      }
    },
    showModal() {
      this.$refs['acc-perf-modal'].show()
      setTimeout(() => {
        document.querySelector('#acc-perf-modal___BV_modal_outer_ #acc-perf-modal').classList.toggle('light-theme', this.$store.state.light_theme);
        document.querySelector('#acc-perf-modal___BV_modal_outer_ #acc-perf-modal .modal-dialog .modal-content').style.opacity = '1';
      }, 150);

      let stats = false

      while(stats == false)
      {
        stats = this.getStats(this.nextInterval(this.activeInterval))
        if(this.activeInterval == -1 && stats == false)
        {
          this.activeInterval = 1
          break
        }
      }
      
      this.loaded = true
    }
  },
  beforeMount() {
    accPerfModal.event.$on('show-acc-perf-modal', () => this.showModal())
  },

  filters : {
    locale: function(val, currency) {
      return parseFloat(val).toLocaleString('en-US', { style: 'currency', currency: currency })
    }
  },
}
</script>

<style lang="scss">
@import '~/variables';

@media only screen and (max-width: 374px) {
  #acc-perf-modal .modal-dialog .modal-content .modal-body {
    .stats-group {
      min-width: 100% !important;

      .btn {
        font-size: 11px;
        padding: 6px !important;
      }
    }

    .pl-stats p {
      font-size: 11px !important;
    }
  }
}

@media only screen and (max-width: 425px) and (min-width: 375px) {
  #acc-perf-modal .modal-dialog .modal-content .modal-body {
    .stats-group {
      min-width: 100% !important;

      .btn {
        font-size: 13px;
        padding: 6px 8px !important;
      }
    }

    .pl-stats p {
      font-size: 12.5px;
    }
  }
}


#acc-perf-modal .modal-dialog .modal-content {
  max-width: 700px !important;
  width: 100% !important;
  opacity: 0;
}

.modal-body {
  color: #a7a7a7 !important;

  .stats-group {
    .btn {
      color: #a7a7a7;
      background-color: #424242;
      font-size: 13px;
      border: none;
      padding: 6px 22px !important;
    }

    .btn.active {
      background-color: #3c95d8 !important;
      color: #fff;
    }
  }

  .pl-stats {
    padding-top: 15px;

    p {
      font-size: 14px;
      padding-right: 10px;
    }
  }

  #performance-trades {
    .row {
      .col,
      .col-xs-12,
      .col-sm-4,
      .col-md-6,
      .col-lg-6,
      .col-xl-6 {
        margin: 0 !important;

        .metric,
        #circle-diagram {
          margin: 10px 5px;
          padding: 10px;
        }

        .metric {
          min-height: 94px;
          max-height: 94px;
          background-color: #353535;

          .metric-header {
            font-size: 12.5px !important;
          }

          .metric-body {
            min-height: 32px;
            max-height: 32px;

            .progress-data small .value {
              color: #a7a7a7;
            }

            .progress {

              .no-value {
                background-color: #808080;
              }

              .profit-bg {
                background-color: #2688d3 !important;
              }

              .loss-bg {
                background-color: #fca311 !important;
              }

              .buy-bg {
                background-color: #38b000 !important;
              }

              .sell-bg {
                background-color: #db3a34 !important;
              }
            }
          }

          .metric-body,
          .metric-footer {
            .profit-col {
              color: #2688d3 !important;
            }

            .loss-col {
              color: #fca311 !important;
            }

            .buy-col {
              color: #38b000 !important;
            }

            .sell-col {
              color: #db3a34 !important;
            }
          }
        }

        #circle-diagram {
          min-height: 200px;
        }
      }
    }
  }
}

#wrapper {
  position: relative;
  background: #000524;
  border: 1px solid #000;
  box-shadow: 0 22px 35px -16px rgba(0, 0, 0, 0.71);
  max-width: 850px;
  margin: 0 auto;
}

#chart-bar {
  position: relative;
  margin-top: -38px;
}

.link {
  position: absolute;
  bottom: 7px;
  right: 13px;
  z-index: 10;
  color: #ccc;
  font-size: 12px;
  text-decoration: none;
  font-family: Helvetica, Arial;
}
</style>
