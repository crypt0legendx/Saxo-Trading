import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

const bad_stock = {
    api_name: "UNKNOWN",
    currency: "USD",
    id: -1,
    lot: 0,
    market_close: "1970-01-20 00:00:00",
    market_open: "1970-01-20 00:00:00",
    name: "UNKNOWN",
}

const ws = JSON.parse('[{"id":0,"name":"Default Workspace","layout":{"type":"container","orientation":"horizontal","first":{"size":50,"el":{"id":4,"type":"cell","color":{"component":"Favourites","additional":{"data":"Favourites"}}}},"second":{"size":50,"el":{"type":"container","orientation":"vertical","first":{"size":50,"el":{"id":1,"type":"cell","color":{"component":"Chart","additional":{"symbol":"AAPL"}}}},"second":{"size":50,"el":{"id":0,"type":"cell","color":{"component":"Chart","additional":{"symbol":"EURUSD"}}}}}}}},{"id":1,"name":"Advanced","layout":{"type":"container","orientation":"horizontal","first":{"size":50,"el":{"type":"container","orientation":"vertical","first":{"size":50,"el":{"type":"container","orientation":"horizontal","first":{"size":50,"el":{"id":7,"type":"cell","color":{"component":"ClientPositions"}}},"second":{"size":50,"el":{"id":2,"type":"cell","color":{"component":"Chart","additional":{"symbol":"EURCAD"}}}}}},"second":{"size":50,"el":{"id":6,"type":"cell","color":{"component":"Instruments"}}}}},"second":{"size":50,"el":{"type":"container","orientation":"vertical","first":{"size":50,"el":{"id":4,"type":"cell","color":{"component":"History"}}},"second":{"size":50,"el":{"id":5,"type":"cell","color":{"component":"Orders"}}}}}}},{"id":2,"name":"Chart Grid","layout":{"type":"container","orientation":"horizontal","first":{"size":50,"el":{"type":"container","orientation":"vertical","first":{"size":50,"el":{"id":8,"type":"cell","color":{"component":"Chart","additional":{"symbol":"EURUSD"}}}},"second":{"size":50,"el":{"id":10,"type":"cell","color":{"component":"Chart","additional":{"symbol":"USDJPY"}}}}}},"second":{"size":50,"el":{"type":"container","orientation":"vertical","first":{"size":50,"el":{"id":9,"type":"cell","color":{"component":"Chart","additional":{"symbol":"AAPL"}}}},"second":{"size":50,"el":{"id":11,"type":"cell","color":{"component":"Chart","additional":{"symbol":"NZDUSD"}}}}}}}}]')

const getDefaultState = () => {
    return {
        rates: [],
        light_theme: false,

        cached: [], //always clean after caching!

        user: {
            user_obj: {},

            accounts: [],

            id: "758123",
            name: "",
            email: "",
            balance: 0,
            equity: 0,
            leverage: 0,
            margin: {
                used: 0,
                free: 0,
                level: "0%"
            },

            portfolio: {
                total_trades: 0,
                profit_num: 0,
                profit_sum: 0,
                loss_num: 0,
                loss_sum: 0,
            },

            type: '',
            sub_type: '',
            sound: 'Enabled',
            locale: 'en-GB',
            symbol: "EUR",
            symbol_rate: 0,
            time_zone: "+09:00",
            orders: [],

            email_verified: false,
            kyc_user_done: null,

            vue_data: {
                current_workspace: 0,
                minimized_windows: [

                ],

                favourites: [],

                cache: {
                    //put anything in here. but never abuse it!
                },
            },

            alerts: {
                savedAlerts: [],
                savedOptions: {},
                informed: [],
            },
        },

        stocks: [

        ],

        workspace: [{
                id: 0,
                name: "Default Workspace",
                layout: null,
            },

            {
                id: 1,
                name: "Advanced",
                layout: null,
            },

            {
                id: 2,
                name: "Chart Grid",
                layout: null,
            }
        ],


        done: {
            user_interact: false,
            sload: false, // on loading/saving workspace/favourites

            connected: false, //socket connected
            connection_failed: false, //socket failed to connect
            window_resized: false, // on window resized cached event

            alertsLoaded: false,
            stocksAdded: false,
            ordersAdded: false,
            userInfoSet: {
                values_data: false,
            },
            workspace_loaded: false,
        },

        unsupported_browser: false,
        mobile_device: false,
        current_time: 0,
        current_date: '',

        latency: 0,
        application_ver: 'BETA v0.0.1',

        b_queue: [], //never access this directly
    }
}
const state = getDefaultState();

