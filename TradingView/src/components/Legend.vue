<template>
  <div ref="tb" v-if="grid_id === 0" class="chart-toolbar" v-bind:style="calc_style">
      <div :class="`order-control ${$store.getters.get_stock(stock).closed ? 'closed' : ''}`">
        <div @mouseover="showByIndex = 0" @mouseleave="showByIndex = null" class="order-button-container">
          <button v-on:click="handleOrder('sell')" class="order-button sell">
            <div class="price">
              <span v-html="stock != null ? $options.filters.formatPrice(this.$store.getters.get_stock(stock).price.sell, this.$store.getters.get_stock(stock).category) : '0.00'"></span>
            </div>
          </button>
          <div v-if="showByIndex == 0 && !$store.getters.get_stock(stock).closed" class="volume-select">
            <input type="number" step="0.01" v-model="volume" maxlength="5" />
          </div>
        </div>
        <div class="spread-wrapper">
          <div class="spread">
            <em>{{ spread(this.$store.getters.get_stock(stock)) }}</em>
          </div>
        </div>
        <div @mouseover="showByIndex = 1" @mouseleave="showByIndex = null" class="order-button-container">
          <button v-on:click="handleOrder('buy')" class="order-button buy">
            <div class="price">
              <span v-html="stock != null ? $options.filters.formatPrice(this.$store.getters.get_stock(stock).price.buy, this.$store.getters.get_stock(stock).category) : '0.00'"></span>
            </div>
          </button>
          <div v-if="showByIndex == 1 && !$store.getters.get_stock(stock).closed" class="volume-select">
            <input type="number" step="0.01" v-model="volume" maxlength="5" />
          </div>
        </div>
      </div>
      <div v-if="fulltb" class="buttons veil">
        <section>
          <i title="Create New Order" @click="newOrder" class="font-icon icon_new_order"></i>
          <i title="Period" v-on:click.stop="$refs.period.open($event)" class="font-icon icon_periods_m1"></i>
          <i title="Chart Type" v-on:click.stop="$refs.chart_type.open($event)" class="font-icon icon_chart_type_candles"></i>
        </section>
        <section>
          <i title="Panning" @click="panning" :class="['font-icon', 'icon_panning', {'highlighted' : highlighted == 0}]"></i>
          <i title="Crosshair Cursor" @click="cursor" :class="['font-icon', 'icon_crosshair', {'highlighted' : highlighted == 1}]"></i>
          <i title="Drawings" v-on:click.stop="$refs.drawings.open($event)" :class="['font-icon', 'icon_lines_trend', {'highlighted' : highlighted == 2}]"></i>
          <i title="Indicators" @click="openIndicators" :class="['font-icon', 'icon_indicators', {'highlighted' : highlighted == 4}]"></i>
          <!-- <i title="Range Tools" @click="$easycm($event, $root,  wndID + '#indicators')" :class="['font-icon', 'mdi', 'mdi-vector-square-plus', {'highlighted' : highlighted == 3}]"></i>
          !-->

          <i title="List of drawings on chart" v-on:click.stop="$refs.obj_manager.open($event)" :class="['font-icon', 'icon_graph_objects', {'highlighted' : highlighted == 5}]"></i>
        </section>
        <section>
          <i title="Remove Object" @click="rmObject" v-show="objectHandled" style="color:#ff6969 !important" class="font-icon far fa-plus-square mdi mdi-trash-can-outline"></i>
        </section>
      </div>
      <div v-else class="buttons veil">
        <section>
          <i title="Actions" @click="$easycm($event, $root,  wndID)" :class="['font-icon', 'icon_window_menu']"></i>
          <i title="Remove Object" @click="rmObject" v-show="objectHandled" style="color:#ff6969 !important" class="font-icon far fa-plus-square mdi mdi-trash-can-outline"></i>
         </section>
      </div>
    
    <vue-context ref="drawings">
      <div class="menu">
        <div @click="figures(0)" class="menu-item selected"> <div class="menu-item-icon"><i class="font-icon icon_lines_trend "></i></div> <span class="menu-item-text">Trendline</span> <div class="menu-item-children"></div> </div>
        <div @click="figures(1)" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_fibo_fan "></i></div> <span class="menu-item-text">Fibonacci Retracement</span> <div class="menu-item-children"></div> </div>
        <div @click="figures(2)" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_fibo_arc-57 "></i></div> <span class="menu-item-text">Fibonacci Fan</span> <div class="menu-item-children"></div> </div>
        <div @click="figures(3)" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_fibo_arc-96 "></i></div> <span class="menu-item-text">Fibonacci Arc</span> <div class="menu-item-children"></div> </div>
        <div @click="figures(4)" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_fibo_timez "></i></div> <span class="menu-item-text">Fibonacci Timezone</span> <div class="menu-item-children"></div> </div>
        <div @click="figures(5)" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_vert_market "></i></div> <span class="menu-item-text">Vertical Marker</span> <div class="menu-item-children"></div> </div>
        <div @click="figures(6)" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_hor_market "></i></div> <span class="menu-item-text">Horizontal Marker</span> <div class="menu-item-children"></div> </div>
        <div @click="figures(7)" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_rectangle "></i></div> <span class="menu-item-text">Rectangle</span> <div class="menu-item-children"></div> </div>
        <div @click="figures(8)" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_ellipse "></i></div> <span class="menu-item-text">Ellipse</span> <div class="menu-item-children"></div> </div>
        <div @click="figures(9)" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_lines_abcd_pattern "></i></div> <span class="menu-item-text">ABCD Pattern</span> <div class="menu-item-children"></div> </div>
      </div>
     </vue-context>

     <vue-context ref="period">
      <div class="menu">
        <div @click="period(0)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_periods_m1 "></i> </div> <span class="menu-item-text">1 minute</span> <div class="menu-item-children"></div> </div>
        <div @click="period(1)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_periods_m2 "></i> </div> <span class="menu-item-text">5 minutes</span> <div class="menu-item-children"></div> </div>
        <div @click="period(2)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_periods_m15 "></i> </div> <span class="menu-item-text">15 minutes</span> <div class="menu-item-children"></div> </div>
        <div @click="period(3)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_periods_m30 "></i> </div> <span class="menu-item-text">30 minutes</span> <div class="menu-item-children"></div> </div>
        <div @click="period(4)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_periods_h1 "></i> </div> <span class="menu-item-text">1 hour</span> <div class="menu-item-children"></div> </div>
      </div>
     </vue-context>

     <vue-context ref="chart_type">
      <div class="menu">
        <div @click="type(0)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_chart_type_bar "></i> </div> <span class="menu-item-text">Bar Chart</span> <div class="menu-item-children"></div> </div>
        <div @click="type(1)" class="menu-item selected"> <div class="menu-item-icon"> <i class="font-icon icon_chart_type_candles "></i> </div> <span class="menu-item-text">Candlesticks</span> <div class="menu-item-children"></div> </div>
        <div @click="type(2)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_chart_ha_candles "></i> </div> <span class="menu-item-text">Heikin Ashi</span> <div class="menu-item-children"></div> </div>
        <div @click="type(3)" class="menu-item"> <div class="menu-item-icon"><i class="font-icon icon_chart_type_hlc "></i></div> <span class="menu-item-text">HLC Chart</span> <div class="menu-item-children"></div> </div>
        <div @click="type(4)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_chart_type_line "></i> </div> <span class="menu-item-text">Line Chart</span> <div class="menu-item-children"></div> </div>
        <div @click="type(5)" class="menu-item"> <div class="menu-item-icon"> <i class="font-icon icon_chart_type_dots "></i> </div> <span class="menu-item-text">Dots Chart</span> <div class="menu-item-children"></div> </div>
      </div>
     </vue-context>

     <vue-context ref="obj_manager">
      <ul v-if="objects != null && objects.length > 0" class="menu v-context">
        <div v-for="object in objects" class="menu" :key="object.name">
          <div @click="manageObject(object)" class="menu-item">
              <div class="menu-item-icon">
                <i class="font-icon icon_lines_fibo_arc-96"></i>
              </div>  
              <span class="menu-item-text">{{ object.last.name }}</span>
              <div class="menu-item-children">
                <i @click="manageObject(object, true)" class="font-icon icon_win_close"></i>
              </div>
            </div>
          </div>
        </ul>
      <ul v-else class="menu v-context" style="padding:3px">
          <span class="menu-item-text">No objects present on chart</span>
      </ul>
     </vue-context>

    <div v-if="ohlcv_show" class="vert-legend">
      <div class="ohlcv">
        <section>
          <span>
              {{stock}} 
              O: <span class="t-vue-lspan" >{{ohlcv[0]}}</span>
              H: <span class="t-vue-lspan" >{{ohlcv[1]}}</span>
              L: <span class="t-vue-lspan" >{{ohlcv[2]}}</span>
              C: <span class="t-vue-lspan" >{{ohlcv[3]}}</span>
          </span>
        </section>
      </div>

      <div v-if="indicators && indicators.length > 0" class="inds">
        <div class="t-vue-ind" v-for="ind in this.indicators">
            <span class="t-vue-iname">{{ind.name}}</span>
            <span class="t-vue-ivalues" v-if="ind.v">
                <span class="t-vue-lspan t-vue-ivalue"
                    v-if="show_values"
                    v-for="v in ind.values" :style="{ color: v.color }">
                    {{v.value ? 'V:' : ''}} {{v.value}}
                </span>
            </span>
            <button-group
                v-bind:buttons="common.buttons"
                v-bind:config="common.config"
                v-bind:ov_id="ind.id"
                v-bind:grid_id="grid_id"
                v-bind:index="ind.index"
                v-bind:tv_id="common.tv_id"
                v-bind:display="ind.v"
                v-on:legend-button-click="button_click">
            </button-group>
            <span v-if="ind.unk" class="t-vue-unknown">
                (Unknown type)
            </span>
            <transition name="tvjs-appear">
                <spinner :colors="common.colors" v-if="ind.loading">
                </spinner>
            </transition>
          </div>
        </div>
        </div>
      </div>
