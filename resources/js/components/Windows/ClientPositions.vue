<template>
  <div class="w-100 h-100 fluid pa-0">
     <!-- SENDING TOPBAR !-->
     <portal :to="windID">
      <i class="font-icon icon_client_positions"></i>
      <span>Client Positions</span>
    </portal>
    <!-- END OF TOPBAR !-->

    <div class="client-positions">
      <b-row class="date-now brow">
        {{ this.$store.state.current_date }}
      </b-row>

      <b-row v-for='(order, name) in orders' :key="instrument" class="pos-container brow">
        <div class="position_rab">
          <div class="instrument">
            <span> {{ name }} </span>
            <span> {{ parseFloat(order.buy / (order.buy + order.sell) * 100).toFixed(2)}}% </span>
          </div>
          <div class="progress">
            <b-progress class="mt-2" :max="100" height="100%">
              <b-progress-bar class='no-value' />
              <b-progress-bar class='profit-bg' :value="parseFloat(order.buy / (order.buy + order.sell) * 100)" v-show="1 > 0" />

              <b-progress-bar class='loss-bg' :value="parseFloat(order.sell / (order.buy + order.sell) * 100)" v-show="1 > 0" />
            </b-progress>
          </div>

          <div class="buy-percent">
            <span> {{ parseFloat(order.sell / (order.buy + order.sell) * 100).toFixed(2) }}% </span>
          </div>
        </div>
      </b-row>


      <b-row class="brow metric_">
        <div class="left">
          BUY
        </div>
        <div class="center">
          50%
        </div>
        <div class="right">
          SELL
        </div>
      </b-row>
    </div>
  </div>
</template>


<style lang="scss">
@import '~/variables.scss';

.metric_ {
  display:flex;
  width:100%;
  flex-direction:row;
  justify-content:space-between;
  align-items:center;
  padding:0 100px;

  .left, .right, .center {
    font-size:13px;
    font-weight:400;
    font-family: 'Roboto'
  }

  .left {
    color: #19a337;
  }

  .right {
    color: #d81720;
    text-align:right;
  }

  .center {
    display:flex;
    justify-content:center;
    color: $flight_color;
    margin-left:10px;
  }
}


.brow {
  margin-left: 0 !important;
  margin-right: 0 !important;
}

.client-positions {
  padding:10px 10px;

  .progress {
    height:1.2rem !important;
    display:flex;
    justify-content:center;
    width:100%;
    background: none !important;
    border-radius:0px !important;

    .no-value {
      background-color: #808080 !important;
    }

    .profit-bg {
      background-color: #19a337 !important;
    }

    .loss-bg {
      background-color: #d81720 !important;
    }

    .buy-bg {
      background-color: #38b000 !important;
    }

    .sell-bg {
      background-color: #db3a34 !important;
    }
  }

  .pos-container {
    margin: 20px 0;
    width:100%;

    .position_rab {
      width:100%;
      display:flex;
      flex-direction:row;
      justify-content:space-between;
      align-items:center;

      .instrument {
        margin-top: -13px;
        display:flex;
        flex-direction:column;

        span:first-child {
          color: $flight_color;
          font-size:13px;
          font-weight:400;
        }

        span:last-child {
          color: #19a337;
          font-size:13px;
          font-weight:500;
        }
      }

      .buy-percent {
        color: #d81720;
        font-size:13px;
        font-weight:500;
        margin-top:5px;
      }

      .instrument {
        width:100px;
      }

      .buy-percent {
        width: 100px;
        text-align: right;
      }
    }

  }

  .date-now {
    color: $flight_color;
    font-family: 'Roboto';
    font-size:24px;
    font-weight:400;
  }
}

</style>

<script>
export default {
  name : "Chart",


  data () {
    return {
      //...
    }
  },

  props : {
    windID : Number,
  },
  computed:
  {
    orders() {
      let fin = {}
      this.$store.getters.get_orders.forEach((order) => {
        if(fin[order.stock.name] == undefined)
          fin[order.stock.name] = { buy: 0, sell: 0 }

        fin[order.stock.name][order.direction] += 1
      })
      console.log(this.$store.getters.get_orders)
      return fin
    }
  },
  created() {
    
  },
}
</script>
