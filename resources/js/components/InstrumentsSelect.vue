<template>
  <div class="selectStock">
    <div class="--custom">
      <input type="text" class="search_stocks" v-model="stocks_search" placeholder="Search Instruments..." autofocus/>
    </div>
    <div class="list-content">
      <div @click="onStockClick(stock)" v-for="stock in filteredList" :key="stock.name">
        <div class="--custom stock-entry">
          <span>{{stock.name}}</span>
          <span style="text-transform:uppercase">{{stock.category}}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: "InstrumentsSelect",

    props : {
      filter: String,
    },

    data: function() {
      return {
        stocks_search: '',
      }
    },

    methods : {
      onStockClick(stock) {
        this.$emit('stockSelected', stock)
      },
    },

    computed: {
      filteredList() {
        if(this.filter == "fundamentals") {
          return this.$store.getters.get_stocks.filter(stock => {
              return stock.name.includes('#') && stock.name.toLowerCase().includes(this.stocks_search.toLowerCase())
          })
        }

        return this.$store.getters.get_stocks.filter(stock => {
          return stock.name.toLowerCase().includes(this.stocks_search.toLowerCase())
        })
      }
    }
}

</script>

<style lang="scss">
@import '../scss/_variables.scss';
@import "../../../node_modules/vue-select/src/scss/vue-select.scss";

.selectStock, .list-content {
  width: 210px !important;
}

.list-content {
    background: $bg;
    max-height:158px;
    overflow-y:auto;

    &::-webkit-scrollbar {
      width: 5px;
    }

    /* Track */
    &::-webkit-scrollbar-track {
      background: none;
    }

    /* Handle */
    &::-webkit-scrollbar-thumb {
      background: #888;
    }

    /* Handle on hover */
    &::-webkit-scrollbar-thumb:hover {
      background: #555;
    }
}

.stock-entry {
    cursor:pointer;
    user-select:none;
    display:flex;
    justify-content:space-between !important;
    padding:0px !important;
    font-size: 13px !important;

    span {
      font-size:12px;
    }

    &:hover {
      background: $navbar_menus_bg_hover;
    }
}

.--custom {
  display:flex;
  width:100%;
  height:35px;
  font-size:13px;
  font-family: 'Roboto', sans-serif;
  align-items:center;
  justify-content:center;
}

.search_stocks {
  height:100%;
  width:100%;
  background:$input_search_stocks_bg;
  border:none;
  padding:5px;
  color:$topbar_fcolor;
  transition: .2s all;
  border-radius: none !important;

  &:focus {
    border:none;
    outline:none;
  }
}

</style>
