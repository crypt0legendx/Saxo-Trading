<script>
import { Overlay } from "trading-vue-js";

export default {
    name: "TRIX",
    mixins: [Overlay],
    methods: {
        meta_info() {
            return {
                author: ".",
                version: "1.0.0",
                desc: "TRIX",
                preset: {
                    name: "TRIX $length",
                    side: "offchart",
                    settings: {
                        lineWidth: 0.75,
                        color: "#d1385c",
                    },
                },
            };
        },
        use_for() {
            return ["TRIX"];
        },
        calc() {
            return {
                props: {
                    length: { def: 25, text: "Length" },
                    color: { def: "#d1385c", text: "Color" },
                },
                conf: { renderer: "Spline" },
                init: `
                    settings({color: color})
                `,
                update: `
                let ema1 = ema(close, length);
                let ema2 = ema(ema1, length);
                let ema3 = ema(ema2, length);
                    return (ema3[0] - ema3[1])/ema3[1]
                `,
            };
        },
    },
};
</script>
