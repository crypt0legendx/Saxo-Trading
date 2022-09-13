export const tableresizable = {
  install(Vue, options) {
    this.event = new Vue();

    Vue.prototype.$resizable_table = {

      init(table) {
        Vue.prototype.$resizable_table.resizableGrid(table);
      },

      resizableGrid(table) {
        var row = table.getElementsByTagName('tr')[0],
        cols = row ? row.children : undefined;
        if (!cols) return;

        table.style.overflow = 'hidden';

        var tableHeight = table.offsetHeight;

        for (var i=0;i<cols.length;i++){
          var div = Vue.prototype.$resizable_table.createDiv(tableHeight);
          cols[i].appendChild(div);
          cols[i].style.position = 'relative';
          Vue.prototype.$resizable_table.setListeners(div);
        }
      },

      setListeners(div){
        var pageX,curCol,nxtCol,curColWidth,nxtColWidth;

        div.addEventListener('mousedown', function (e) {
          curCol = e.target.parentElement;
          nxtCol = curCol.nextElementSibling;
          pageX = e.pageX;

          var padding = Vue.prototype.$resizable_table.paddingDiff(curCol);

          curColWidth = curCol.offsetWidth - padding;
          if (nxtCol)
          nxtColWidth = nxtCol.offsetWidth - padding;
        });

        div.addEventListener('mouseover', function (e) {
          e.target.style.borderRight = '2px solid #0000ff';
        })

        div.addEventListener('mouseout', function (e) {
          e.target.style.borderRight = '';
        })

        document.addEventListener('mousemove', function (e) {
          if (curCol) {
            var diffX = e.pageX - pageX;

            if (nxtCol)
            nxtCol.style.width = (nxtColWidth - (diffX))+'px';

            curCol.style.width = (curColWidth + diffX)+'px';
          }
        });

        document.addEventListener('mouseup', function (e) {
          curCol = undefined;
          nxtCol = undefined;
          pageX = undefined;
          nxtColWidth = undefined;
          curColWidth = undefined
        });
      },

      createDiv(height){
        var div = document.createElement('div');
        div.style.top = 0;
        div.style.right = 0;
        div.style.width = '5px';
        div.style.position = 'absolute';
        div.style.cursor = 'col-resize';
        div.style.userSelect = 'none';
        div.style.height = height + 'px';
        return div;
      },

      paddingDiff(col){

        if (Vue.prototype.$resizable_table.getStyleVal(col,'box-sizing') == 'border-box'){
          return 0;
        }

        var padLeft = Vue.prototype.$resizable_table.getStyleVal(col,'padding-left');
        var padRight = Vue.prototype.$resizable_table.getStyleVal(col,'padding-right');
        return (parseInt(padLeft) + parseInt(padRight));

      },

      getStyleVal(elm,css){
        return (window.getComputedStyle(elm, null).getPropertyValue(css))
      },
    }
  }
}
