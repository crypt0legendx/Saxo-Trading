import AccountPerformanceModal from '../components/Modals/AccountPerformanceModal.vue';
import KYCModal from '../components/Modals/KYCModal.vue';
import SettingsModal from '../components/Modals/SettingsModal.vue';
import SelectInstrumentModal from '../components/Modals/SelectInstrumentModal.vue';
import BidAskModal from '../components/Modals/BidAskModal.vue';
import AlertsModal from '../components/Modals/AlertsModal.vue';
import SystemInformation from '../components/Modals/SystemInfo.vue';
import SearchModal from '../components/Modals/Search.vue';
import AccountSelection from '../components/Modals/AccountSelection.vue';
import NotifLogs from '../components/Modals/NotifLogs.vue';
import EditPositionModal from '../components/Modals/EditPosition.vue'
import PosInfoModal from '../components/Modals/PositionInfo.vue';
import InstInfoModal from '../components/Modals/InstrumentInfo.vue';


export const instInfo = {
    install(Vue, options) {
        this.event = new Vue();
        Vue.component('inst-info-modal', InstInfoModal);
        Vue.prototype.$instInfo = {
            show(apiname) {
                instInfo.event.$emit('show-inst-info-modal', apiname);
            }
        }
    }
}

export const posInfo = {
    install(Vue, options) {
        this.event = new Vue();
        Vue.component('pos-info-modal', PosInfoModal);
        Vue.prototype.$posInfo = {
            show(pid) {
                posInfo.event.$emit('show-pos-info-modal', pid);
            }
        }
    }
}

export const editPos = {
  install(Vue, options) {
      this.event = new Vue();
      Vue.component('edit-position-modal', EditPositionModal);
      Vue.prototype.$editPos = {
          show(order) {
              editPos.event.$emit('show-edit-position-modal', order);
          }
      }
  }
}

export const nlogs = {
  install(Vue, options) {
      this.event = new Vue();
      Vue.component('nlogs-modal', NotifLogs);
      Vue.prototype.$nlogs = {
          show() {
              nlogs.event.$emit('show-nlogs-modal');
          }
      }
  }
}

export const accSel = {
  install(Vue, options) {
      this.event = new Vue();
      Vue.component('account-selection-modal', AccountSelection);
      Vue.prototype.$accSel = {
          show() {
              accSel.event.$emit('show-account-selection-modal');
          }
      }
  }
}

export const accPerfModal = {
    install(Vue, options) {
        this.event = new Vue();
        Vue.component('acc-perf-modal', AccountPerformanceModal);
        Vue.prototype.$accModal = {
            show() {
                accPerfModal.event.$emit('show-acc-perf-modal');
            }
        }
    }
}

export const kycModal = {
    install(Vue, options) {
        this.event = new Vue();
        Vue.component('kyc-modal', KYCModal);
        Vue.prototype.$kycModal = {
            show() {
                kycModal.event.$emit('show-kyc-modal');
            }
        }
    }
}

export const settingsModal = {
    install(Vue, options) {
        this.event = new Vue();
        Vue.component('settings-modal', SettingsModal);
        Vue.prototype.$settModal = {
            show() {
                settingsModal.event.$emit('show-settings');
            }
        }
    }
}

export const instrumentModal = {
    install(Vue, options) {
        this.event = new Vue();
        Vue.component('select-instrument-modal', SelectInstrumentModal);
        Vue.prototype.$instModal = {
            show(from) {
                instrumentModal.event.$emit('show-select-instrument-modal', from);
            }
        }
    }
}

export const searchmodal = {
    install(Vue, options) {
        this.event = new Vue();
        Vue.component('search-modal', SearchModal);
        Vue.prototype.$searchmodal = {
            show() {
                searchmodal.event.$emit('show-search-modal');
            }
        }
    }
}

export const sysInfo = {
    install(Vue, options) {
        this.event = new Vue();
        Vue.component('system-info-modal', SystemInformation);
        Vue.prototype.$sysInfo = {
            show() {
                sysInfo.event.$emit('show-system-info-modal');
            }
        }
    }
}

export const bidAskModal = {
    install(Vue, options) {
        this.event = new Vue();
        Vue.component('bid-ask-modal', BidAskModal);
        Vue.prototype.$bidModal = {
            show(data, active_nav, direction) {
                bidAskModal.event.$emit('show-bid-ask-modal', {
                  data: data,
                  active_nav: active_nav,
                  direction: direction,
                });
            }
        }
    }
}

export const alertsModal = {
    install(Vue, options) {
        this.event = new Vue();
        Vue.component('alerts-modal', AlertsModal);
        Vue.prototype.$alerts_Modal = {
            show() {
                alertsModal.event.$emit('show-alerts-modal');
            }
        }
    }
}
