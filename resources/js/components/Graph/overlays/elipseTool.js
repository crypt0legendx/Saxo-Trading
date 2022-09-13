
// Draws a line, adds corresponding collision f-n

import { Utils } from 'trading-vue-js'
import Math2 from "../primitives/math"

export default class Elipse {

    // Overlay ref, canvas ctx
    constructor(overlay, ctx) {
        this.ctx = ctx
        this.comp = overlay
        this.T = overlay.$props.config.TOOL_COLL
        if (Utils.is_mobile) this.T *= 2
    }

    // p1[t, $], p2[t, $] (time-price coordinates)
    draw(p1, p2) {

        const layout = this.comp.$props.layout

        let x1 = layout.t2screen(p1[0])
        let y1 = layout.$2screen(p1[1])
        let x2 = layout.t2screen(p2[0])
        let y2 = layout.$2screen(p2[1])

        // this.ctx.moveTo(x1, y1)
        let radiusX = Math.abs((x1 - x2) / 2)
        let radiusY = Math.abs((y1 - y2) / 2)

        let w = layout.width
        let h = layout.height
        // TODO: transform k (angle) to screen ratio
        // (this requires a new a2screen function)

        // this.ctx.arc(x2, y2, radius * 0.236, 0, 2 * Math.PI);
        // this.ctx.stroke();
        // this.ctx.beginPath()
        this.ctx.arc(x1, y1, radiusX, radiusY, 0, 0, 2 * Math.PI);

        // this.comp.collisions.push(
        //     this.make([x1, y1], [x2, y2])
        // )
    }

    // Collision function. x, y - mouse coord.
    make(p1, p2) {
        let f =
            Math2.point2line.bind(Math2)
        return (x, y) => {
            return f(
                [x, y], p1, p2
            ) < this.T
        }
    }
}