const mutations = {
    //light theme
    toggleTheme(state) { state.light_theme = !state.light_theme },

    //evbus

    broadcast(state, payload) {},
    broadcast_queue_post(state, payload) {
        let qid = state.b_queue.findIndex((ev => ev.event == payload.event))

        if (qid != -1) {
            state.b_queue[qid] = payload
            return
        }

        state.b_queue.push(payload)
    },

    broadcast_queue_clear(state, payload) {
        state.b_queue = []
    },

    //dashboard related


    //user-account relation feed
    user_add_accounts(state, payload) {
        state.user.accounts = payload;
    },

    //new workspace grid

    set_active_workspace(state, payload) { //payload = id of workspace
        state.user.vue_data.current_workspace = payload
    },

    update_whole_workspace(state, payload) {
        state.workspace = payload
    },

    update_workspace_data(state, payload) { // payload = {wid: 0, data : {}}
        for (var i = 0; i < state.workspace.length; i++) {
            if (state.workspace[i].id == payload.wid) {
                state.workspace[i].layout = payload.data
            }
        }
    },

    show_zones(state) {
        return true
    },

    hide_zones(state) {
        return false
    },

    //rate
    set_symbol_rate(state, rate) {
        state.user.symbol_rate = rate
    },

    //done events
    stocksDone(state) {
        state.done.stocksAdded = true;
    },

    ordersDone(state) {
        state.done.ordersAdded = true;
    },

    userInfoDone(state) {
        state.done.userInfoset = true;
    },


    //cache

    rm_cache(state, prop) { //deletes a cache val, prop = name of the cached shit
        delete state.user.vue_data.cache[prop];
    },


    put_cache(state, prop) { //caches some data.. prop = {prop: 'cacheName', val: 'cacheVal'}
        state.user.vue_data.cache[prop.prop] = prop.val;
    },

    // User

    set_alerts(state, prop) { //prop = {prop: 'alerts', val: []}
        state.user.alerts[prop.prop] = prop.val;
    },

    set_user_vue_data(state, prop) { //ex.prop = Object {prop: 'access_token', val: "asasd"}
        state.user.vue_data[prop.prop] = prop.val;
    },

    set_user_margin(state, prop) { //ex.prop = Object {prop: 'access_token', val: "asasd"}
        state.user.margin[prop.prop] = prop.val;
    },

    set_user_data(state, prop) { //ex. prop = Object {prop: 'balance', val: '12'}
        state.user[prop.prop] = prop.val;
    },

    //Stocks
    set_stock_price(state, stock) { // stock = Object {api_name: "EURUSD", price: {}}
        for (var i = 0; i < state.stocks.length; i++) {
            if (state.stocks[i].api_name == stock.api_name)
                state.stocks[i].price = stock.price;
        }
    },

    update_stock(state, payload) {
        for (var i = 0; i < state.stocks.length; i++) {
            if (state.stocks[i].api_name == payload.api_name)
                state.stocks[i][payload.key] = payload.value;
        }
    },

    add_stock(state, stock) { //stock = Object of stock
        state.stocks.push(stock);
    },

    //orders

    //moved to actions

    update_order(state, prop) { //prop = {id: '5', prop: 'close_price', val: 12313}
        for (var i = 0; i < state.user.orders.length; i++) {
            if (state.user.orders[i].order_id == prop.id) {
                state.user.orders[i][prop.prop] = prop.val
            }
        }
    },


    //favs

    rename_fav_cat(state, payload) { // paylaod = {cat: 'name', new_name: 'namename'}
        var at = state.user.vue_data.favourites.findIndex(e => e.name == payload.cat)
        if (at == -1) return

        state.user.vue_data.favourites[at].name = payload.new_name
    },

    rm_fav_cat(state, payload) { //payload = cat name to rm
        var at = state.user.vue_data.favourites.findIndex(e => e.name == payload)
        if (at == -1) return

        state.user.vue_data.favourites.splice(at, 1);
    },

    create_fav_cat(state, payload) { // payload = {name: 'Fav cat1', favs: []}
        var at = state.user.vue_data.favourites.findIndex(e => e.name == payload.name)
        if (at != -1) return

        if (payload.favs == undefined)
            payload.favs = []

        state.user.vue_data.favourites.push(payload)
    },

    add_fav(state, prop) { //adds a fav .. prop = symbol to a CAT
        //{cat: 'FAV1', symbol: 'EURUSD'
        //{catid: 0, symbol: EURUSD}

        var at = -1
        if (prop.catid != undefined) {
            at = prop.catid
        } else {
            at = state.user.vue_data.favourites.findIndex(e => e.name == prop.cat)
        }

        if (at == -1) return

        if (state.user.vue_data.favourites[at].favs.find(e => e == prop.symbol)) //if symb exist in cat, fuck off
            return;

        state.user.vue_data.favourites[at].favs.push(prop.symbol);
    },

    update_favs(state, favs) { //favs = collection of fav
        state.user.vue_data.favourites = favs
    },

    rm_fav(state, prop) { //rms fav from cat = {cat: 'eur1231' symbol : 'EURUSD'}
        var at = state.user.vue_data.favourites.findIndex(e => e.name == prop.cat)
        if (at == -1) return

        at = state.user.vue_data.favourites[at].favs.findIndex(e => e == prop.symbol)
        if (at == -1) return

        state.user.vue_data.favourites[at].favs.splice(at, 1)
    },
    //workspace

    set_done_status(state, prop) { // commit('set_done_status', {status: 'sload', state: true})
        state.done[prop.status] = prop.state
    },


    min_window(state, prop) { //prop = obj { loaded_component: '', id: {} }
        state.user.vue_data.minimized_windows.push(prop)
    },

    rm_from_min_window(state, prop) { //prop = obj {id : 0}
        state.user.vue_data.minimized_windows.splice(prop.id, 1)
    },

    //events
    workspace_updated: state => {},
    pricesUpdated: state => {},
    calcDone: state => {},

    //price alerts

    inform(state, payload) { //payload is the alert object itself
        state.user.alerts.informed.push(payload)
    },
}

