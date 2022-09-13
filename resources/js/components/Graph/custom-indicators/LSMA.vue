<script>
import { Overlay } from "trading-vue-js";

export default {
    name: "LSMA",
    mixins: [Overlay],
    methods: {
        meta_info() {
            return {
                author: ".",
                version: "1.0.0",
                desc: "Least Squares Moving Average",
                preset: {
                    name: "LSMA $length",
                    side: "onchart",
                    settings: {
                        lineWidth: 0.75,
                        color: "#d1385c",
                    },
                },
            };
        },
        use_for() {
            return ["LSMA"];
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
                let id = _tsid(_id, \`linreg(${length})\`)

                src.__len__ = Math.max(src.__len__ || 0, len)
                let data = close
                let len = length
                let offset = 0
                data = data.slice(0, len).reverse().map((x, i) => [i, x])

                var sum_x = 0,
                    sum_y = 0,
                    sum_xy = 0,
                    sum_xx = 0,
                    count = 0,
                    m, b

                // calculate sums
                for (var i = 0, len = data.length; i < len; i++) {
                    if (!data[i]) return NaN
                    var point = data[i]
                    sum_x += point[0]
                    sum_y += point[1]
                    sum_xx += point[0] * point[0]
                    sum_xy += point[0] * point[1]
                    count++
                }

                // calculate slope (m) and y-intercept (b) for f(x) = m * x + b
                m = (count * sum_xy - sum_x * sum_y) / (count * sum_xx - sum_x * sum_x)
                b = (sum_y / count) - (m * sum_x) / count

                let lr =  m * (data.length - 1 - offset) + b
                return ts(lr, id, src.__tf__)[0]
                `,
            };
        },
    },
};
</script>
