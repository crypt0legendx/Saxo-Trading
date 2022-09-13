import Vue from 'vue'
Vue.config.silent = true

import './scss/_context.scss'

import '@mdi/font/css/materialdesignicons.css'
import './scss/_font-icon.scss';
import vuetify from './plugins/vuetify';

import 'dtoaster/dist/dtoaster.css'
import DToaster from 'dtoaster'
import ToasterPresets from './json/toast_presets.json'

Vue.use(DToaster, {
  presets: ToasterPresets,
  position: 'top-right',
  containerOffset: '45px',
})


import moment from 'moment';
import VueMoment from 'vue-moment'
Vue.use(VueMoment, { moment });

import VueApexCharts from 'vue-apexcharts'
Vue.use(VueApexCharts)

Vue.component('apexchart', VueApexCharts)

import PortalVue from 'portal-vue'
Vue.use(PortalVue)


import {
  sysInfo,
  accPerfModal,
  kycModal,
  settingsModal,
  instrumentModal,
  bidAskModal,
  alertsModal,
  searchmodal,
  accSel,
  nlogs,
  editPos,
  posInfo,
  instInfo,
} from './plugins/modals.js';

Vue.use(editPos);
Vue.use(nlogs);
Vue.use(accSel);
Vue.use(searchmodal);
Vue.use(sysInfo);
Vue.use(accPerfModal);
Vue.use(kycModal);
Vue.use(settingsModal);
Vue.use(instrumentModal);
Vue.use(bidAskModal);
Vue.use(alertsModal);
Vue.use(posInfo);
Vue.use(instInfo);

import {grid} from './plugins/grid.js'
Vue.use(grid);

import {tableresizable} from './plugins/table-resizable.js'
Vue.use(tableresizable);

import axios from 'axios'
Vue.prototype.$http = axios;

import {browser} from './plugins/BrowserAgent.js'
Vue.use(browser);

import {notifAgent} from './plugins/NotificationAgent.js'
Vue.use(notifAgent);

import filters from './filters'
Vue.use(filters);

import VueEasyCm from 'vue-easycm'
Vue.use(VueEasyCm)

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import CxltToastr from 'cxlt-vue2-toastr'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'
import './scss/notif.scss'

var toastrConfigs = {
    position: 'top right',
    showMethod: 'slideInRight',
    hideMethod: 'slideOutRight',
    showDuration: 500,
    hideDuration: 500,
    timeOut: 4000,
}

Vue.use(CxltToastr, toastrConfigs)


import store from './store'

import router from './router';

import {Sound} from './sound'
Vue.use(Sound)

import vSelect from 'vue-select'

Vue.component('v-select', vSelect)

//Main pages
import App from './views/app.vue'
Vue.use(BootstrapVue)


const app = new Vue({
  vuetify,
  store,
  router,
  render: h => h(App),
});
app.$mount('#app');
