<template>
  <b-modal
  id='alerts-modal'
  ref="alerts-modal"
  size="lg"
  no-close-on-backdrop
  hide-footer
  centered
  no-fade
  >
  <template v-slot:modal-title>Alerts</template>
  <template v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
  <template v-slot:default class='pb-0 mb-0'>
    <b-button-group>
      <b-button
      class='no-focus-outline'
      @click='setActiveComponent(0)'
      :class='{active: activeComp === 0}'
      >Price</b-button>

      <b-button
      class='no-focus-outline'
      @click='setActiveComponent(1)'
      :class='{active: activeComp === 1}'
      >Execution</b-button>
    </b-button-group>

    <hr class='line-div'/>

    <div id='alerts-div-searchbar' class='col-12 d-flex justify-content-between' v-show="activeComp === 0">
      <b-form-input
      size="sm"
      class='cus-dark-input no-focus-outline col-7 col-lg-10 col-md-9 col-sm-9'
      style="font-size: 13px !important; border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important;"
      placeholder="Search alerts..." v-show="activeComp === 0">
    </b-form-input>
    <button
    id='alerts-div-addbtn'
    class='btn col-4 col-lg-2 col-md-3 col-sm-3 w-100 btn-success no-focus-outline'
    style='border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important'
    v-show="activeComp === 0"
    @click="addAlertComp"
    :disabled='!(currAlerts.length < 10)'
    >Add Alerts</button>
  </div>

  <hr class='line-div' v-if="activeComp === 0"/>

  <div id='alerts-div-checkbox' v-show="activeComp === 0">
    <div class="d-flex flex-row justify-content-between">
    <b-form-checkbox
    id="checkbox_enable_alerts"
    v-model="enableAll"
    checked="true"
    name="checkbox_enable_alerts"
    style="font-size: 13px;"
    @change="toggleAllAlerts"
    >Enable All
  </b-form-checkbox>

  <span style="font-size:13px">* All values are compared and displayed in your selected currency {{$store.getters.get_user_data('symbol')}}</span>
  </div>
</div>

<hr class='line-div' v-show="activeComp === 0"/>

<div id='alerts-div' class='p-0 m-0 w-100' v-show='activeComp === 0'>
  <div
  class='alert-comp d-flex'
  v-for="(el, index) in currAlerts"
  :key="index"
  :style="{
    'border-bottom-color': el.state === 1 ? '#148d2e' : 'transparent'
  }">
  <b-form-checkbox
  :id='`checkbox-alert-${index}`'
  :name='`checkbox-alert-${index}`'
  v-model="el.enabled"
  @change="enableElementAlert(index)"
  ></b-form-checkbox>

  <div class='alert-curr-dropdown'>
    <span
    @click="toggleList(index)"
    class='stock-select-btn'
    style='padding: 5px;font-size: 13px !important;'
    >{{ el.stock }}<v-icon>mdi-chevron-down</v-icon></span>

    <div v-if="el.dropdown" class="selectDropdown no-focus-outline">
      <InstrumentsSelect @stockSelected="stockSelected"/>
    </div>
  </div>

  <b-form-select
  v-model="el.alertOn"
  :options="bidAskOptions"
  size="sm"
  class='cus-dark-input no-focus-outline ml-1'
  style="padding: 3px 8px !important; font-size: 13px !important;"
  @change="alertsChanged = true"
  ></b-form-select>

  <b-form-input
  :id="`price-value-${index}`"
  type='number'
  v-model="el.priceValue"
  class='cus-dark-input no-focus-outline ml-1'
  style='max-width: 80px !important; font-size: 13px !important;'
  @keydown="alertsChanged = true"
  ></b-form-input>

  <b-form-input
  type='text'
  v-model="el.comment"
  class='price-comment cus-dark-input no-focus-outline ml-1'
  placeholder="Comment..."
  @keydown="alertsChanged = true"
  ></b-form-input>

  <v-icon
  class='email-notif no-focus-outline ml-1'
  :style="el.emailNotif | active_execution"
  @click="changeEmailNotification(index)"
  >mdi-email-outline</v-icon>

  <v-icon
  class='cus-dark-input no-focus-outline ml-1'
  style="background-color: transparent !important; padding-top: 2px; font-size: 18px;"
  @click="removeAlert(index)"
  >mdi-close</v-icon>
