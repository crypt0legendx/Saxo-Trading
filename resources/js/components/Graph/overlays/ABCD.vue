<script>
// Line drawing tool
// TODO: make an angle-snap when "Shift" is pressed
import { Tool, Overlay, primitives } from "trading-vue-js";
const { Pin, Seg, Ray, Line } = primitives;

import Icons from "./icons.json";

export default {
    name: "ABCD",
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
                hint: "This hint will be shown on hover",
                data: [{ name: 'ABCD Pattern', drawing: true }], // Default data
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

            // Third one is following mouse until it clicks
            this.pins.push(
                new Pin(this, "p3", {
                    state: "tracking",
                })
            );

            // Fourth one is following mouse until it clicks
            this.pins.push(
                new Pin(this, "p4", {
                    state: "tracking",
                })
            );

            //
            this.pins[0].on("settled", () => {
                this.set_state("finished");
                this.$emit("drawing-mode-off");
            })
            this.pins[1].on("settled", () => {
                this.set_state("finished");
                this.$emit("drawing-mode-off");
            })
            this.pins[2].on("settled", () => {
                this.set_state("finished");
                this.$emit("drawing-mode-off");
            })
            this.pins[3].on("settled", () => {
                this.set_state("finished");
                this.$emit("drawing-mode-off");
            })
        },
        draw(ctx) {
            if (!this.p1 || !this.p2 || !this.p3 || !this.p4) return;

            ctx.lineWidth = this.line_width;
            ctx.strokeStyle = this.color;
            ctx.beginPath();
            new Seg(this, ctx).draw(this.p1, this.p2);
            new Seg(this, ctx).draw(this.p2, this.p3);
            new Seg(this, ctx).draw(this.p3, this.p4);
            ctx.stroke();

            this.render_pins(ctx);
        },
        use_for() {
            return ["ABCD"];
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
        p3() {
            return this.$props.settings.p3;
        },
        p4() {
            return this.$props.settings.p4;
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