const getters = {
    //broadcast

    //payload ev name
    //from queue
    get_broadcasted_event: (state) => (payload) => {
        let ev = state.b_queue.findIndex(ev => ev.event == payload)
        if (ev == -1)
            return null
        return state.b_queue[ev]
    },

    //favs
    get_favs_by_cat: (state) => (payload) => { //payload = cat name
        return state.user.vue_data.favourites.find(e => e.name == payload).favs;
    },


    //accounts

    get_active_account_id: (state) => {
        return state.user.id
    },

    get_active_account: (state) => {
        return state.user.accounts.find(acc => acc.id == state.user.activeAccount)
    },

    get_accounts_by_prop: (state) => (payload) => { //payload {prop,val}
        return state.user.accounts.filter(w => w[payload.prop] == payload.val)
    },

    get_workspace: (state) => {
        return state.user.workspace;
    },

    //new workspace gridData
    get_workspace_data: (state) => (payload) => { //payload = {wid: 0}
        return state.workspace.find(w => w.id == payload.wid).layout
    },

    get_active_workspace_object: (state) => {
        return state.workspace.find(w => w.id == state.user.vue_data.current_workspace)
    },

    get_active_workspace: (state) => {
        return state.user.vue_data.current_workspace;
    },

    //price alerts
    get_active_alerts: (state) => {
        return state.user.alerts.savedAlerts.filter(alert => alert.enabled == true)
    },

    is_informed: (state) => (payload) => { //payload is {symbol: "EURUSD", alertOn: "ask-bigger"}
        //returns true if element exists

        return state.user.alerts.informed.filter(alert => alert.stock == payload.symbol)
            .filter(alert => alert.alertOn == payload.alertOn)
            .length > 0 ? true : false
    },

    //rates
    get_symbol_rate: (state) => {
        return state.user.symbol_rate;
    },

    //orders
    get_orders: (state, getters) => {

        const user = getters.get_user
        let orders = state.user.orders

        orders.forEach((order) => {
            let stock = getters.get_stock(order.stock.api_name)
            let base = (order.close_price == null) ? stock.price[order.direction] : parseFloat(order.close_price)
            let open_price = parseFloat(order.price)
            let diff = (order.direction == 'buy') ? base - open_price : open_price - base
            order['pl'] = (order.volume * order.stock.lot * diff) / user.leverage
        })

        return orders
    },

    get_order: (state) => (order) => { //order = order id !!
        return state.user.orders.find(o => o.order_id == order);
    },

    //Cache
    get_cache: (state) => (prop) => {
        return state.user.vue_data.cache[prop];
    },

    //User
    get_user: (state) => {
        return state.user;
    },
    get_user_data: (state) => (prop) => {
        return state.user[prop];
    },

    get_user_vue_data: (state) => (prop) => {
        return state.user.vue_data[prop];
    },


    //Stocks

    get_stock: (state) => (stock_) => {
        var stock = JSON.parse(JSON.stringify(state.stocks.find(s => s.api_name == stock_)))

        if (!stock) return null

        stock.price.sell *= state.user.symbol_rate
        stock.price.buy *= state.user.symbol_rate
        stock.price.low *= state.user.symbol_rate
        stock.price.high *= state.user.symbol_rate

        return stock
    },

    get_stock_by_prop: (state) => (obj) => { //obj is Object {prop: 'api_name', search: 'EURGBP'}
        var stock = JSON.parse(JSON.stringify(state.stocks.find(s => s[obj.prop] == obj.search)))
        if (!stock) return null

        stock.price.sell *= state.user.symbol_rate
        stock.price.buy *= state.user.symbol_rate
        stock.price.low *= state.user.symbol_rate
        stock.price.high *= state.user.symbol_rate

        return stock
    },

    get_stocks: (state, getters) => {
        var final = []

        for (var i = 0; i < state.stocks.length; i++)
            final.push(getters.get_stock(state.stocks[i].api_name))

        return final;

    },

    //workspace

    get_minimized_windows: state => {
        return state.user.vue_data.minimized_windows
    },

    get_workspaces: state => {
        return state.workspace
    },
}

