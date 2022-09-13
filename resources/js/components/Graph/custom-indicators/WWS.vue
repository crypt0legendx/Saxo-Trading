<script>
import { Overlay } from "trading-vue-js";

export default {
    name: "WWS",
    mixins: [Overlay],
    methods: {
        meta_info() {
            return {
                author: ".",
                version: "1.0.0",
                desc: "Welles Wilder Smoothing",
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
            return ["WWS"];
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
                    return ema(close, (2 * length - 1))[0]
                `,
            };
        },
    },
};
</script>
