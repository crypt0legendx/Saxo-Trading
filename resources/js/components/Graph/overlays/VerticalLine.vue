<script>
// Line drawing tool
// TODO: make an angle-snap when "Shift" is pressed
import { Tool, Overlay, primitives } from "trading-vue-js";
const { Pin, Seg, Ray, Line } = primitives;
import Icons from "./icons.json";
export default {
    name: "VerticalLine",
    mixins: [Overlay, Tool],
    methods: {
        meta_info() {
            return { author: ".", version: "1.1.0" };
        },
        tool() {
            return {
                // Descriptor for the tool
                group: "Lines",
                icon: Icons["extended.png"],
                type: "Extended",
                hint: "This hint will be shown on hover",
                data: [{ name: 'Vertical Line', drawing: true }], // Default data
                settings: { extended: true }, // Default settings
                // Modifications
            };
        },
        // Called after overlay mounted
        init() {
            // First pin is settled at the mouse position
            this.pins.push(new Pin(this, "p1"));
            this.pins[0].on("settled", () => {
                this.set_state("finished");
                this.$emit("drawing-mode-off");
            });
        },
        draw(ctx) {
            if (!this.p1) return;
            ctx.lineWidth = this.line_width;
            ctx.strokeStyle = this.color;
            ctx.beginPath();
            new Line(this, ctx).draw(this.p1, [this.p1[0], this.p1[1] + 1]);
            ctx.stroke();
            this.render_pins(ctx);
        },
        use_for() {
            return ["VerticalLine"];
        },
        data_colors() {
            return [this.color];
        },
    },
    // Define internal setting & constants here
    computed: {
        sett() {
            return this.$props.settings;
        },
        p1() {
            return this.$props.settings.p1;
        },
        p2() {
            return this.$props.settings.p2;
        },
        line_width() {
            return this.sett.lineWidth || 0.9;
        },
        color() {
            return this.sett.color || "#e3895d";
        },
    },
    data() {
        return {};
    },
};
</script>
