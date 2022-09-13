<script>
import { Overlay } from "trading-vue-js";

export default {
    name: "Qstick",
    mixins: [Overlay],
    methods: {
        meta_info() {
            return {
                author: ".",
                version: "1.0.0",
                desc: "Qstick",
                preset: {
                    name: "QS $length",
                    side: "offchart",
                    settings: {
                        lineWidth: 0.75,
                        color: "#d1385c",
                    },
                },
            };
        },
        use_for() {
            return ["Qstick"];
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
                    return ema(sub(close, open), length)[0]
                `,
            };
        },
    },
};
</script>
