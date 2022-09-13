<template>
  <div>
    <b-modal
    id='settings-modal'
    ref="settings-modal"
    hide-footer

    no-close-on-backdrop
    centered
    no-fade
    >
    <template v-slot:modal-title>Settings</template>
    <template v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
    <template v-slot:default class='pb-0 mb-0'>
      <div class='settings-dialog no-user-selection'>
        <div class='setting-component d-flex' @click='showTimeZoneModal'>
          <div class='setting-about mr-auto d-flex'>
            <v-icon class='sett-icon'>
              mdi-clock-time-four-outline
            </v-icon>
            <span class='sett-name'>Time Zone</span>
          </div>
          <div class='setting-value ml-auto'>
            {{ selectedTimeZone | upper_case }}
          </div>
          <v-icon class='sett-icon'>mdi-chevron-right</v-icon>
        </div>

        <!--
        <div class='setting-component d-flex' @click='showCurrencyModal'>
          <div class='setting-about mr-auto d-flex'>
            <v-icon class='sett-icon'>
              mdi-currency-sign
            </v-icon>
            <span class='sett-name'>Selected Currency</span>
          </div>
          <div class='setting-value ml-auto'>
            {{ $store.getters.get_user_data('symbol') }}
          </div>
          <v-icon class='sett-icon'>mdi-chevron-right</v-icon>
        </div>-->

        <div class='setting-component d-flex' @click='handleVolume'>
          <div class='setting-about mr-auto d-flex'>
            <v-icon class='sett-icon'>
              mdi-{{ $store.state.user.sound ? 'volume-high' : 'volume-mute' }}
            </v-icon>
            <span class='sett-name'>Sounds</span>
          </div>
          <div class='setting-value ml-auto'>
            {{ $store.state.user.sound }}
          </div>
        </div>

        <div class='setting-component d-flex' @click='handleTheme'>
          <div class='setting-about mr-auto d-flex'>
            <v-icon class='sett-icon'>mdi-circle-half-full</v-icon>
            <span class='sett-name'>Theme</span>
          </div>
          <div class='setting-value ml-auto'>
            {{ $store.state.light_theme ? 'Light' : 'Dark' }}
          </div>
        </div>

        <div class='setting-component d-flex' @click='handleCrashReport'>
          <div class='setting-about mr-auto d-flex'>
            <v-icon class='sett-icon'>mdi-clipboard-list-outline</v-icon>
            <span class='sett-name'>Crash Reporting</span>
          </div>
          <div class='setting-value ml-auto'>
            {{ crashReportSetting | enabled_disabled_filter }}
          </div>
        </div>

        <div class='setting-component d-flex' @click='showConfirmModal'>
          <div class='setting-about mr-auto d-flex'>
            <v-icon class='sett-icon'>mdi-reload</v-icon>
            <span class='sett-name'>Reset Settings</span>
          </div>
        </div>
      </div>
    </template>
  </b-modal>

  <b-modal
  id='time-zone-modal'
  ref='time-zone-modal'
  hide-footer
  size="sm"
  no-close-on-backdrop
  centered
  no-fade
  scrollable
  >
  <template v-slot:modal-title>Choose Timezone</template>
  <template v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
  <template v-slot:default class='pb-0 mb-0'>
    <div class='settings-dialog no-user-selection'>
      <div
      class='setting-component d-flex'
      v-for='(tz, index) in timeZoneArray'
      :key="index"
      @click="handleTimeZoneChange(`-${tz}`)"
      >
      <div class='setting-about mr-auto d-flex'>
        <span class='sett-name'>UTC -{{ tz }}</span>
      </div>
      <v-icon
      class='sett-icon sett-active pr-2'
      v-show="selectedTimeZone === `-${tz}`"
      >mdi-check</v-icon>
      <v-icon class='sett-icon'>mdi-chevron-right</v-icon>
    </div>

    <div class='setting-component d-flex' @click="handleTimeZoneChange('+0')">
      <div class='setting-about mr-auto d-flex'>
        <span class='sett-name'>UTC +0</span>
      </div>
      <v-icon
      class='sett-icon sett-active pr-2'
      v-show="selectedTimeZone === '+0'"
      >mdi-check</v-icon>
      <v-icon class='sett-icon'>mdi-chevron-right</v-icon>
    </div>

    <div
    class='setting-component d-flex'
    v-for='(tz, index) in [...timeZoneArray].reverse()'
    :key="timeZoneArray.length + index"
    @click="handleTimeZoneChange(tz)"
    >
    <div class='setting-about mr-auto d-flex'>
      <span class='sett-name'>UTC +{{ tz }}</span>
    </div>
    <v-icon
    class='sett-icon sett-active pr-2'
    v-show="selectedTimeZone === `+${tz}`"
    >mdi-check</v-icon>
    <v-icon class='sett-icon'>mdi-chevron-right</v-icon>
  </div>

  <div class='setting-component d-flex' @click="handleTimeZoneChange('+13')">
    <div class='setting-about mr-auto d-flex'>
      <span class='sett-name'>UTC +13</span>
    </div>
    <v-icon
    class='sett-icon sett-active pr-2'
    v-show="selectedTimeZone === '+13'"
    >mdi-check</v-icon>
    <v-icon class='sett-icon'>mdi-chevron-right</v-icon>
  </div>
