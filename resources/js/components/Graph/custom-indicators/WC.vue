<script>
import { Overlay } from "trading-vue-js";

export default {
    name: "WC",
    mixins: [Overlay],
    methods: {
        meta_info() {
            return {
                author: ".",
                version: "1.0.0",
                desc: "Weighted Close",
                preset: {
                    name: "WC $length",
                    side: "onchart",
                    settings: {
                        lineWidth: 0.75,
                        color: "#d1385c",
                    },
                },
            };
        },
        use_for() {
            return ["WC"];
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
                    return div(add(add(high, low), mult(close, 2)), 4)
                `,
            };
        },
    },
};
</script>
