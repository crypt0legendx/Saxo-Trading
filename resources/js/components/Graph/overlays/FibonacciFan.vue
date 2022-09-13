<script>
// Line drawing tool
// TODO: make an angle-snap when "Shift" is pressed
import { Tool, Overlay, primitives } from "trading-vue-js";
const { Pin, Seg, Ray, Line } = primitives;

import Icons from "./icons.json";

export default {
    name: "Fan",
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
                type: "Fibonacci",
                hint: "This hint will be shown on hover",
                data: [{ name: 'Fibonacci Fan', drawing: true }], // Default data
                settings: { extended: true }, // Default settings
                // Modifications
            };
        },
        // Called after overlay mounted
        init() {
            // First pin is settled at the mouse position
            this.pins.push(new Pin(this, "p1"));
            // Second one is following mouse until it clicks
            this.pins.push(
                new Pin(this, "p2", {
                    state: "tracking",
                })
            );
            this.pins[0].on("settled", () => {
                this.set_state("finished");
                this.$emit("drawing-mode-off");
            });
            this.pins[1].on("settled", () => {
                this.set_state("finished");
                this.$emit("drawing-mode-off");
            });
        },
        draw(ctx) {
            if (!this.p1 || !this.p2) return;

            ctx.lineWidth = this.line_width;
            ctx.strokeStyle = this.color;
            ctx.beginPath();
            new Seg(this, ctx).draw(this.p1, this.p2)
            new Ray(this, ctx).draw(this.p1, [
                this.p2[0],
                this.p1[1] + (this.p2[1] - this.p1[1]) * 0.681,
            ]);
            new Ray(this, ctx).draw(this.p1, [
                this.p2[0],
                this.p1[1] + (this.p2[1] - this.p1[1]) * 0.5,
            ]);
            new Ray(this, ctx).draw(this.p1, [
                this.p2[0],
                this.p1[1] + (this.p2[1] - this.p1[1]) * 0.382,
            ]);
            ctx.stroke();

            this.render_pins(ctx);
        },
        use_for() {
            return ["Fan"];
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
