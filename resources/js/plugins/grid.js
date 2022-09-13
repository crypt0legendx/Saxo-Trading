import Grid from '../components/Grid/Grid';
import store from '../store'

const bfs = (data) => {
  const queue = []
  queue.push(data)

  while(queue.length !== 0) {
    const { node, ref, orientation } = queue.shift()

    if(node.type === "cell") {
      return { node, ref, orientation }
    }

    queue.push({
      node: node.first.el,
      ref: node.first,
      orientation: node.orientation
    }, {
      node: node.second.el,
      ref: node.second,
      orientation: node.orientation
    })
  }
}

const invertOrientation = (orientation) => orientation === "horizontal" ? "vertical" : "horizontal"

export const grid = {
    install(Vue, options) {
        this.event = new Vue();
        Vue.component('grid', Grid);

        Vue.prototype.$grid = {
          dropZones: ["top", "bottom", "left", "right"],
          nextId: 0,

          add(data, set = false) {
              var grid = store.getters.get_workspace_data({wid: store.getters.get_active_workspace})

              let id = Vue.prototype.$grid.nextId++

              do { id = Vue.prototype.$grid.nextId++ }
              while(Vue.prototype.$grid.findCellAndParentById(id) != null)

              const cell = {
                  id: id,
                  type: "cell",
                  color: data,
              }

              if (!grid || grid === {}) {
                  grid = cell
                  store.commit('update_workspace_data', {wid: store.getters.get_active_workspace, data: grid})
                  return
              }

              const { node, ref, orientation } = bfs({
                node: grid,
                ref: null
              })

              if(!data.orientation && ref) {
                ref.el = {
                  type: "container",
                  orientation: invertOrientation(orientation),
                  first: {
                    size: 1,
                    el: node
                  },
                  second: {
                    size: 1,
                    el: cell
                  }
                }

              }
              else {
                Vue.prototype.$grid.moveToEdge(invertOrientation(data.orientation || "vertical"), cell, false)
              }

              Vue.prototype.$grid.fixGridData()
          },
          remove(id) {
            var grid = store.getters.get_workspace_data({wid: store.getters.get_active_workspace})
              if (grid.type === "cell") {
                  grid = null
                  store.commit('update_workspace_data', {wid: store.getters.get_active_workspace, data: grid})
                  return
              }

              const cellData = Vue.prototype.$grid.findCellAndParentById(id)

              if (cellData) {
                  let [, cellParent] = cellData
                  cellParent.el = null
              } else if (grid.first.el.id === id) {
                  grid = {
                      type: "container",
                      first: {
                          size: 1,
                          el: {
                              ...grid.second.el,
                          },
                      },
                      second: {
                          size: 1,
                          el: null,
                      },
                  }
              } else {
                  grid = {
                      type: "container",
                      first: {
                          size: 1,
                          el: null,
                      },
                      second: {
                          size: 1,
                          el: {
                              ...grid.first.el,
                          },
                      },
                  }
              }

              Vue.prototype.$grid.fixGridData()
          },
          drop(e, zone) {
            var grid = store.getters.get_workspace_data({wid: store.getters.get_active_workspace})

              const [cell, cellParent] = Vue.prototype.$grid.findCellAndParentById(e.data)

              cellParent.el = null

              switch (zone) {
                  case "top":
                      Vue.prototype.$grid.moveToEdge("vertical", cell, true)
                      break
                  case "bottom":
                      Vue.prototype.$grid.moveToEdge("vertical", cell, false)
                      break

                  case "left":
                      Vue.prototype.$grid.moveToEdge("horizontal", cell, true)
                      break

                  case "right":
                      Vue.prototype.$grid.moveToEdge("horizontal", cell, false)
                      break
              }
              Vue.prototype.$grid.fixGridData()
          },
          moveToEdge(orientation, dragCell, isFirst) {
              var grid = store.getters.get_workspace_data({wid: store.getters.get_active_workspace})
              const cell = {
                  size: 50,
                  el: { ...dragCell },
              }

              const rest = {
                  size: 50,
                  el: { ...grid },
              }

              var grid = {
                  type: "container",
                  orientation,
                  first: isFirst ? cell : rest,
                  second: !isFirst ? cell : rest,
              }

              store.commit('update_workspace_data', {wid: store.getters.get_active_workspace, data: grid})
          },

          getWndID(id) {
            var wnd =  Vue.prototype.$grid.findCellAndParentById(id)

            if(wnd != undefined && wnd[0] != undefined)
              wnd = wnd[0]

            return wnd
          },

          updateCellById(id, key, value) {
            var grid = store.getters.get_workspace_data({wid: store.getters.get_active_workspace})
              const traverse = (node) => {
                  if (!node) {
                      return null
                  }

                  if(node.id != undefined && node.id == id)
                    return node.color.additional[key] = value

                  if (node.first?.el.id === id) {
                      node.first.el.color.additional[key] = value
                  }

                  if (node.second?.el.id === id) {
                      return node.second.el.color.additional[key] = value
                  }

                  const searchFirst = traverse(node.first?.el)

                  if (searchFirst) {
                      return searchFirst
                  }

                  const searchSecond = traverse(node.second?.el)

                  if (searchSecond) {
                      return searchSecond
                  }

                  return null
              }

              traverse(grid)
              store.commit('update_workspace_data', {wid: store.getters.get_active_workspace, data: grid})
          },

          findCellAndParentById(id) {
            var grid = store.getters.get_workspace_data({wid: store.getters.get_active_workspace})
              const traverse = (node) => {
                  if (!node) {
                      return null
                  }

                  if(node.id != undefined && node.id == id)
                    return node

                  if (node.first?.el.id === id) {
                      return [node.first.el, node.first]
                  }

                  if (node.second?.el.id === id) {
                      return [node.second.el, node.second]
                  }

                  const searchFirst = traverse(node.first?.el)

                  if (searchFirst) {
                      return searchFirst
                  }

                  const searchSecond = traverse(node.second?.el)

                  if (searchSecond) {
                      return searchSecond
                  }

                  return null
              }

              return traverse(grid)
          },
          move(id, dropId, zone) {
              var grid = store.getters.get_workspace_data({wid: store.getters.get_active_workspace})

              const [dragCell, dragCellParent] = Vue.prototype.$grid.findCellAndParentById(id)
              const [dropCell, dropCellParent] = Vue.prototype.$grid.findCellAndParentById(
                  dropId
              )

              switch (zone) {
                  case "replace":
                      return Vue.prototype.$grid.replace(
                          dragCell,
                          dragCellParent,
                          dropCell,
                          dropCellParent
                      )
                  case "top":
                      return Vue.prototype.$grid.rearrange(
                          "vertical",
                          dragCell,
                          dropCell,
                          dropCellParent,
                          dragCellParent
                      )
                  case "bottom":
                      return Vue.prototype.$grid.rearrange(
                          "vertical",
                          dropCell,
                          dragCell,
                          dropCellParent,
                          dragCellParent
                      )

                  case "left":
                      return Vue.prototype.$grid.rearrange(
                          "horizontal",
                          dragCell,
                          dropCell,
                          dropCellParent,
                          dragCellParent
                      )

                  case "right":
                      return Vue.prototype.$grid.rearrange(
                          "horizontal",
                          dropCell,
                          dragCell,
                          dropCellParent,
                          dragCellParent
                      )
              }
          },

          replace(dragCell, dragCellParent, dropCell, dropCellParent) {
              dragCellParent.el = dropCell
              dropCellParent.el = dragCell

              this.fixGridData()
          },

          rearrange(
              orientation,
              firstCell,
              secondCell,
              dropCellParent,
              dragCellParent
          ) {
              dropCellParent.el = {
                  type: "container",
                  orientation: orientation,
                  first: {
                      size: 50,
                      el: firstCell,
                  },
                  second: {
                      size: 50,
                      el: secondCell,
                  },
              }

              dragCellParent.el = null
              Vue.prototype.$grid.fixGridData()
          },

          fixGridData() {
              var grid = store.getters.get_workspace_data({wid: store.getters.get_active_workspace})
              const traverse = (node, parent) => {
                  if (!node) return
                  if (node.type === "cell") return

                  if (node.first?.el === null) {
                      if (parent) {
                          parent.el = node.second.el
                      } else {
                          grid = node.second.el
                      }
                  }

                  if (node.second?.el === null) {
                      if (parent) {
                          parent.el = node.first.el
                      } else {
                          grid = node.first.el
                      }
                  }

                  if (node.first) {
                      traverse(node.first.el, node.first)
                  }

                  if (node.second) {
                      traverse(node.second.el, node.second)
                  }
              }

              traverse(grid, null)

              store.commit('update_workspace_data', {wid: store.getters.get_active_workspace, data: grid})
          },
        }
    }
}
