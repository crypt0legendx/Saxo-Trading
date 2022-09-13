<script>
import { Overlay } from "trading-vue-js";

export default {
    name: "Dots",
    mixins: [Overlay],
    methods: {
        meta_info() {
            return { author: "C451", version: "1.0.2" };
        },
        draw(ctx) {
            const layout = this.$props.layout;
            const data = this.$props.data;
            ctx.lineWidth = 1.5;
            ctx.strokeStyle = "black";
            for (var k = 0, n = data.length; k < n; k++) {
                let p = data[k];
                ctx.fillStyle = this.buy_color;
                ctx.beginPath();
                let x = layout.t2screen(p[0]); // x - Mapping
                let y = layout.$2screen(p[4]); // y - Mapping
                ctx.arc(x, y, this.marker_size + 0.5, 0, Math.PI * 2, true);
                ctx.fill();
                ctx.stroke();
            }
        },

        use_for() {
            return ["Dots"];
        },

        // Defines legend format (values & colors)
    },
    // Define internal setting & constants here
    computed: {
        sett() {
            return this.$props.settings;
        },
        default_font() {
            return "12px " + this.$props.font.split("px").pop();
        },
        buy_color() {
            return this.sett.buyColor || "#00deff";
        },
        sell_color() {
            return this.sett.sellColor || "#ec4662";
        },
        label_color() {
            return this.sett.labelColor || "#999";
        },
        marker_size() {
            return this.sett.markerSize || 5;
        },
        show_label() {
            return this.sett.showLabel !== false;
        },
        new_font() {
            return this.sett.font || this.default_font;
        },
    },
};
</script>