</div>
</div>

<div id='execution-component' class="w-100 p-0 m-0" v-show='activeComp === 1'>
  <div id='execution-options' class='absolute-wh-center'>
    <div class='execution-option d-flex'
    v-for="(opt, index) in currExeOptions"
    :key="index"
    :class="(index + 1) !== currExeOptions.length ? 'border-bottom' : ''">
    <span class='executin-option-text mr-auto'>
      {{ opt.text }}
    </span>

    <v-icon
    class='ml-auto execution-option-btn no-focus-outline'
    :style="opt.value | active_execution"
    @click="setExecutionState(index)"
    >mdi-email-outline</v-icon>
  </div>
</div>
</div>

<hr class='line-div'/>

<div class='alert-modal-footer d-flex justify-content-between'>
  <div class='pr-1 w-100'>
    <button
    class='btn w-100'
    style='background-color: #3c95d8'
    :disabled='!(alertsChanged || exeChanged)'
    @click="SaveChanges"
    >

    <span v-if="loading" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
    <div v-else>
      Apply changes
    </div>

  </button>
  </div>
  <div class='pl-1 w-100'>
    <button class='w-100 close-alerts' @click="closeModal">
      {{ alertsChanged || exeChanged ? 'Cancel' : 'Close' }}
    </button>
  </div>
</div>
</template>
</b-modal>
</template>

<style lang="scss">
@import '~/variables';
@import '~/modal_css';


@media only screen and (max-width: 768px) {
  .price-comment {
    max-width: 280px !important;
  }
}

@media only screen and (min-width: 769px) {
  .price-comment {
    max-width: 360px !important;
  }
}

@media only screen and (max-width: 425px) {
  #alerts-modal .modal-dialog .modal-content .modal-body {
    .btn-group {
      min-width: 100%;
    }
  }
}

