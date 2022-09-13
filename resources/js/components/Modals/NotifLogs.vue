<template>
  <b-modal
  id='nlogs-modal'
  ref="nlogs-modal"
  class="nlogs-modal"
  hide-footer
  no-close-on-backdrop
  centered
  v-center
  size="lg"
  no-fade
  >
  <template v-slot:modal-title><v-icon>mdi-bell-alert-outline</v-icon> Notifications Log</template>
  <template v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
  <template v-slot:default>
    <v-container class="sel-ins-cnt">
      <div class="search">
        <input v-model='stocks_search' placeholder="Search for notifications..."/>
      </div>

      <b-button-group class='button-group-el mb-2' style='width: 100% !important;'>
        <b-button
        class='no-focus-outline'
        @click="onSectionChange(0)"
        :class='{active: activeBtn === 0}'>General
      </b-button>

      <b-button
      class='no-focus-outline'
      @click="onSectionChange(1)"
      :class='{active: activeBtn === 1}'>Positions
    </b-button>

    <b-button
    class='no-focus-outline'
    @click="onSectionChange(2)"
    :class='{active: activeBtn === 2}'>Alerts
  </b-button>
</b-button-group>

<template>
  <div class="stocks-list">

    <v-data-table
    v-columns-resizable
    :headers="general_headers"
    :items="items"
    :items-per-page="999999999"
    class="datatable dark"
    :mobile-breakpoint="0"
    :search="stocks_search"
    >
    <template slot="no-data">
  <div>No notifications to show</div>
  </template>
  </v-data-table>
</div>
</template>

  <div class='mt-3 -100 h-100 d-flex justify-content-end align-center'>
    <b-button
    id='clear-logs'
    class='no-focus-outline'
    style="font-size:13px; height:29px; border-radius: 2px !important; transition: none !important;"
    @click="clearLog"
    >Clear Logs
    </b-button>
  </div>


</v-container>
</template>
</b-modal>
</template>

<style lang='scss'>
@import '~/variables.scss';
@import '~/modal_css';
@import '~/datatables.scss';

#nlogs-modal .modal-dialog .modal-content {
  opacity: 0;
}

div.modal.nlogs-modal .modal-dialog {
    min-height:500px !important;
}

.stocks-list {
  overflow-y: auto;
  max-height:450px;
  font-size:12px;

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

  .datatable tbody td {
    font-size: 13px !important;
  }

  .stock {
    cursor:pointer;
    margin-top:2px;
    font-family: 'Roboto';
    padding:8px;
    display:flex;
    flex-direction:row;
    justify-content:space-between;
    align-items:center;

    color:$select_f;
    background: $input_search_stocks_bg;

    .stock_name {
      font-size:15px;
    }

    &:hover {
      background: $stock_hover;
    }

    .category {
      display:flex;
      align-items:center;
      font-size:14px;

      i {
        font-size:20px;
      }
    }
  }
}


.sel-ins-cnt {
  padding-left:0 !important;
  padding-right:0 !important;
}

.search {
  height:40px;
  width:100%;

  input {
    width:100%;
    background: $select_bg;
    color: $select_f;
    outline: none;
    border: none;
    padding: 5px 10px;
    font-size: 14px;
    height:35px;
  }
}
</style>

<script>
import {nlogs} from '../../plugins/modals.js';

export default {
  name: 'NotifLogs',
  methods: {
    clearLog() {
        this.$notifAgent.clear();

        this.$dtoast.pop({
            preset: "info",
            heading: "Notification Logs Cleared",
            content: `Your notification logs were clearead.`,
          })
          this.$sound.play('alert.mp3')

        this.hideModal();
    },


    onSectionChange(val) {
      this.activeBtn = val
      this.items = this.back_items;

      this.items = this.items.filter(pos => {
          if(val == 0) {
            return pos.type != 'POSITIONS' && pos.type != 'ALERTS';
          } else if(val == 1) {
            return pos.type == 'POSITIONS';
          } else if(val == 2) {
            return pos.type == "ALERTS";
          }
      })

    },

    showModal() {
      let th = this
      this.$http.get('/api/user/notifications').then((data) => {
        th.items = [];

        data = data.data
        data.forEach((notif) => {
          var date = new Date(notif.created_at);

          th.items.push({
            notif: notif.notification,
            type: notif.type.toUpperCase(),
            date: date.toLocaleString(),
          })
        })

        th.back_items = th.items;

        th.onSectionChange(0);
      })

      this.$refs['nlogs-modal'].show()
      setTimeout(() => {
        document.querySelector('#nlogs-modal___BV_modal_outer_ #nlogs-modal').classList.toggle('light-theme', this.$store.state.light_theme);
        document.querySelector('#nlogs-modal___BV_modal_outer_ #nlogs-modal .modal-dialog .modal-content').style.opacity = '1';
      }, 150);
    },

    hideModal() {
      this.$refs['nlogs-modal'].hide()
    },

  },


  beforeMount() {
    nlogs.event.$on('show-nlogs-modal', () => {
      this.showModal();
    })
  },

  data: function() {
    return {
      stocks_search: '',
      activeBtn: 0,

      general_headers: [
        { text: 'Notification', value: 'notif', align: 'left', width: "35%"},
        { text: 'Type', value: 'type' , align: 'center', width: "35%"},
        { text: 'Date', value: 'date' , align: 'center', width: "35%"},
      ],


      items: [
      ],

      back_items: [],
    }
  },

  created() {

  },

  computed: {
  },

}
</script>
