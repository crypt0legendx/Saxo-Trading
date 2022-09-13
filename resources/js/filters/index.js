import currency_locale from './filters/currency_locale.js';
import nosingledigit from './filters/no_single_digit.js';
import loaduserdata from './filters/loaduserdata.js';

export default {
     install(Vue) {
         Vue.filter('currency_locale', currency_locale);
         Vue.filter('nosingledigit', nosingledigit);
          Vue.filter('loaduserdata', loaduserdata);
     }
}