#alerts-modal .modal-dialog .modal-content {
  max-width: 700px !important;
  width: 100% !important;
  max-height: 560px !important;
  height: 560px !important;
  opacity: 0;

  .modal-body {
    padding: 0 !important;

    .btn-group {
      z-index: 999;
    }

    .line-div {
      background-color: #353535;
      width: 100% important;
      padding: 0 !important;
      margin: 0 !important;
    }

    .btn-group,
    #alerts-div-searchbar,
    #alerts-div-checkbox,
    #alerts-div,
    #execution-component,
    .alert-modal-footer {
      padding: 10px !important;
    }

    #alerts-div-searchbar {
      padding-top: 5px !important;
      margin-top: 5px;
      .cus-dark-input {
        background-color: #353535 !important;
        border: none;
        border-radius: 0px;
        color: #a7a7a7 !important;
        max-height: 30px !important;
        height: 30px !important;
      }

      .cus-dark-input option:hover {
        background-color: #000 !important;
      }

      #alerts-div-addbtn {
        background-color: #148d2e;
        border: none;
        font-size: 12.5px;
        font-weight: 500;
        border-radius: none;
      }

      #alerts-div-addbtn:hover {
        background-color: #19a337;
      }
    }

    #alerts-div::-webkit-scrollbar {
      opacity: .4 !important;
      width: 5px;
      transition: all .1s ease-in-out;
    }

    #alerts-div::-webkit-scrollbar-track {
      box-shadow: inset 0 0 6px rgba(0, 0, 0, 0);
    }

    #alerts-div::-webkit-scrollbar-thumb {
      background-color: #424242;
      border-radius: 2px;
    }

    #alerts-div::-webkit-scrollbar {
      opacity: 1 !important;
      width: 5px;
    }

    #alerts-div {
      max-height: 324px !important;
      min-height: 324px !important;
      overflow: auto;
      padding: 5px !important;

      .alert-comp {
        min-width: 580px !important;
        border-bottom: 2px solid transparent;
        padding: 5px;

        .custom-checkbox {
          padding-top: 3.5px !important;
        }

        .alert-curr-dropdown,
        .cus-dark-input {
          max-height: 30px !important;
          height: 30px !important;
        }

        .alert-curr-dropdown {
          min-width: 80px !important;
          text-align: center !important;
          padding: 2px;
          background-color: #353535;
        }

        .price-comment {
          font-size: 13px !important;
        }

        .cus-dark-input {
          max-width: 70px;
          padding: 5px;
          background-color: #353535 !important;
          border: none;
          border-radius: 2px;
          color: #a7a7a7 !important;
        }

        .cus-dark-input option:hover {
          background-color: #000 !important;
        }
      }

      .selectDropdown {
        position:absolute;
        z-index:999;
        padding:5px;
        background: $bg;
        margin-left:-5px;
        box-shadow: rgb(0 0 0 / 45%) 1px 1px 5px 0px !important;
      }

      .email-notif {
        transition: all .1s ease-in-out;
        border: none;
        cursor: pointer;
        background-color: #353535;
        width: 50px;
        border-radius: 2px;
        font-size: 16px !important;
      }
    }

    #execution-component {
      max-height: 400px !important;
      height: 400px;

      #execution-options {
        top: 40% !important;
      }
      position: relative;
      text-align: center;
      padding: 5px;

      .execution-option {
        padding: 6px;
        line-height: 2;
        font-size: 14px;
        min-width: 260px !important;
        z-index: 9999;
        border-bottom-color: #090909 !important;

        .execution-option-text {
          font-size: 14px;
          line-height: 2.5;
        }

        .execution-option-btn {
          transition: all .1s ease-in-out;
          border: none;
          cursor: pointer;
          background-color: #353535;
          width: 50px;
          border-radius: 2px;
          font-size: 16px !important;
        }

        .execution-option-btn:hover {
          background-color: #464646;
        }
      }
    }
    .alert-modal-footer {
      div > button {
        border: none !important;
        border-radius: 2px;
        font-size: 12.5px !important;
        font-weight: 500;
        color: #fff;
        background-color: #353535;
        height: 35px;
      }
    }
  }
}
</style>

<script>
import {alertsModal} from '../../plugins/modals.js';
import InstrumentsSelect from '../InstrumentsSelect.vue';

