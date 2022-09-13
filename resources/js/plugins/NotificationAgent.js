export const notifAgent = {
  install(Vue, options) {
    this.event = new Vue();

    Vue.prototype.$notifAgent = {
      push(notif, type) {
        Vue.prototype.$http.post('/api/user/notifications/store', {
          notification: notif,
          type: type
        }).then((data) => {
          return;
        });
      },

      clear() {
        Vue.prototype.$http.post('/api/user/notifications/clear').then((data) => {
          return;
        });
      }
    }
  }
}
