// Config
const origin = 'http://localhost:8000'
const updateInterval = 2000
const currency = 'USD'
let rates


// Libraries
const app = require('express')()
const http = require('http').Server(app)
const mysql = require('mysql2/promise')
const log4js = require("log4js")
const logger = log4js.getLogger()
const TradingViewAPI = require('tradingview-scraper')
const tv = new TradingViewAPI.TradingViewAPI()
const axios = require('axios');
const io = require('socket.io')(http, {
    cors: {

        methods: ["GET", "POST"]
    },
    pingInterval: 2000,
    pingTimeout: 60000
});

// Logger
logger.level = "debug";


// MySQL
var pool = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'trading',
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});


// HTTP Server
app.get('/', (req, res) => {
    res.send('Hello World!')
    res.code(200)
});

http.listen(3000, () => {
    // logger.info('Listening on port *: 3000');
});

// Main function
async function main() {
    // Variables
    logger.warn('Stocks: Loading')
    let data = {};
    data.stocks = [];

    // Rates Loader
    rates = await axios.get(`https://api.exchangerate.host/latest?symbol&base=${currency}`, ).then(res => res.data.rates)

    // Stocks Loader
    const result = await pool.query('SELECT * FROM `stocks`');
    for (let i = 0; i < result[0].length; i++) {
        let ticker = await tv.getTicker(result[0][i].api_name)
        ticker.name = result[0][i].name
        ticker.api_name = result[0][i].api_name
        ticker.lot = result[0][i].lot
        ticker.currency = result[0][i].currency

        data.stocks.push(ticker)
    }

    return data;
}

async function updateStocks(stocks) {
    while (true) {
        // Timer
        await new Promise(resolve => setTimeout(resolve, updateInterval));

        // Response Data
        let data = []

        // Fill the Data
        for (let i = 0; i < stocks.length; i++) {
            // Get Stock Info
            let ticker = await tv.getTicker(stocks[i].api_name);
            let price = convert(stocks[i].currency, ticker.lp)

            // Update the Database
            pool.query(`UPDATE stocks SET price = ${price} WHERE api_name = '${ticker.api_name}'`)

            // Add Data to Response
            data.push(ticker)

            // Close SL/TP Orders
            try {
                const result = await pool.query(`SELECT * FROM orders WHERE close_price IS NULL AND (${ticker.lp} <= stop_loss OR ${ticker.lp} >= take_profit)`)
                result[0].forEach(async(order) => {
                    const result = await pool.query(`SELECT token FROM users WHERE id = ${order.user_id}`)
                    let token = result[0][0].token
                    await axios.post(`${origin}/api/orders/close/${order.id}?token=${token}`)

                    logger.info('SL/TP Order Closed: ', order.id)
                })
            } catch (ex) {
                logger.fatal(ex)
            }
        }

        io.emit('stockUpdate', JSON.stringify(data))
    }
}

main().then(async(data) => {

    logger.info('Stocks: Loaded')
    logger.info(`Updating prices every: ${updateInterval}ms`)

    // Socket.IO Events
    io.on('connection', (socket) => {
        console.log('Connected: ', socket.id)
        socket.on("ping", (cb) => {
            if (typeof cb === "function")
                cb();
        });

        socket.emit('init', data.stocks)

        // Test Event
        socket.on('message', (data) => {
            console.log('Message Received: ', data)
        })

        socket.on('disconnect', () => {
            console.log('user disconnected');
        });
    });

    updateStocks(data.stocks)
})


function convert(symbol, value) {
    var converted = parseFloat(value / rates[symbol]).toFixed(5);
    return parseFloat(converted)
}