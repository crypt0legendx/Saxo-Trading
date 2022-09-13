<script>
import { Overlay } from "trading-vue-js";

export default {
    name: "TMA",
    mixins: [Overlay],
    methods: {
        meta_info() {
            return {
                author: ".",
                version: "1.0.0",
                desc: "Triangular Moving Average",
                preset: {
                    name: "TMA $length",
                    side: "onchart",
                    settings: {
                        lineWidth: 0.75,
                        color: "#d1385c",
                    },
                },
            };
        },
        use_for() {
            return ["TMA"];
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
                    return sma(sma(close, Math.ceil(length / 2)), Math.floor(length / 2) + 1)[0]
                `,
            };
        },
    },
};
</script>
