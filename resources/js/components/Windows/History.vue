<template>
  <div class="h-100 pa-0">
     <!-- SENDING TOPBAR !-->
     <portal :to="windID">
      <i class="font-icon icon_history"></i>
      <span class="topbar-heading">History</span>

      <div class="topbar-component select">
        <select v-model="direction">
          <option value="all" selected>All Transactions</option>
          <option value="buy">Buy Only</option>
          <option value="sell">Sell Only</option>
          <option value="deposit">Deposit And Withdrawal</option>
        </select>
      </div>

      <div class="topbar-component select">
        <select v-model="period_select" @change="periodChanged">
          <option value="today" selected>Today</option>
          <option value="current_week">Current Week</option>
          <option value="current_month">Current Month</option>
          <option value="current_year">Current Year</option>
          <option value="all_time">All Time</option>
          <option value="custom">Custom Dates</option>
        </select>
      </div>

      <div class="topbar-component custom">
        <DDatePick
          :dark="!$store.state.light_theme"
          :disabled="!custom_dates_active"
          v-model="period.startDate"
        />
      </div>

      <div class="topbar-component custom">
        <DDatePick
          :dark="!$store.state.light_theme"
          :disabled="!custom_dates_active"
          v-model="period.endDate"
        />
      </div>

      <div class="topbar-component btn-prim">
         <button @click="export_">Statement</button>
      </div>

      <div class="topbar-component custom">
        <DSearch
          v-model="search"
        ></DSearch>
      </div>

    </portal>
    <!-- END OF TOPBAR !-->

    <v-data-table ref="historyTable" :headers="headers" :items="filterHistory" :items-per-page="999999999999" class="datatable dark h-100" :search="search" :mobile-breakpoint="0">
      <template v-slot:item.direction='{item}'>
        <span :style="{color: item.direction == 'buy' ? '#19a337' : '#d81720'}">
          {{ item.direction == 'buy' ? 'Buy' : 'Sell' }}
        </span>
      </template>

      <template v-slot:item.created="{ item }">
        {{ item.created.toLocaleString() }}
      </template>

      <template v-slot:item.closed="{ item }">
        {{ item.closed.toLocaleString() }}
      </template>

      <template v-slot:item.pips="{ item }">
        <span v-if="item.pips < 0" style="color: red">
          {{ item.pips }}
        </span>
        <span v-else style="color: green">
          +{{ item.pips }}
        </span>
      </template>

    </v-data-table>
</div>
</template>


<style lang="scss">
@import '~/variables.scss';
@import '../../scss/datatables.scss';

</style>

<script>
import DDatePick from '../DDatePick.vue'
import DSearch from '../DSearch.vue'
const format = "YYYY-MM-DD HH:mm[Z]"

export default {
  name : "History",
  components: {DDatePick, DSearch},

  data () {
    return {
      search: '',
      period_select: 'today',
      direction: 'all',
      period: {
        startDate: null,
        endDate:null,
      },

      custom_dates_active: false,


      headers: [
        { text: 'PID', value: 'order_id', align: 'd-none', width: '3%'},
        { text: `Create Time (UTC ${this.$store.getters.get_user_data('time_zone')})`, value: 'created', align: 'center', width: '7%' },
        { text: 'Transaction', value: 'direction', align: 'center', width: '5%' },
        { text: 'Quantity', value: 'volume', width: '5%' },
        { text: 'Instrument', value: 'instrument', width: '5%' },
        { text: 'Open Price', value: 'price', width: '5%' },
        { text: 'Stop Loss', value: 'sl', width: '5%' },
        { text: 'Take Profit', value: 'tp', width: '5%' },
        { text: 'Close Price', value: 'close_price', width: '5%' },
        { text: 'Pips', value: 'pips', width: '3%' },
        { text: 'Swap', value: 'swap', width: '3%' },
        { text: 'Net Amount', value: 'net_profit', width: '5%' },
        { text: `Close Time (UTC ${this.$store.getters.get_user_data('time_zone')})`, value: 'closed', width: '5%' },
        { text: 'Gross Amount', value: 'gross_profit', width: '4%' },
        { text: `Balance`, value: 'balance', width: '5%' },
        { text: `Credit`, value: 'credit', width: '5%' },
        { text: `Commissions`, value: 'commission', width: '5%' },
      ],
    }
  },

  props : {
    windID : Number,
  },

  methods: {
    periodChanged(e) {
      e = e.target.value
      let sDate, eDate;
      switch(e) {
        case "today":
          var date = this.$moment()
          sDate = date.subtract(1, 'days')
          eDate = this.$moment();
          break;

        case "current_week":
          var date = this.$moment()
          sDate = date.subtract(7, 'days')
          eDate = this.$moment();
          break;

        case "current_month":
          var date = this.$moment()
          sDate = date.subtract(1, 'months')
          eDate = this.$moment();
          break;

        case "current_year":
          var date = this.$moment()
          sDate = date.subtract(1, 'years')
          eDate = this.$moment();
          break;

        case "all_time":
          var date = this.$moment(0)
          sDate = date
          eDate = this.$moment();
          break;

        case "custom":
          this.custom_dates_active = true
          break;
      }

      if(e != 'custom') this.custom_dates_active = false

      this.period.startDate = sDate.format(format)
      this.period.endDate = eDate.format(format)
    },
    formatDate(date) {
        date = new Date(date.getTime() + date.getTimezoneOffset() * 60000 + (3600000 * this.$store.getters.get_user_data('time_zone')))
        return date.getFullYear() + '-' +
                 date.getMonth().toLocaleString('en-US', {minimumIntegerDigits: 2}) + '-' +
                 date.getDate().toLocaleString('en-US', {minimumIntegerDigits: 2}) + ' ' +
                 date.getHours().toLocaleString('en-US', {minimumIntegerDigits: 2}) + ':' +
                 date.getMinutes().toLocaleString('en-US', {minimumIntegerDigits: 2})
    },

    export_() {
      this.$http.get('/api/user/history').then((data) => {
        data = data.data

        var file = new Blob([data], {type: 'csv'});

        if (window.navigator.msSaveOrOpenBlob) // IE10+
          window.navigator.msSaveOrOpenBlob(file, 'history.csv');
          else { // Chrome, FF, etc
         var a = document.createElement("a"),
                 url = URL.createObjectURL(file);
         a.href = url;
         a.download = 'history.csv';
         document.body.appendChild(a);
         a.click();
         setTimeout(function() {
             document.body.removeChild(a);
             window.URL.revokeObjectURL(url);
         }, 0);
     }

      })
    },
  },

  mounted() {
    this.$resizable_table.init(this.$refs.historyTable.$el)
  },

  computed: {
    items() {
      return this.$store.getters.get_orders
    },
    filterHistory() {
        return this.items.filter(pos => {
            if(pos.close_price == null) return false
            return pos.direction.toLowerCase().includes(this.direction.toLowerCase()) || this.direction == 'all'
        }).filter(pos => {
          var date = this.$moment(pos.created)
          return date.isBetween(this.period.startDate, this.period.endDate) || date.isSame(this.period.startDate) || date.isSame(this.period.endDate)
        })
    },
  },

  created() {
    this.periodChanged({target: {value: 'today'}})
  },
}
</script>
