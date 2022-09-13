import io from 'socket.io-client';

export default {
  name : "Events",
  Vue : null,

  user_data() {

    var sessionExpire = setInterval(() => {
      var exp = JSON.parse(localStorage.access_token).expiry
      if(Date.now() >= exp) {
        localStorage.clear();
        this.Vue.$router.push({ name: 'Login' }).catch(err => {})
      }
    }, 850);


    let that = this

  this.Vue.$store.watch(
    (state)=>{
      return this.Vue.$store.state.user.vue_data.favourites
    },
    (val)=>{
      this.Vue.$store.dispatch('sendFavsData');
    },
    {
      deep:true
    }
    );


    this.Vue.$store.subscribe((mutation, state) => {
      //ltheme
      if(mutation.type === 'toggleTheme') localStorage.light_theme = this.Vue.$store.state.light_theme

      //workspace
      if(mutation.type == 'update_whole_workspace'
          || mutation.type == 'update_workspace_data'
          || mutation.type == 'workspace_updated')
            that.Vue.$store.dispatch('sendWorkspace')

      //stocks
      if(mutation.type === 'add_stock' || mutation.type === 'update_stock' || mutation.type === 'set_stock_price')
      {
        that.Vue.$store.dispatch('calc_userdata');

        that.Vue.$store.commit('pricesUpdated')
      }
    })

    this.Vue.$store.subscribeAction((action, state) => {
      if (action.type == 'closeOrder' || action.type == 'closeAll' || action.type === 'add_order' || action.type === 'set_order') {
        that.Vue.$store.dispatch('calc_userdata');
        that.Vue.$store.commit('pricesUpdated')
      }
    });
  },

  stock_alerts() {
    var that = this
    this.Vue.$store.subscribe((mutation, state) => { //fires when stock is updated
      if(mutation.type === 'update_stock' || mutation.type === 'set_stock_price')
      {
        var active_alerts = JSON.parse(JSON.stringify(that.Vue.$store.getters.get_active_alerts));
        active_alerts.forEach((alert) => {
          var stock  = JSON.parse(JSON.stringify(that.Vue.$store.getters.get_stock(alert.stock)))
          let when = alert.alertOn


          if(alert.priceValue == 0)
            return

          if(!stock || stock.price.bid == 0)
            return

          let symb = that.Vue.$store.getters.get_user_data('symbol')

          var inform = false;
          var inform_text = '';

          if(when == "bid-bigger") {
            if(stock.price.sell >= alert.priceValue) {
              inform = true;
              inform_text = `${stock.name} BID ≥ ${alert.priceValue.toLocaleString('en-US', { style: 'currency', currency: symb })}. Actual BID: ${stock.price.sell.toLocaleString('en-US', { style: 'currency', currency: symb })}`
            }
          } else if(when == "bid-lower") {
            if(stock.price.sell <= alert.priceValue) {
              inform = true;
              inform_text = `${stock.name} BID ≤ ${alert.priceValue.toLocaleString('en-US', { style: 'currency', currency: symb })}. Actual BID: ${stock.price.sell.toLocaleString('en-US', { style: 'currency', currency: symb })}`
            }

          } else if(when == "ask-bigger") {
            if(stock.price.buy >= alert.priceValue) {
              inform = true;
              inform_text = `${stock.name} ASK ≤ ${alert.priceValue.toLocaleString('en-US', { style: 'currency', currency: symb })}. Actual ASK: ${stock.price.buy.toLocaleString('en-US', { style: 'currency', currency: symb })}`
            }

          } else if(when == "ask-lower") {
            if(stock.price.buy <= alert.priceValue) {
              inform = true;
              inform_text = `${stock.name} ASK ≥ ${alert.priceValue.toLocaleString('en-US', { style: 'currency', currency: symb })}. Actual ASK: ${stock.price.buy.toLocaleString('en-US', { style: 'currency', currency: symb })}`
            }
          }

          if(inform && !that.Vue.$store.getters.is_informed({symbol: alert.stock, alertOn: alert.alertOn})) {
            this.Vue.$dtoast.pop({
              preset: "info",
              heading: `Price Alert of ${stock.name}`,
              content:  inform_text
            })
            that.Vue.$notifAgent.push(inform_text, 'alerts');

            if(that.Vue.$store.state.done.user_interact)
              that.Vue.$sound.play('alert.mp3')

            that.Vue.$store.commit('inform', alert)
          }


        })
      }
    })
  },


  async start(vue) {
    if(!vue) return
    this.Vue = vue;

    this.user_data()
    this.stock_alerts()
  },

  render: () => null,
}
