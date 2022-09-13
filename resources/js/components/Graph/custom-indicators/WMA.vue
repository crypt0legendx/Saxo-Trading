<script>
import { Overlay } from "trading-vue-js";

export default {
    name: "WMA",
    mixins: [Overlay],
    methods: {
        meta_info() {
            return {
                author: ".",
                version: "1.0.0",
                desc: "Weighted Moving Average",
                preset: {
                    name: "WMA $length",
                    side: "onchart",
                    settings: {
                        lineWidth: 0.75,
                        color: "#d1385c",
                    },
                },
            };
        },
        use_for() {
            return ["WMA"];
        },
        calc() {
            return {
                props: {
                    length: { def: 25, text: "Length" },
                    color: { def: "#d1385c", text: "Color" },
                },
                conf: { renderer: "Splines" },
                init: `
                    settings({color: color})
                `,
                update: `
                    return wma(close, length)[0]
                `,
            };
        },
    },
};
</script>