</div>
</template>
</b-modal>

<b-modal
id='currency-modal'
ref='currency-modal'
hide-footer
size="sm"
no-close-on-backdrop
centered
no-fade
scrollable
>
<template v-slot:modal-title>Choose Currency</template>
<template v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
<template v-slot:default class='pb-0 mb-0'>
  <div class='settings-dialog no-user-selection'>
    <div
    class='setting-component d-flex'
    v-for='(currency, index) in currencyArray' :key="index"
    @click='handleCurrencyChange(currency)'
    >
    <div class='setting-about mr-auto d-flex'>
      <span class='sett-name'>{{ currency }}</span>
    </div>
    <v-icon
    class='sett-icon sett-active pr-2'
    v-show="$store.getters.get_user_data('symbol') === currency"
    >mdi-check
  </v-icon>
  <v-icon class='sett-icon'>mdi-chevron-right</v-icon>
</div>
</div>
</template>
</b-modal>

<b-modal
id='confirm-modal'
ref="confirm-modal"
hide-footer
size="sm"

no-close-on-backdrop
centered
no-fade
>
<template v-slot:modal-title>Confirmation</template>
<template v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
<template v-slot:default class='pb-0 mb-0'>
  <p>Are you sure you want to reset your settings to the default?</p>
  <div class='d-flex justify-content-between confirm-btns w-100'>
    <button
    id='btn-yes'
    class='btn w-100 mr-auto no-focus-outline'
    @click='confirmReset(true)'
    >Yes</button>
    <button
    id='btn-no'
    class='btn w-100 ml-auto no-focus-outline'
    @click='confirmReset(false)'
    >No</button>
  </div>
</template>
</b-modal>
</div>
</template>

<style lang="scss">
@import '~/variables';
@import '~/modal_css';


#confirm-modal .modal-dialog {
  .modal-content {
    min-width: 300px !important;
    max-width: 300px !important;
    width: 100% !important;
    opacity: 0;

    .modal-body {
      font-size: 13px;

      .confirm-btns {
        .btn {
          font-size: 13px;
          line-height: 1.5 !important;
          border-radius: 2px;
          margin: 0px 5px !important;
          color: #fff;
          font-weight: 500;
        }

        #btn-yes {
          background-color: #3c95d8 !important;
        }

        #btn-no {
          transition: 0s !important;
          font-weight: 500 !important;
          background-color: #353535;
        }
      }
    }
  }
}

