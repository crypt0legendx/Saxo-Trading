<template>
  <b-modal id='pos-info-modal' ref="pos-info-modal" size="md" hide-footer no-close-on-backdrop centered no-fade >
  <template v-slot:modal-title> Position Information</template>
  <template v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
  <template v-slot:default>
    <div class="trade-info-dialog">

      <!-- Basic -->
      <div class="trade-info-block">
        <div class="trade-info-row">
          <div class="trade-info-item">
            <div class="title">Position ID</div>
            <div class="value">PID{{order.id}}</div>
          </div>
          <div class="trade-info-item">
            <div class="title">Status</div>
            <div class="value">{{ !order.close_price ? 'Open' : 'Closed' }}</div>
          </div>
        </div>
      </div>

      <!-- Instrument -->
      <div class="trade-info-block dim">
        <div class="trade-info-row">
          <div class="trade-info-item">
            <div class="title">Instrument</div>
            <div class="value">{{ order.stock.name }}</div>
          </div>
        </div>
        <div class="trade-info-row">
          <div class="trade-info-item">
            <div class="title">Current quantity</div>
            <div class="value">{{ order.volume }}</div>
          </div>
          <div class="trade-info-item">
            <div class="title">Current direction</div>
            <div class="value">{{ order.type == 'sell' ? 'Sell' : 'Buy' }}</div>
          </div>
        </div>
      </div>

      <!-- Modifications -->
      <div class="trade-info-block">
        <div class="trade-info-row">
          <div class="trade-info-item">
            <div class="title">Created time</div>
            <div class="value">{{ dateFormat(order.created_at) }}</div>
          </div>
          <div class="trade-info-item">
            <div class="title">Last modified time</div>
            <div class="value">{{ order.updated_at == null ? '—' : dateFormat(order.updated_at) }}</div>
          </div>
        </div>
      </div>

      <!-- Price -->
      <div class="trade-info-block">
        <div class="trade-info-row">
          <div class="trade-info-item">
            <div class="title">Entry price</div>
            <div class="value">{{ order.open_price }}</div>
          </div>
          <div class="trade-info-item">
            <div class="title">Swap</div>
            <div class="value">€&thinsp;0</div>
          </div>
        </div>
      </div>

      <!-- SL/TP -->
      <div class="trade-info-block">
        <div class="trade-info-row">
          <div class="trade-info-item">
            <div class="title">Current SL</div>
            <div class="value">{{ order.stop_loss != null ? order.stop_loss : '—'}}</div>
          </div>
          <div class="trade-info-item">
            <div class="title">Distance (pips)</div>
            <div class="value">{{ distancePips(order) }}</div>
          </div>
        </div>
        <div class="trade-info-row">
          <div class="trade-info-item">
            <div class="title">Current TP</div>
            <div class="value">{{ order.take_profit != null ? order.take_profit : '—' }}</div>
          </div><div class="trade-info-item">
            <div class="title">Distance (pips)</div>
            <div class="value">{{ distancePips(order) }}</div>
          </div>
        </div>
      </div>

      <!-- Profits -->
      <div class="trade-info-block">
        <div class="trade-info-row">
          <div class="trade-info-item">
            <div class="title">Unr. Commissions</div>
            <div class="value">€&thinsp;0.00</div>
          </div>
          <div class="trade-info-item">
            <div class="title">Real. Commissions</div>
            <div class="value">€&thinsp;0.00</div>
          </div>
        </div>
        <div class="trade-info-row">
          <div class="trade-info-item">
            <div class="title">Net Unr. P&amp;L</div>
            <div class="value">
              <span :title="'Profit/Loss: ' + pl(order)" :class="parseFloat(order.pl.toFixed(2)) > 0 ? 'up' : ''">
                {{ pl(order) }}
              </span>
            </div>
          </div>
          <div class="trade-info-item">
            <div class="title">Gross Unr. P&amp;L</div>
            <div class="value">
              <span :title="'Profit/Loss: ' + pl(order)" :class="parseFloat(order.pl.toFixed(2)) > 0 ? 'up' : ''">
                {{ order.pl.toLocaleString('en-US', { style: 'currency', currency: $store.getters.get_user_data('symbol') }) }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>
</b-modal>
</template>

<script>
import {posInfo} from '../../plugins/modals.js';

export default {
  name: 'PosInfoModal',
  methods: {
    pl(order) {
      let string = ''
      if(parseFloat(order.pl.toFixed(2)) > 0)
        string += '+'
      if(parseFloat(order.pl.toFixed(2)) < 0)
        string += '-'
      return string + order.pl.toLocaleString('en-US', { style: 'currency', currency: this.$store.getters.get_user_data('symbol') })
    },

    distancePips(order) {
      if(order.take_profit == null || order.stop_loss == null)
        return '—'

      let distance = (Math.abs((order.take_profit == null ? order.stop_loss : order.take_profit) - order.stock.price) * 10000).toFixed(2)
      return distance
    },

    dateFormat(date) {
      var t = new Date(date)
      return new Date(t.getTime() + t.getTimezoneOffset() * 60000 + (3600000 * this.$store.getters.get_user_data('time_zone'))).toLocaleString(this.$store.getters.get_user_data('locale'))
    },

    showModal() {
      this.$refs['pos-info-modal'].show()
      setTimeout(() => {
        document.querySelector('#pos-info-modal___BV_modal_outer_ #pos-info-modal').classList.toggle('light-theme', this.$store.state.light_theme);
        document.querySelector('#pos-info-modal___BV_modal_outer_ #pos-info-modal .modal-dialog .modal-content').style.opacity = '1';
      }, 150);
    },

    hideModal() {
      this.$refs['pos-info-modal'].hide()
    },


  },

  beforeMount() {
    posInfo.event.$on('show-pos-info-modal', (pid) => {
      this.showModal();

      this.order = this.$store.getters.get_order(pid)
    })
  },

  data: function() {
    return {
      order: null,
    }
  },

  computed : {

  },

  filters : {

  },
}
</script>

<style lang='scss'>
@import '~/variables';
@import '~/modal_css';

#pos-info-modal .modal-dialog .modal-content {
    opacity: 0;
}

#pos-info-modal .modal-dialog .modal-content .modal-body {
    padding: 0;
}

#pos-info-modal .modal-dialog {
    max-width: 300px;
}

.trade-info-dialog {
    margin: 0;
    padding: 0!important;
    color: #6e6e6e;
    font-size: 12px;
}

.trade-info-row {
    padding: 10px;
    height: 50px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    width: 100%;
}

.trade-info-item {
    width: 50%;

    .title {
      white-space: nowrap;
    }

    .value {
      white-space: nowrap;
      color: #a7a7a7;
      font-size: 14px;
    }
}

.trade-info-block {
    border-bottom: 1px solid #161616;
}

.up {
    color: #19a337;
}

</style>