const actions = {

    // Stocks Handle
    addStocks({ state, commit }, data) {

        // Calculate the time
        const date = new Date()
        const time_now = date.getHours() * 3600 + date.getMinutes() * 60 + date.getSeconds()

        // Stocks Handle
        for (let i = 0; i < data.length; i++) {
            let stock = data[i]

            if (stock.type === 'forex')
                stock.pricescale = 10000

            let temp = {
                api_name: stock.api_name,
                name: stock.name,
                description: stock.description,
                category: stock.type,
                tradable: stock.is_tradable,
                price: {
                    sell: stock.ask,
                    buy: stock.bid,
                    scale: stock.pricescale,
                    change: stock.chp,
                    low: stock.low_price,
                    high: stock.high_price,
                },
                lot: stock.lot,
                closed: (stock.current_session != 'market') ? true : false,
                opens_in: 28800 - time_now, //seconds
            }

            commit('add_stock', temp)
        }

        commit('stocksDone')
    },

    updateStocks({ commit }, stocks) {

        // Date
        let date = new Date()
        let time_now = date.getHours() * 3600 + date.getMinutes() * 60 + date.getSeconds()

        stocks.forEach((stock) => {
            let closed = (stock.current_session != 'market') ? true : false
            if (stock.type === 'forex')
                stock.pricescale = 10000

            let price = {
                sell: stock.bid,
                buy: stock.ask,
                scale: stock.pricescale,
                change: stock.chp,
                low: stock.low_price,
                high: stock.high_price,
            }

            commit('set_stock_price', { api_name: stock.api_name, price: price })

            commit('update_stock', { api_name: stock.api_name, key: 'opens_in', value: 28800 - time_now })
            commit('update_stock', { api_name: stock.api_name, key: 'closed', value: closed })
        })
    },

    ISOOrder({ state, commit, getters }, order) { //Standartize orders structure.. if you make any changes, make them here
        return new Promise((resolve, reject) => {
            if (!order.pl)
                order.pl = 0
            let closed = null

            var t = new Date(order.created_at)

            if (order.updated_at != null && order.close_price != null) {
                let tr = new Date(order.updated_at)
                closed = new Date(tr.getTime() + tr.getTimezoneOffset() * 60000 + (3600000 * getters.get_user_data('time_zone')))
            }

            var created = new Date(t.getTime() + t.getTimezoneOffset() * 60000 + (3600000 * getters.get_user_data('time_zone')))
            let stock = getters.get_stock(order.stock.api_name)
            let decimals = (stock.category == 'forex') ? 5 : 2
            var current_price = ((stock.price[order.type])).toLocaleString('en-US', { style: 'currency', currency: getters.get_user_data('symbol') })
            var open_price = (order.open_price)
            var close_price = order.close_price == null ? null : (getters.get_symbol_rate * order.close_price).toFixed(stock.price.scale == 100 ? 2 : 4)
            var sl = (order.stop_loss == null) ? '--' : (getters.get_symbol_rate * order.open_price) + order.stop_loss / stock.price.scale
            var tp = (order.take_profit == null) ? '--' : (getters.get_symbol_rate * order.open_price) + order.take_profit / stock.price.scale
            let pips = (order.type == 'buy') ? stock.price[order.type] - open_price : open_price - stock.price[order.type]
            pips *= stock.price.scale

            var obj = {
                edit: '',
                description: stock.description,
                order_id: order.id,
                instrument: order.stock.name,
                direction: order.type == 'buy' ? 'buy' : 'sell',
                description: stock.description,
                volume: order.volume,
                price: open_price.toFixed(decimals),
                distance: parseInt(Math.abs((open_price - stock.price[order.type]) * stock.price.scale)),
                current_price: stock.price[order.type].toFixed(decimals),
                created: created,
                closed: closed,
                close_price: close_price,
                sl: order.stop_loss == null ? '--' : sl.toFixed(5),
                tp: order.take_profit == null ? '--' : tp.toFixed(5),
                type: order.trade_type,
                balance: 0,
                credit: 0,
                commission: 0, //todo
                swap: 0,
                pips: pips.toFixed(2),
                gross_profit: order.pl.toLocaleString('en-US', { minimumFractionDigits: 2, style: 'currency', currency: getters.get_user_data('symbol') }),
                net_profit: order.pl.toLocaleString('en-US', { minimumFractionDigits: 2, style: 'currency', currency: getters.get_user_data('symbol') }),
                stock: order.stock
            }

            resolve(obj)
        })
    },

    add_order({ dispatch, state, getters }, order) { //order = Order Object {}
        if (getters.get_orders.find(ord => ord.order_id == order.order_id)) {
            return
        }

        state.user.orders.push(order);
    },

    set_order({ dispatch, getters, state }, order) { //order == Obj { id: 0, order : {} }
        var idx = getters.get_orders.findIndex((ord => ord.order_id == order.id))
        if (idx == -1) return

        dispatch('ISOOrder', order.order).then((formatted) => {
            state.user.orders[idx] = formatted
        }).catch((err) => {
            console.log(err)
        })

    },

    async addOrders({ dispatch, state, commit, getters }) {
        // Fetch
        const orders = await Vue.prototype.$http.post('/api/orders').then((res) => res.data)

        if (!orders)
            return

        orders.forEach((order) => {
            dispatch('ISOOrder', order).then((formatted) => {
                dispatch('add_order', formatted)
            }).catch((er) => {
                console.log(er)
            })
        })

        commit('ordersDone')
    },

    reset_workspace({ commit }) {
        commit('update_whole_workspace', ws)
    },

    showPipPrice({ state }, payload) {
        // Convert the price to string and seperate both parts
        const array = payload.price.toString().split(".")

        // Convert number parts to array so they could join later
        let number = array[0].split("")
        let decimals = array[1].split("")

        if (payload.category == 'forex') {
            decimals.splice(2, 0, '<span>')
            decimals.splice(5, 0, '</span>')

            return number.concat('.', decimals)
        }

        decimals.unshift('<span>')
        decimals.push('</span>')

        return number.concat('.', decimals)

    },

    //user related
    calc_userdata({ commit, getters }, payload) {
        // Orders
        const user = getters.get_user
        const orders = getters.get_orders


        let pl = 0,
            margin_used = 0
        orders.forEach((order) => {
            //
            if (order.close_price != null || order.type != 'market')
                return

            pl += order.pl
            margin_used += (order.volume * order.stock.lot * parseFloat(order.price) * getters.get_symbol_rate) / user.leverage
        })

        let equity = user.balance + pl
        let margin_free = equity - margin_used


        let level = 0;

        if (margin_used != 0)
            level = (equity / margin_used) * 100

        commit('set_user_data', { prop: 'equity', val: equity })
        commit('set_user_margin', { prop: 'free', val: margin_free })
        commit('set_user_margin', { prop: 'used', val: margin_used })
        commit('set_user_margin', { prop: 'level', val: level })
        commit('set_user_data', { prop: 'pl', val: pl })

        commit('calcDone')
    },



    //orders

    async closeAll({ commit, getters }, payload) {
        const response = await Vue.prototype.$http.post(`/api/orders/close`).then(res => res.data)
        response.forEach((order) => {
            // Date
            let tr = new Date(order.updated_at)
            let closed = new Date(tr.getTime() + tr.getTimezoneOffset() * 60000 + (3600000 * getters.get_user_data('time_zone'))).toLocaleString(getters.get_user_data('locale'))

            commit('update_order', { id: order.id, prop: 'close_price', val: order.close_price })
            commit('update_order', { id: order.id, prop: 'closed', closed })
        })

        return response
    },

    async closeOrder({ commit, getters }, payload) {
        // Request
        var order = await Vue.prototype.$http.post(`/api/orders/close/${payload}`).then(res => res.data)

        // Date
        let tr = new Date(order.updated_at)
        let closed = new Date(tr.getTime() + tr.getTimezoneOffset() * 60000 + (3600000 * getters.get_user_data('time_zone'))).toLocaleString(getters.get_user_data('locale'))

        // Commits
        commit('set_user_data', { prop: 'balance', val: state.user.balance + order.pl })
        commit('update_order', { id: order.id, prop: 'close_price', val: order.close_price })
        commit('update_order', { id: order.id, prop: 'closed', closed })

        return order
    },

    async createOrder({ dispatch, commit, getters }, payload) { // payload is
        const stock = getters.get_stock(payload.stock_id)

        if (stock.closed && payload.trade_type == 'market')
            return { error: 'Instrument is disabled by the broker.' }

        if (payload.trade_type != 'market')
            payload.entryPrice /= getters.get_symbol_rate

        const order = await Vue.prototype.$http.post(`/api/orders/create`, {
            stock_id: payload.stock_id,
            volume: payload.volume,
            entryPrice: payload.entryPrice,
            type: payload.type,
            duration: payload.cancel,
            trade_type: payload.trade_type,
            sl: payload.sl,
            tp: payload.tp,
        }).then(res => res.data)

        if (order.error)
            return order

        let closed = null

        // Created
        var t = new Date(order.created_at)

        if (order.updated_at != null && order.close_price != null) {
            let tr = new Date(order.updated_at)
            closed = new Date(tr.getTime() + tr.getTimezoneOffset() * 60000 + (3600000 * getters.get_user_data('time_zone'))).toLocaleString(getters.get_user_data('locale'))
        }

        var created = new Date(t.getTime() + t.getTimezoneOffset() * 60000 + (3600000 * getters.get_user_data('time_zone'))).toLocaleString(getters.get_user_data('locale'))
        let decimals = (stock.category == 'forex') ? 5 : 2
        var current_price = ((stock.price[order.type])).toLocaleString('en-US', { style: 'currency', currency: getters.get_user_data('symbol') })
        var open_price = (getters.get_symbol_rate * order.open_price)
        var close_price = order.close_price == null ? null : (getters.get_symbol_rate * order.close_price).toLocaleString('en-US', { minimumFractionDigits: 4, style: 'currency', currency: getters.get_user_data('symbol') })
        var sl = (order.stop_loss == null) ? '--' : (getters.get_symbol_rate * order.open_price) + order.stop_loss / stock.price.scale
        var tp = (order.take_profit == null) ? '--' : (getters.get_symbol_rate * order.open_price) + order.take_profit / stock.price.scale
        let pips = (order.type == 'buy') ? stock.price[order.type] - open_price : open_price - stock.price[order.type]
        pips *= stock.price.scale

        var obj = {
            edit: '',
            description: stock.description,
            order_id: order.id,
            instrument: order.stock.name,
            direction: order.type == 'buy' ? 'buy' : 'sell',
            description: stock.description,
            volume: order.volume,
            price: open_price.toFixed(decimals),
            distance: parseInt(Math.abs((open_price - stock.price[order.type]) * stock.price.scale)),
            current_price: stock.price[order.type].toFixed(decimals),
            created: created,
            closed: closed,
            close_price: close_price,
            sl: order.stop_loss == null ? '--' : sl.toFixed(5),
            tp: order.take_profit == null ? '--' : tp.toFixed(5),
            type: order.trade_type,
            commission: 0, //todo
            swap: 0, //todo
            pips: pips,
            gross_profit: 0, //todo
            net_profit: 0,
            stock: order.stock
        }

        dispatch('add_order', obj)
        return order
    },

    editOrder({ commit, getter }, payload) {
        return Vue.prototype.$http.post(`/api/orders/edit/` + payload.order_id, payload)
    },


    //user related

    changeUserData({ dispatch, commit, getters }, payload) { //changes user data and applies it to the server
        //payload {prop: 'data', val: 'asd'}
        commit('set_user_data', payload)

        return Vue.prototype.$http.post(`/api/user/update/${payload.prop}`, { value: payload.val, gs: 1 })
    },

    //watch favourites

    addtoFavs({ dispatch, commit, getters }, payload) { //Adds an entry to the favourites list (watch)
        // payload : {cat: 'ERIAS' , symbol: 'EURUSDB'}
        // payload: {catid: 0, symbol: EURUSD}

        return new Promise((resolve, reject) => {
            if (payload.catid != undefined) {
                commit('add_fav', payload);
                resolve(1)
                return
            }

            let favs = getters.get_favs_by_cat(payload.cat);
            if (favs.find(f => f == payload.symbol)) {
                reject(-1)
                return
            }

            commit('add_fav', payload);
            //dispatch('sendFavsData'); //send changes to the server

            resolve(1)
        })
    },

    rmFromFavs({ dispatch, commit, getters }, payload) { //Rms an entry from the favourites list (watch)
        // payload : {symbol: ''}

        commit('rm_fav', payload);

        dispatch('sendFavsData'); //send changes to the server
    },

    loadFavsData({ commit, getters }, payload) {
        payload = JSON.parse(payload)

        commit('update_favs', payload)
    },

    sendFavsData({ commit, getters }, payload) { // no payload
        commit('set_done_status', { status: 'sload', state: true })
        let fs = JSON.stringify(getters.get_user_vue_data('favourites'))
        return Vue.prototype.$http.post(`/api/user/update/account/favourites`, { id: getters.get_active_account_id, value: fs })
    },

    sendWorkspace({ commit, getters }, payload) {
        let fs = JSON.stringify(getters.get_workspaces)
        return Vue.prototype.$http.post(`/api/user/update/account/workspace`, { id: getters.get_active_account_id, value: fs })
    },

    loadWorkspace({ commit, getters }, payload) {
        payload = JSON.parse(payload)

        commit('update_whole_workspace', payload)
    },

    min_window({ dispatch, commit, getters }, payload) { //MINIMIZE WINDOW ! payload == {loaded_component: {}. id: 0}
        let minimized_wnds = getters.get_minimized_windows
        return new Promise((resolve, reject) => {
            if (minimized_wnds.find(l => l.loaded_component == payload.loaded_component) != null &&
                payload.loaded_component != 'Chart') {
                if (payload.loaded_component.additional != undefined) {
                    if (minimized_wnds.find(l => l.loaded_component.additional.data == payload.loaded_component.additional.data) != null) {
                        reject('wnd_exist')
                        return
                    }
                } else {
                    reject('wnd_exist')
                    return
                }
            }

            commit('min_window', payload)

            resolve('success')
        })
    },

    manualConvert({ commit, getters }, payload) { //payload = {from: 'BGN', to: 'USD', load: 2000}
        return Vue.prototype.$http.get('https://api.exchangerate.host/latest', {
                params: {
                    'base': payload.from,
                    'symbols': payload.to
                }
            })
            .then(function(response) {
                return new Promise(resolve => {
                    resolve(response.data.rates[payload.to] * payload.load)
                });
            })
    },

    loadRates({ commit, getters }) {
        var user_symbol = getters.get_user_data('symbol');

        if (user_symbol == 'USD') {
            commit('set_symbol_rate', 1)
            return;
        }

        return Vue.prototype.$http.get('https://api.exchangerate.host/latest', {
                params: {
                    'base': 'USD',
                    'token': null
                }
            })
            .then(function(response) {
                return new Promise(resolve => {
                    commit('set_symbol_rate', response.data.rates[user_symbol])
                    resolve(getters.get_symbol_rate)
                });
            })
    },

}


export default new Vuex.Store({
    state,
    mutations,
    getters,
    actions
})