<script>
import { Overlay } from "trading-vue-js";

export default {
    name: "ROC",
    mixins: [Overlay],
    methods: {
        meta_info() {
            return {
                author: ".",
                version: "1.0.0",
                desc: "Rate Of Change",
                preset: {
                    name: "ROC $length",
                    side: "onchart",
                    settings: {
                        lineWidth: 0.75,
                        color: "#d1385c",
                    },
                },
            };
        },
        use_for() {
            return ["ROC"];
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
                    return roc(close, length)
                `,
            };
        },
    },
};
</script>