#settings-modal .modal-dialog .modal-content {
  max-width: 580px !important;
  width: 100% !important;
  opacity: 0;

  .modal-body {
    background-color: #161616;
  }
}

#time-zone-modal .modal-dialog .modal-content:hover,
#currency-modal .modal-dialog .modal-content:hover {
  .modal-body::-webkit-scrollbar {
    opacity: 1 !important;
    width: 5px;
  }
}

#time-zone-modal .modal-dialog .modal-content,
#currency-modal .modal-dialog .modal-content {
  max-height: 400px !important;
  opacity: 0;

  .modal-body::-webkit-scrollbar {
    opacity: .4 !important;
    width: 5px;
    transition: all .1s ease-in-out;
  }

  .modal-body::-webkit-scrollbar-track {
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0);
  }

  .modal-body::-webkit-scrollbar-thumb {
    background-color: #424242;
    border-radius: 2px;
  }

  .modal-body {
    background-color: #161616;
    padding: 0px !important;

    .settings-dialog {
      padding: 0px !important;

      .setting-component {
        border-bottom: 1px solid #090909;

        .sett-active {
          color: #a7a7a7;
          font-size: 22px;
          display: flex;
        }
      }
    }
  }
}

#time-zone-modal .modal-dialog .modal-content .modal-body,
#currency-modal .modal-dialog .modal-content .modal-body,
#settings-modal .modal-dialog .modal-content .modal-body {
  .settings-dialog {
    padding: 0px 3px;

    .setting-component {
      cursor: pointer;
      padding: 15px 0px;
      border-bottom: 1px solid #090909;
      transition: 0s !important;

      .setting-about,
      .setting-value {
        padding: 0px 10px;
        font-weight: 400;
        line-height: 2;
        font-size: 13.5px;

        .sett-icon {
          line-height: 1.7;
          font-size: 17px;
        }
      }

      .setting-about .sett-icon {
        margin-right: 10px;
      }

      .setting-value {
        text-transform: capitalize;
        color: #a6a6a6cc;

        .sett-icon {
          font-size: 24px;
          margin-right: 0px !important;
        }
      }

      .sett-icon {
        color: #606060;
        line-height: 1;
        font-size: 28px;
        margin-left: 0;
        padding-left: 0 !important;
      }
    }

    .setting-component:active {
      transition: .2s !important;
      background-color: #353535;
    }
  }
}
</style>

<script>
import {settingsModal} from '../../plugins/modals.js';
import currencyListJson from '../../json/currency_list.json';

