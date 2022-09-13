<script>
import { Overlay } from "trading-vue-js";

export default {
    name: "ER",
    mixins: [Overlay],
    methods: {
        meta_info() {
            return {
                author: ".",
                version: "1.0.0",
                desc: "Efficiency ratio",
                preset: {
                    name: "ER $length",
                    side: "offchart",
                    settings: {
                        lineWidth: 0.75,
                        color: "#d1385c",
                    },
                },
            };
        },
        use_for() {
            return ["ER"];
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
                let old = ts(close) 
                old = ts(offset(old[0], 10))[0][10]
                

                return sub(close, old)                    
                `,
            };
        },
    },
};
</script>
