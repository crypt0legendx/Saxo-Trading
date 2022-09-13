import io from 'socket.io-client';

export default {
  name : "UserData",
  Vue : null,

  async sync(vue) {
    try {
      if(!vue) return
      this.Vue = vue;

      var bleep = true;

      var user = await vue.$http.get('/api/user').catch(err => {})

      var inDash = false

      if(user.data.accounts == undefined ||
        user.data.accounts == null ||
        user.data.accounts.length == 0 &&
        this.Vue.$router.currentRoute.name != 'Dashboard') {
          this.Vue.$router.push({ name: 'Dashboard' }).catch(err => {})
          inDash = true
          bleep = false
        }

        if(user.data.accounts.length > 0) {
          vue.$store.commit('user_add_accounts', user.data.accounts)

          if(localStorage.accSelected == undefined ||
            JSON.parse(localStorage.access_token).expiry < Date.now()) {
              if(user.data.accounts.length > 1 && !inDash) {
                //experimental
                var ss = setInterval(() => {
                  if(vue.$store.state.user.vue_data.cache.accSelLoaded) {
                    this.Vue.$accSel.show()
                    bleep = false;
                    clearInterval(ss)
                  }
                }, 100);
              }
            }
          }

          user = user.data
          var obj = JSON.parse(user.user_data)
          if(obj == null) {
            vue.$router.push({ name: 'GettingStarted' }).catch(err => {})
            bleep = false;
          } else {
            vue.$store.commit('set_user_data', {prop: 'symbol', val: user.currency})
            vue.$store.state.user.type = user.type
            vue.$store.state.user.sub_type = user.sub_type
          }

          let socket = io('http://localhost:3000', { transports : [ 'polling', 'websocket' ]})

          setInterval(() => {
            const start = Date.now();

            socket.volatile.emit("ping", () => {
              vue.$store.state.latency = Date.now() - start;
            });
          }, 5000);

          socket.on('connect_error', function(err) {
            this.$dtoast.pop({
              preset: "error",
              heading: `Connection Error`,
              content: `Couldn\'t connect to the server`,
              icon: "mdi mdi-lan-disconnect",
            })
            vue.$store.state.done.connection_failed = true
            vue.$store.state.done.connected = false
          })

          // IO Receive

          let th = this;

          socket.on('init', async function(data) {
            vue.$store.dispatch('addStocks', data)
            vue.$store.dispatch('addOrders')

            // Trader is ready to show
            vue.$store.state.done.connected = true
            vue.$store.state.done.connection_failed = false
          })


          // On update stocks

          socket.on('stockUpdate', function(data) {
            data = JSON.parse(data)
            vue.$store.dispatch('updateStocks', data)
          })

          // IO Send
          //

          // Getting User Data
          //


          if(user.kyc != null) {
            vue.$store.state.user.kyc_user_done = true
            vue.$store.state.user.kyc = JSON.parse(user.kyc)
          }
          else {
            vue.$store.state.user.kyc_user_done = false
          }

          if(user.user.email_verified_at != null) {
            vue.$store.state.user.email_verified = true
          }
          else {
            vue.$store.state.user.email_verified = false
          }


          vue.$store.commit('set_user_data', {prop: 'user_obj', val: user.user})
          vue.$store.commit('set_user_data', {prop: 'id', val: user.id}) //account id not user id. user id is in user_obj.id
          vue.$store.commit('set_user_data', {prop: 'email', val: user.email})
          vue.$store.commit('set_user_data', {prop: 'name', val: user.name})
          vue.$store.commit('set_user_data', {prop: 'leverage', val: user.leverage})
          vue.$store.commit('set_user_data', {prop: 'time_zone', val: user.time_zone})
          vue.$store.commit('set_user_data', {prop: 'portfolio', val: user.portfolio})
          vue.$store.commit('set_user_data', {prop: 'sound', val: user.sound})

          if(this.Vue.$router.currentRoute.name == 'Dashboard' || inDash)
          return;

          if(user.workspace != null) {
            vue.$store.dispatch('loadWorkspace', user.workspace)
            vue.$store.state.done.workspace_loaded = true;
          } else {
            vue.$store.dispatch('reset_workspace')
          }

          if(user.favourites != null) {
            vue.$store.dispatch('loadFavsData', user.favourites).then((res) => {
              vue.$store.state.done.sload = false
            }).catch(error => {
              vue.$store.state.done.sload = false
            })
          } else {
            // default favs
            //vue.$store.dispatch('loadFavsData', demo_data);

            vue.$store.commit('create_fav_cat', {name: 'Favourites', favs: [
              "EURUSD"
            ]})

            vue.$store.commit('create_fav_cat', {name: 'FX Majors', favs: [
              "EURUSD"
            ]})

            vue.$store.commit('create_fav_cat', {name: 'Inidices', favs: [
              "EURUSD"
            ]})

          }

          if(user.alerts != null || user.alerts_options != null) {
            if(user.alerts) {
              vue.$store.commit('set_alerts', {prop: 'savedAlerts', val: JSON.parse(user.alerts)})
            }

            if(user.alerts_options != null) {
              vue.$store.commit('set_alerts', {prop: 'savedOptions', val: JSON.parse(user.alerts_options)})
            } else {
              var demo = `[{"text":"Stop Loss","value":false},{"text":"Take Profit","value":false},{"text":"Pending Order Filled","value":false},{"text":"Stop Out","value":false}]`
              vue.$store.commit('set_alerts', {prop: 'savedOptions', val: JSON.parse(demo)})
            }

            vue.$store.state.done.alertsLoaded = true
          }

          vue.$store.dispatch('loadRates').then((res) => {
            vue.$store.commit('set_user_data', {prop: 'balance', val: parseFloat(user.balance * vue.$store.getters.get_symbol_rate) })
            th.Vue.$store.dispatch('calc_userdata');
            vue.$store.state.done.userInfoSet.values_data = true
          })

          //finished
          if(bleep)
            vue.$sound.play('bleep.mp3')
        } catch(err) {
          // fuck errors
        }
      },


      render: () => null,
    }