export default {
  name: 'SettingsModal',

  watch : {
    selectedTimeZone : function() {
      this.$store.dispatch('changeUserData', {prop: 'time_zone', val: this.selectedTimeZone})
    },
  },


  data() {
    return {
      selectedTimeZone: this.$store.state.user.time_zone,
      volumeSetting: false,
      crashReportSetting: false,
      timeZoneArray: [
        '12',
        '11',
        '10',
        '9',
        '8',
        '7',
        '6',
        '5',
        '4',
        '3',
        '2',
        '1',
      ],
      currencyArray: currencyListJson,
    }
  },

  watch : {
    '$store.state.user.time_zone' : function() {
      this.selectedTimeZone = 'UTC ' + this.$store.state.user.time_zone
    }
  },

  methods: {
    showSettingsModal() {
      this.$refs['settings-modal'].show();
      this.toggleTheme();
    },
    showTimeZoneModal() {
      this.$refs['time-zone-modal'].show();

      setTimeout(() => {
        document.querySelector('#time-zone-modal___BV_modal_outer_ #time-zone-modal').classList.toggle('light-theme', this.$store.state.light_theme);
        document.querySelector('#time-zone-modal___BV_modal_outer_ #time-zone-modal .modal-dialog .modal-content').style.opacity = '1';
      }, 150);
    },
    showCurrencyModal() {
      this.$refs['currency-modal'].show();
    },
    showConfirmModal() {
      this.$refs['confirm-modal'].show();

      setTimeout(() => {
        document.querySelector('#confirm-modal___BV_modal_outer_ #confirm-modal').classList.toggle('light-theme', this.$store.state.light_theme);
        document.querySelector('#confirm-modal___BV_modal_outer_ #confirm-modal .modal-dialog .modal-content').style.opacity = '1';
      }, 150);
    },
    handleTimeZoneChange(newTimeZone) {
      if(this.selectedTimeZone != newTimeZone) {
        const fixedTimeZone = newTimeZone
        //const fixedTimeZone = Math.abs(Number(newTimeZone));
        //const fixedTimeZone = `${Number(newTimeZone) < 0 ? '-' : '+'}${abs_tz <= 9 ? '0' : ''}${abs_tz}:00`
        this.selectedTimeZone = 'UTC ' + fixedTimeZone;
        this.$http.post('/api/user/update/account/time_zone', { value : fixedTimeZone}).then((res) => {
          //this.$toast.success({title: 'Success!', message: `Your timezone has been changed.`})
        }).catch(error => {
         // this.$toast.success({title: 'Error', message: `Your time zone can't be changed right now.`})
        })
      }
      this.$refs['time-zone-modal'].hide();
      //alert(this.$store.state.user.time_zone);
    },
    handleCurrencyChange(newCurrency) {
      if(this.$store.getters.get_user_data('symbol') != newCurrency) {
        this.$store.dispatch('changeUserData', {prop: 'symbol', val: newCurrency}).then((res) => {
          //this.$toast.success({title: 'Success!', message: `Your currency has been changed.`})
          location.reload()
        }).catch(error => {
          //this.$toast.success({title: 'Error', message: `Your currency can't be changed right now.`})
        })
      }
      this.$refs['currency-modal'].hide();
    },
    handleVolume() {
      if(this.$store.state.user.sound == 'Enabled')
      this.$store.state.user.sound = 'Disabled'
      else if(this.$store.state.user.sound == 'Disabled')
      this.$store.state.user.sound = 'Enabled'

      this.$http.post('/api/user/update/account/sound', { value : this.$store.state.user.sound}).then((res) => {
        //this.$toast.success({title: 'Success!', message: `Your changes were saved.`})
      }).catch(error => {
       // this.$toast.success({title: 'Error', message: `Your changes can't be saved right now.`})
      })
    },
    handleTheme() {
      this.$store.state.light_theme = !this.$store.state.light_theme;
      this.toggleTheme();
    },
    toggleTheme() {
      let app = document.querySelector('#app .v-application--wrap #app');
      app.classList.toggle('light-theme', this.$store.state.light_theme);

      setTimeout(() => {
        document.querySelector('#settings-modal___BV_modal_outer_ #settings-modal').classList.toggle('light-theme', this.$store.state.light_theme);
        document.querySelector('#settings-modal___BV_modal_outer_ #settings-modal .modal-dialog .modal-content').style.opacity = '1';
      }, 150);
    },
    handleCrashReport() {
      this.crashReportSetting = !this.crashReportSetting;
    },
    confirmReset(confirm) {
      if(confirm) {
        this.$store.state.user.sound = 'Enabled'
        this.$store.state.user.locale = 'en-GB'
        this.$store.state.time_zone = 'UTC'
        this.$store.dispatch('reset_workspace')

         this.$dtoast.pop({
                  preset: "info",
                  heading: `Settings Updated`,
                  content:  `Your settings were resetted.`,
                 })
      }

      this.$refs['confirm-modal'].hide();
    },
  },
  filters: {
    upper_case: (strVal) => strVal.toUpperCase(),
    enabled_disabled_filter: (boolValue) => {
      return boolValue ? 'enabled' : 'disabled';
    }
  },
  beforeMount() {
    settingsModal.event.$on('show-settings', () => this.showSettingsModal());
  }
}
</script>
