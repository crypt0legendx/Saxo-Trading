<script>
// Renedrer for candlesticks + volume (optional)
// It can be used as the main chart or an indicator

import { Overlay } from "trading-vue-js";
import { layout_cnv } from "trading-vue-js";
import { primitives } from "trading-vue-js";
import Candle from "../primitives/candle";
import Price from "../primitives/price";
import Volbar from "../primitives/volbar";
import HeikinAshi from "heikinashi";
import math from "../primitives/math";

const candle = function candle(self, mid, p, $p) {
    return {
        x: mid,
        w: self.px_step * $p.config.CANDLEW,
        o: Math.floor(math.log(p[1]) * self.A + self.B),
        h: Math.floor(math.log(p[2]) * self.A + self.B),
        l: Math.floor(math.log(p[3]) * self.A + self.B),
        c: Math.floor(math.log(p[4]) * self.A + self.B),
        raw: p,
    };
};

export default {
    name: "Heikin",
    mixins: [Overlay],
    methods: {
        meta_info() {
            return { author: "C451", version: "1.2.1" };
        },
        init() {
            this.price = new Price(this);
        },
        draw(ctx) {
            // If data === main candlestick data
            // render as main chart:
            if (this.$props.sub === this.$props.data) {
                var cnv = {
                    candles: this.$props.layout.candles,
                    volume: this.$props.layout.volume,
                };
                // Else, as offchart / onchart indicator:
            } else {
                cnv = layout_cnv(this);
            }

            var cc = cnv.candles;
            let style = cc[0].raw[6];
            const old = cc[0];
            let constructor = cc[0].constructor;

            cc = cc.map((value) => {
                return {
                    time: value.raw[0],
                    close: value.raw[4],
                    high: value.raw[2],
                    low: value.raw[3],
                    open: value.raw[1],
                    volume: value.raw[5],
                };
            });
            cc = HeikinAshi(cc);
            cc = cc.map((value, i) => {
                let p = this.sub[i];
                let mid = this.layout.t2screen(p[0]) + 0.5;
                var res = candle(this, mid, p, this.$props);
                res = {
                    x: mid,
                    w: this.layout.px_step * this.$props.config.CANDLEW,
                    o: Math.floor(value.open * this.layout.A + this.layout.B),
                    h: Math.floor(value.high * this.layout.A + this.layout.B),
                    l: Math.floor(value.low * this.layout.A + this.layout.B),
                    c: Math.floor(value.close * this.layout.A + this.layout.B),
                    raw: p,
                };
                return res;
            });
            new Candle(this, ctx, cc[0]);
            for (var i = 1, n = cc.length; i < n; i++) {
                var curr = cc[i];
                var prev = cc[i - 1];
                new Candle(this, ctx, curr);
            }

            if (this.price_line) this.price.draw(ctx);
        },
        use_for() {
            return ["Heikin"];
        },

        // In case it's added as offchart overlay
        y_range() {
            var hi = -Infinity,
                lo = Infinity;
            for (var i = 0, n = this.sub.length; i < n; i++) {
                let x = this.sub[i];
                if (x[2] > hi) hi = x[2];
                if (x[3] < lo) lo = x[3];
            }
            return [hi, lo];
        },
    },

    // Define internal setting & constants here
    computed: {
        sett() {
            return this.$props.settings;
        },
        show_volume() {
            return "showVolume" in this.sett ? this.sett.showVolume : true;
        },
        price_line() {
            return "priceLine" in this.sett ? this.sett.priceLine : true;
        },
        colorCandleUp() {
            return this.sett.colorCandleUp || this.$props.colors.candleUp;
        },
        colorCandleDw() {
            return this.sett.colorCandleDw || this.$props.colors.candleDw;
        },
        colorWickUp() {
            return this.sett.colorWickUp || this.$props.colors.wickUp;
        },
        colorWickDw() {
            return this.sett.colorWickDw || this.$props.colors.wickDw;
        },
        colorWickSm() {
            return this.sett.colorWickSm || this.$props.colors.wickSm;
        },
        colorVolUp() {
            return this.sett.colorVolUp || this.$props.colors.volUp;
        },
        colorVolDw() {
            return this.sett.colorVolDw || this.$props.colors.volDw;
        },
    },
    data() {
        return { price: {} };
    },
};
</script>
