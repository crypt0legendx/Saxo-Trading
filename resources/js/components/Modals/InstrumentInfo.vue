<template>
  <b-modal id='inst-info-modal' ref="inst-info-modal" size="sm" hide-footer no-close-on-backdrop centered no-fade >
  <template v-slot:modal-title> {{stock.api_name}} Information</template>
  <template v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
  <template v-slot:default>
    <div class="inst-info">
      <div class="header">
        <h1>{{stock.api_name}} Information</h1>
        <span>{{ stock.description }}</span>
      </div>

      <v-container class="info-container">
        <v-col cols="12">
          <div class="group mt-2">
            <h4>Pip Size:</h4>
            <span>
              {{ 1 / stock.price.scale }} ({{ stock.price.scale == 100 ? 2 : 4 }} digits)
            </span>
          </div>

          <div class="group mt-2">
            <h4>Lot Size:</h4>
            <span>
              {{ stock.lot.toLocaleString() }}
            </span>
          </div>

          <div class="group mt-2">
            <h4>Commission (per mil.):</h4>
            <span></span>
          </div>

          <div class="group mt-2">
            <h4>Minimum Price Change:</h4>
            <span>
              0.01
            </span>
          </div>

          <div class="group mt-2">
            <h4>Minimum Trade Volume:</h4>
            <span>
              0.01
            </span>
          </div>

          <div class="group mt-2">
            <h4>Maximum Trade Volume:</h4>
            <span>
              100,000
            </span>
          </div>

          <div class="group mt-2">
            <h4>Maximum Total Trade Volume:</h4>
            <span>
              0
            </span>
          </div>

          <div class="group mt-2">
            <h4>Stops Level:</h4>
            <span>
              {{ (stock.price.sell - stock.price.buy) * stock.price.scale }} pips
            </span>
          </div>

          <div class="group mt-4">
            <h4>Swap Long:</h4>
            <span>
              x
            </span>
          </div>

          <div class="group mt-2">
            <h4>Swap Short:</h4>
            <span>
              x
            </span>
          </div>

          <div class="group mt-2">
            <h4>3-Days-Swaps:</h4>
            <span>
              Friday
            </span>
          </div>

          <div class="group mt-4">
            <h4>Market Schedule:</h4>
          </div>

          <div class="group mt-4">
            <h4>Buy Positions:</h4>
            <span>
              0
            </span>
          </div>

          <div class="group mt-2">
            <h4>Sell Positions: </h4>
            <span>
              0
            </span>
          </div>
        </v-col>
      </v-container>
    </div>
  </template>
</b-modal>
</template>

<style lang='scss'>
@import '~/variables';
@import '~/modal_css';

#inst-info-modal___BV_modal_body_ {
  max-height:450px;
  padding: 0 !important;
}

#inst-info-modal .modal-dialog .modal-content {
  opacity: 0;
}

.info-container {
  width:100%;
  height:100%;
  overflow-y: auto;
  padding:10px;
}


.inst-info {
  margin:2px 0 !important;
  font-family: 'Roboto';
  padding:0 !important;

  .col {
    padding: 0 !important;
  }

  .container {
    height:350px;
    padding: 8px;

    &::-webkit-scrollbar {
      width: 5px;
    }

    /* Track */
    &::-webkit-scrollbar-track {
      background: none;
    }

    /* Handle */
    &::-webkit-scrollbar-thumb {
      background: #888;
    }

    /* Handle on hover */
    &::-webkit-scrollbar-thumb:hover {
      background: #555;
    }
  }



  .header {
    height:80px;
    padding:10px;
    display:flex;
    flex-direction: column;

    h1 {
      color: #a7a7a7;
      margin: 5px 0;
      font-size: 2rem;
      font-weight: 400;
      padding: 0 10px;
    }

    span {
      padding: 0 10px 10px;
      font-size:1.4rem;
    }
  }

  hr {
    border-top: 1px solid rgb(177 177 177 / 14%);
    width: 100%;
  }

  .group {
    margin:3px 0;
    display:flex;
    flex-direction:row;
    justify-content:space-between;
    width:100%;
    align-items:center;

    h4 {
      font-size: 12px;
      color:#a7a7a7;
      font-weight:400;
      margin: 0 !important;
    }

    span {
      margin:3px 0;
      font-size:12px;
    }
  }
}

</style>

<script>
import {instInfo} from '../../plugins/modals.js';

export default {
  name: 'InstInfoModal',
  methods: {
    dateFormat(date) {
      var t = new Date(date)
      return new Date(t.getTime() + t.getTimezoneOffset() * 60000 + (3600000 * this.$store.getters.get_user_data('time_zone'))).toLocaleString(this.$store.getters.get_user_data('locale'))
    },

    showModal() {
      this.$refs['inst-info-modal'].show()
      setTimeout(() => {
        document.querySelector('#inst-info-modal___BV_modal_outer_ #inst-info-modal').classList.toggle('light-theme', this.$store.state.light_theme);
        document.querySelector('#inst-info-modal___BV_modal_outer_ #inst-info-modal .modal-dialog .modal-content').style.opacity = '1';
      }, 150);
    },

    hideModal() {
      this.$refs['inst-info-modal'].hide()
    },


  },

  beforeMount() {
    instInfo.event.$on('show-inst-info-modal', (apiname) => {
      this.showModal();

      this.stock = this.$store.getters.get_stock(apiname)
    })
  },

  data: function() {
    return {
      stock: null,
    }
  },

  computed : {

  },

  filters : {

  },
}
</script>