</template>
<script>

import VueContext from 'vue-context';
import ButtonGroup from './ButtonGroup.vue'
import Spinner from './Spinner.vue'
import Labels from '../helpers/menus.json'

export default {
  name: 'ChartLegend',
  props: [
    'common', 'values', 'grid_id', 'meta_props', 'instrument', 'wndID'
  ],
  components: { ButtonGroup, Spinner, VueContext },
  data() {
    return {
      ohlcv_show: false,
      fulltb: true,

      objectHandled: false,
      currentTool: null,
      highlighted: 0,
      stock: null,
      volume: 0.1,
      labels: Labels,
      showByIndex: null,
    }
  },

  mounted() {

    try { //hide ohlcv
     this.$refs.tb.closest('.window-frame').addEventListener('mouseenter', () => {
        this.ohlcv_show = true
      })
      
      this.$refs.tb.closest('.window-frame').addEventListener('mouseleave', () => {
        this.ohlcv_show = false
      })

      //responsive-ish legend
      const tbListener = new ResizeObserver((e) => {
        if(!this.$refs.tb) return
        if(this.$refs.tb.offsetWidth <= 450)
          this.fulltb = false
      }).observe(this.$refs.tb)
    } catch(e) {

    }

  },

  created() {
    this.stock = this.instrument

    console.log(this.objects)

    this.$store.subscribe((mutation, state) => {
      if(mutation.type == "broadcast") {
        if(mutation.payload.wndID != this.wndID) return
        

        if(mutation.payload.event == 'chart_internal_event') {
          this.interpretInternalEvent(mutation.payload.body)
          
          if(mutation.payload.body.client) {
             mutation.payload.body.client = false
             this.$emit('custom-event', mutation.payload.body)
          }
        }

        if(mutation.payload.event == 'periodChange') {
          let content = mutation.payload.body
        }

        if(mutation.payload.event == 'typeChange') {
          let content = mutation.payload.body
        }
      }
    })
  },

  computed: {
    objects() {
      let objects = []
      const overlays = this.common.data

      overlays.forEach((overlay) => {
        if(overlay.last.drawing === true)
          objects.push(overlay)
      })

      return objects
    },
    ohlcv() {
      if (!this.$props.values || !this.$props.values.ohlcv) {
        return Array(6).fill('n/a')
      }
      const prec = this.layout.prec

      // TODO: main the main legend more customizable
      let id = this.main_type + '_0'
      let meta = this.$props.meta_props[id] || {}
      if (meta.legend) {
        return (meta.legend() || []).map(x => x.value)
      }

      return [
        this.$props.values.ohlcv[1].toFixed(prec),
        this.$props.values.ohlcv[2].toFixed(prec),
        this.$props.values.ohlcv[3].toFixed(prec),
        this.$props.values.ohlcv[4].toFixed(prec),
        this.$props.values.ohlcv[5] ?
        this.$props.values.ohlcv[5].toFixed(2):
        'n/a'
      ]
    },
    // TODO: add support for { grid: { id : N }}
    indicators() {
      const values = this.$props.values
      const f = this.format
      var types = {}

      return this.json_data.filter(
        x => x.settings.legend !== false && !x.main
      ).map(x => {
        if (!(x.type in types)) types[x.type] = 0
        const id = x.type + `_${types[x.type]++}`
        return {
          v: 'display' in x.settings ? x.settings.display : true,
          name: x.name || id,
          index: (this.off_data || this.json_data).indexOf(x),
          id: id,
          values: values ? f(id, values) : this.n_a(1),
          unk: !(id in (this.$props.meta_props || {})),
          loading: x.loading
        }
      })
    },
    calc_style() {
      let top = this.layout.height > 150 ? 10 : 5
      let grids = this.$props.common.layout.grids
      let w = grids[0] ? grids[0].width : undefined
      return {
        top: `${this.layout.offset + top}px`,
        width: `${w-20}px`
      }
    },
    layout() {
      const id = this.$props.grid_id
      return this.$props.common.layout.grids[id]
    },
    json_data() {
      return this.$props.common.data
    },
    off_data() {
      return this.$props.common.offchart
    },
    main_type() {
      let f = this.common.data.find(x => x.main)
      return f ? f.type : undefined
    },
    show_values() {
      return this.common.cursor.mode !== 'explore'
    }
  },

  filters : {
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
  },

  methods: {

    manageObject(object, del) {
      if(!del) del = false

      this.$store.commit('broadcast', {
        event: 'chart_internal_event',
        wndID: this.wndID,
        body: {event: 'object-selected', client: true,  args: [object.grid.id, object.name, object.settings.$uuid]}
      })

      if(del)
        this.$store.commit('broadcast', {
          event: 'chart_internal_event',
          wndID: this.wndID,
          body: {event: 'tool-selected', client: true, args: ['System:Remove']}
        })
    },

    openIndicators() {
       this.$emit('custom-event', {
                event: 'legend-button-click',
                 args: [{ button: "add", dataIndex: 0, grid: 0, overlay: "", type: "onchart", }],
         })
    },


    interpretInternalEvent(e) {
      switch (e.event) {
        case "remove-tool":
          this.currentTool = null
          this.objectHandled = false
          break;
        case "object-selected":
          this.objectHandled = true
          break;

        case "drawing-mode-off":
          this.currentTool = null
          this.objectHandled = true
          break;

        case "tool-selected":
          {
            this.currentTool = e.args[0]

            if(e.category == "drawings") {
              this.highlighted = 2
              this.objectHandled = true
              this.currentTool = e.args[0]
            }

            if(e.category == "indicators") {
              this.highlighted = 3
              this.objectHandled = true
              this.currentTool = e.args[0]
            }

            switch(e.args[0]) {
              case "System:Remove":
                this.objectHandled = false
                this.highlighted = 0
              break;

              case "Cursor":
                this.highlighted = 1
                this.objectHandled = false
                this.currentTool = null
              break;

              case "Panning":
                this.highlighted = 0
                this.objectHandled = false
                this.currentTool = null
               break;
            }
          }
          break;

        case "grid-mousedown":
          if(this.currentTool != null &&
             this.currentTool != "System:Remove" &&
             this.currentTool != "Panning" &&
             this.currentTool != "Cursor"){
            this.objectHandled = true
          }
          else {
            this.objectHandled = false
            this.highlighted = 0
          }

        break;

      }
    },


    rmObject() {
      this.$emit('custom-event', {
                event:'tool-selected',
                args: ['System:Remove']
              })
    },


    altcm() {
      this.$emit('custom-event', {
                ignore: true,
                event:'altcm',
                wndID: this.wndID
              })
    },


    spread(symb) {
      try {
        const stock = symb
          if(!stock) return 0.00

        var pip = Math.abs(stock.price.buy - stock.price.sell) * stock.price.scale

        return parseFloat(pip.toFixed(2))
      } catch(err) {
        return 0.00
      }
    },

    handleOrder(dir) {
      if(!this.$store.getters.get_stock(this.stock).closed)
       this.$emit('custom-event', {event:'quick_order',args: {vol: this.volume ,direction: dir}, wndID: this.wndID, ignore: true})
    },


    toggleCheck(index, childrenID) {
      if(index == 0)
      index = 'time'
      if(index == 1)
      index = 'type'
      if(index == 2)
      index = 'drawings'

      for(let child of this.labels[index])
      child.icon = ''

      this.labels[index][childrenID].icon = 'mdi mdi-check'
    },

    panning() {
      this.$emit('custom-event', {
          event:'tool-selected',
          args: ['Panning']
        })

      this.highlighted = 0
    },

    newOrder() {
      this.$emit('custom-event', {
                ignore: true,
                wndID: this.wndID,
                event:'new_order'
              })
    },

    cursor() {
      this.$emit('custom-event', {
                event:'tool-selected',
                args: ['Cursor']
              })
       this.highlighted = 1
    },

    objmng(idx) {

    },


    period(idx) {
      switch (idx) {
        case 0: {
          this.$emit('custom-event', {
            event:'period',
            wndID: this.wndID,
            args: {
              value: 1,
              menu_item: idx[0]
            },
            ignore: true
          })
        }
        break;

        case 1: {
          this.$emit('custom-event', {
            event:'period',
            wndID: this.wndID,
            args: {
              value: 5,
              menu_item: idx[0]
            },
            ignore: true
          })
        }
        break;

        case 2: {
          this.$emit('custom-event', {
            event:'period',
            wndID: this.wndID,
            args: {
              value: 15,
              menu_item: idx[0]
            },
            ignore: true
          })
        }
        break;

        case 3: {
          this.$emit('custom-event', {
            event:'period',
            wndID: this.wndID,
            args: {
              value: 30,
              menu_item: idx[0]
            },
            ignore: true
          })
        }
        break;

        case 4: {
          this.$emit('custom-event', {
            event:'period',
            wndID: this.wndID,
            args: {
              value: 60,
              menu_item: idx[0]
            },
            ignore: true
          })
        }
        break;
      }
    },

    async type(idx) {
      this.$emit('custom-event', {
        wndID: this.wndID,
        event:'type',
        args: {
          value: idx,
        },
        ignore: true
      })
    },

    async indicatorsMenu(idx) {
      var tool = null

      switch (idx[0]) {
        case 0:
          tool = "RangeTool:Price"
          break;

        case 1:
          tool = "RangeTool:Time"
          break;
        
        case 2:
          tool = "RangeTool:PriceTime"
          break;
      }

      this.$emit('custom-event', {
        event:'tool-selected', args: [tool]
      })
       this.highlighted = 3
    } ,

    async figures(idx) {
      var tool = null

      switch (idx) {
        case 0:
          tool = "LineTool:Segment"
          break;
        case 1:
          tool = "Retracement:Fibonacci"
          break;
        case 2:
          tool = "Fan:Fibonacci"
          break;
        case 3:
          tool = "Arc:Fibonacci"
          break;
        case 4:
          tool = "Timeline:Fibonacci"
          break;
        case 5:
          tool = "VerticalLine:Extended"
          break;
        case 6:
          tool = "HorizontalLine:Extended"
          break;
        case 7:
          tool = "Rectangle:Default"
          break;
        case 8:
          tool = "Elipse:Segment"
          break;
        case 9:
          tool = "ABCD:Default"
          break;
      }

       this.$emit('custom-event', {
          event:'tool-selected', args: [tool]
       })

        this.highlighted = 2
    },

    pleaseWork() {
      // yay
    },
    handleClick(e) {
      
    },
    format(id, values) {
      let meta = this.$props.meta_props[id] || {}
      // Matches Overlay.data_colors with the data values
      // (see Spline.vue)
      if (!values[id]) return this.n_a(1)

      // Custom formatter
      if (meta.legend) return meta.legend(values[id])

      return values[id].slice(1).map((x, i) => {
        const cs = meta.data_colors ? meta.data_colors() : []
        if (typeof x == 'number') {
          // Show 8 digits for small values
          x = x.toFixed(Math.abs(x) > 0.001 ? 4 : 8)
        }
        return {
          value: x,
          color: cs ? cs[i % cs.length] : undefined
        }
      })
    },
    n_a(len) {
      return Array(len).fill({ value: 'n/a' })
    },
    button_click(event) {
      this.$emit('legend-button-click', event)
    }
  }
}
</script>
<style>

