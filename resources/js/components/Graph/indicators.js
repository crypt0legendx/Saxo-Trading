export default {
    addEMA(self, color, length) {
        self.chart.add("onchart", {
            type: "EMA",
            name: "EMA, " + length,
            data: [],
            settings: {
                lineColor: color,
                length: length,
                lineWidth: 1.5,
            },
        });
    },
    addSMA(self, color, length) {
        self.chart.add("onchart", {
            type: "SMA",
            name: "SMA, " + length,
            data: [],
            settings: {
                lineColor: color,
                length: length,
                lineWidth: 1.5,
            },
        });
    },
    addTMA(self, color, length) {
        self.chart.add("onchart", {
            type: "TMA",
            name: "TMA, " + length,
            data: [],
            settings: {
                lineColor: color,
                length: length,
                lineWidth: 1.5,
            },
        });
    },
    addWMA(self, color, length) {
        self.chart.add("onchart", {
            type: "WMA",
            name: "WMA, " + length,
            data: [],
            settings: {
                lineColor: color,
                length: length,
                lineWidth: 1.5,
            },
        });
    },
    addLSMA(self, color, length) {
        self.chart.add("onchart", {
            type: "LSMA",
            name: "LSMA, " + length,
            data: [],
            settings: {
                lineColor: color,
                length: length,
                lineWidth: 1.5,
            },
        });
    },
    addWWS(self, color, length) {
        self.chart.add("onchart", {
            type: "WWS",
            name: "WWS, " + length,
            data: [],
            settings: {
                lineColor: color,
                length: length,
                lineWidth: 1.5,
            },
        });
    },
    addWC(self, color, length) {
        self.chart.add("onchart", {
            type: "WC",
            name: "WC, " + length,
            data: [],
            settings: {
                lineColor: color,
                length: length,
                lineWidth: 1.5,
            },
        });
    },
    addROC(self, color, length) {
        self.chart.add("offchart", {
            type: "ROC",
            name: "ROC, " + length,
            data: [],
            settings: {
                lineColor: color,
                length: length,
                lineWidth: 1.5,
            },
        });
    },
}