export default {
  name: 'AlertsModal',
  components: {
    InstrumentsSelect
  },
  data() {
    return {
      loading: false,
      activeComp: 0,
      enableAll: false,
      alertsChanged: false,
      exeChanged: false,
      bidAskOptions: [
        { value: 'ask-bigger', text: 'Ask >' },
        { value: 'ask-lower', text: 'Ask <' },
        { value: 'bid-bigger', text: 'Bid >' },
        { value: 'bid-lower', text: 'Bid <' }
      ],
      currAlerts: [],
      savedAlerts:[],

      currExeOptions: [],
      savedExeOptions: [
        { text: 'Stop Loss', value: false },
        { text: 'Take Profit', value: false },
        { text: 'Pending Order Filled', value: false },
        { text: 'Stop Out', value: false }
      ]
    }
  },

  methods: {
    setActiveComponent(btnIndex) {
      this.activeComp = btnIndex;
    },
    toggleAllAlerts() {
      if(this.currAlerts.length != 0) {
        this.currAlerts.forEach(el => el.enabled = this.enableAll);
        this.alertsChanged = true;
      }
    },
    setExecutionState(optIndex) {
      const {value} = this.currExeOptions[optIndex];
      this.currExeOptions[optIndex].value = !value;

      const {
        currExeOptions: currOptions,
        savedExeOptions: savedOptions
      } = this;

      for(let i = 0; i < currOptions.length; i++) {
        if(currOptions[i].value !== savedOptions[i].value) {
          this.exeChanged = true;
          break;
        }
        else
        this.exeChanged = false;
      }
    },
    addAlertComp() {
      if(this.currAlerts.length < 10) {
        this.currAlerts = [
          {
            enabled: true,
            stock: 'EURUSD',
            dropdown: false,
            alertOn: 'bid-bigger',
            priceValue: 1.3888,
            comment:'',
            emailNotif: false,
            state: 1
          },
          ...this.currAlerts
        ];

        this.alertsChanged = true;
      }
    },
    removeAlert(index) {
      let newArray = [];

      for(let i = 0; i < this.currAlerts.length; i++) {
        if(i != index)
        newArray.push(this.currAlerts[i]);
      }

      this.alertsChanged = true;
      this.currAlerts = newArray;
    },
    enableElementAlert(index) {
      //this.currAlerts[index].enabled = !this.currAlerts[index].enabled;
      this.alertsChanged = true;
    },
    changeEmailNotification(index) {
      this.currAlerts[index].emailNotif = !this.currAlerts[index].emailNotif;
      this.alertsChanged = true;
    },
    stockSelected(stock) {
      this.currAlerts.forEach((el) => {
        if(el.dropdown === true) {
          el.stock = stock.api_name;
          el.dropdown = false;
          this.alertsChanged = true;
        }
      });
    },
    toggleList(elIndex) {
      let prevVal = !this.currAlerts[elIndex].dropdown;
      this.closeAllLists();
      this.currAlerts[elIndex].dropdown = prevVal;
    },
    closeAllLists() {
      this.currAlerts.forEach(el => el.dropdown = false);
    },
    SaveChanges() {
      this.loading = true

      this.currAlerts.forEach(el => el.state = 0);
      this.$store.state.user.alerts.savedOptions = JSON.parse(JSON.stringify(this.currExeOptions));
      this.$store.state.user.alerts.savedAlerts = JSON.parse(JSON.stringify(this.currAlerts));

      var th = this
      this.$http.post(`/api/user/update/account/alerts`, { value : this.$store.state.user.alerts.savedAlerts }).then((res) => {
        this.$http.post(`/api/user/update/account/alerts_options`, { value : this.$store.state.user.alerts.savedOptions }).then((res) => {
          this.$dtoast.pop({
              preset: "info",
              heading: "Alerts Updated",
              content: `Your alerts had been updated`,
            })
          this.loading = false;
          th.alertsChanged = false;
          th.exeChanged = false;
        }).catch(error => {
          this.$dtoast.pop({
            preset: "error",
            heading: "Unexpected Error",
            content: `Your alerts can't be updated right now.`,
          })
          this.loading = false
        })
      }).catch(error => {
         this.$dtoast.pop({
            preset: "error",
            heading: "Unexpected Error",
            content: `Your alerts can't be updated right now.`,
          })
        this.loading = false
      })

    },
    showModal() {
      var that = this
      that.currAlerts = that.$store.state.user.alerts.savedAlerts
      that.currExeOptions = that.$store.state.user.alerts.savedOptions

      this.$store.subscribe((mutation, state) => {
        if(mutation.type === 'set_alerts') {
          that.currAlerts = that.$store.state.user.alerts.savedAlerts
          that.currExeOptions = that.$store.state.user.alerts.savedOptions
        }
      })

      this.enableAll = false;
      this.exeChanged = false;
      this.alertsChanged = false;
      this.closeAllLists();
      this.$refs['alerts-modal'].show();
      setTimeout(() => {
        document.querySelector('#alerts-modal___BV_modal_outer_ #alerts-modal').classList.toggle('light-theme', this.$store.state.light_theme);
        document.querySelector('#alerts-modal___BV_modal_outer_ #alerts-modal .modal-dialog .modal-content').style.opacity = '1';
      }, 150);
    },

    closeModal() {
      this.$refs['alerts-modal'].hide();
    }
  },
  filters: {
    active_execution(val) {
      return {
        background: val ? '#1eb940 !important' : '#353535',
        color: val ? '#fff !important' : '#a7a7a7'
      }
    }
  },
  beforeMount() {
    alertsModal.event.$on('show-alerts-modal', () => this.showModal())
  }
}
</script>