.v-context * {
  box-sizing: border-box !important;
}

.vert-legend {
  display:flex;
  flex-basis:100%;
  flex-direction: column;
}

.inds {
  display:flex;
  flex-direction: column;
  margin-top: 15px !important;
}

.t-vue-iname, .t-vue-ivalues {
  font-size:13px;
  vertical-align: middle;
}

.t-vue-ind {
  z-index: 99999;
  margin-left: 0 !important;
}

.ohlcv {
    z-index: 1;
    font-size: 13px;
    flex-basis: 100%;
    color: #b1b1b1;;
}

.highlighted {
  color:white !important
}


button.order-button.sell:hover {
    background: #ff313c;
}

button.order-button.buy:hover {
    background: #2bd851;
}


.chart-toolbar {
  height: 20px;
  position: absolute;
  top: 5px;
  left: 5px;

  display:flex;
  flex-direction:row;
  flex-wrap: wrap;
}

@media (min-resolution: 2x) {
  .chart-toolbar {
    font-weight: 400;
  }
}

/* Buy-Sell */
.chart-toolbar .order-control {
  z-index: 8;
  top: 2px;
  display: flex;
  width: 150px;

}

.order-control .order-button-container {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-flex: 0;
  -ms-flex: 0 0;
  flex: 0 0;
  position: relative;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  padding-bottom: 5px;
  min-height:50px;
}

.order-control .order-button {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
  -ms-flex-direction: row;
  flex-direction: row;
  height: 25px;
  min-width: 75px;
  font-size: 1.1rem;
  color: #fff;
  cursor: pointer;
  position: relative;
  border: 0;
  text-align: left;
}

.order-control .sell {
  border-radius: 2px;
  background-color: #d81720;
  color: #fff;
  cursor: pointer;
}

.order-control .buy {
  border-radius: 2px;
  background-color: #19a337;
  color: #fff;
  cursor: pointer;
}

.order-control .spread-wrapper {
  width: 2px;
}

.order-control .spread {
  z-index: 12;
  text-align: center;
  color: #fff;
  font-size: .8em;
  background-color: #161616;
  -webkit-transform: translateX(-50%);
  transform: translateX(-50%);
  display: inline-block;
  padding: 1px 2px;
  position: relative;
  left: 1px;
  border-radius: 2px;
  top: 5px;
  min-width: 20px;
}

.order-control .spread em {
  font-style: normal;
  font-size: 1rem;
  color: #a7a7a7;
}

.order-control .order-button-container .order-button .price {
  margin-top: 5px;
}

.order-control .order-button-container:last-child .order-button .price {
  margin-left: 16px;
}

.order-control .order-button-container .volume-select {
    height: 0;
}

.volume-select {
    position: relative!important;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
}

.order-control .order-button-container .volume-select > input {
    min-width: 45px;
    height: 28px;
    font-size: 1.1rem;
}
.volume-select input {
    width: 100%;
    min-width: 90px;
    height: 30px;
    color: #a7a7a7;
    border: none;
    background: #353535;
    padding-left: 5px;
    font-size: 1.2rem;
    border-top-left-radius: 2px;
    border-bottom-left-radius: 2px;
}

.volume-select input:focus {
    outline: none;
}

/* Controllers */
.chart-toolbar .buttons {
  top: 2px;
  left: 175px;
  z-index: 100;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  height: 27px;
}

.buttons.veil {
  display:flex;
  align-items: center;
  margin-left:60px;
}

.buttons.veil i {
  font-size:16px !important
}


.chart-toolbar .buttons section {
  margin-right: 15px;
}




</style>
