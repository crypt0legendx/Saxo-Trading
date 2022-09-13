<template>
    <b-modal
    id='select-instrument-modal'
    ref="select-instrument-modal"
    size="md"
    hide-footer
    no-close-on-backdrop
    centered
    no-fade
    >
    <template v-slot:modal-title><v-icon>mdi-magnify</v-icon> Search and Select</template>
    <template v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
    <template v-slot:default>
        <v-container class="sel-ins-cnt">
            <div class="search">
                <input v-model='stocks_search' placeholder="Search for instruments..."/>
            </div>

            <div class="stocks-list">

                <div @click="onStockClick(stock)" v-for="stock in filteredList" :key="stock.name">
                    <div class="stock">
                        <div class="stock_name">{{stock.name}}</div>
                        <div class="category">{{stock.category.toUpperCase(g)}} <v-icon>mdi-chevron-right</v-icon></div>
                    </div>
                </div>

            </div>

        </v-container>
    </template>
</b-modal>
</template>

<style lang='scss'>
@import '~/variables';
@import '~/modal_css';

#select-instrument-modal .modal-dialog .modal-content {
  opacity: 0;
}

.search input {
    font-size: 13px !important;
}

.stocks-list {
    overflow-y: auto;
    max-height:550px;

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

    .stock {
        cursor:pointer;
        margin-top:2px;
        font-family: 'Roboto';
        padding:8px;
        display:flex;
        flex-direction:row;
        justify-content:space-between;
        align-items:center;

        color:$select_f;
        background: $input_search_stocks_bg;

        .stock_name {
            font-size:15px;
        }

        &:hover {
            background: $stock_hover;
        }

        .category {
            display:flex;
            align-items:center;
            font-size:14px;

            i {
                font-size:20px;
            }
        }
    }
}


.sel-ins-cnt {
    padding-left:0 !important;
    padding-right:0 !important;
}

.search {
    height:40px;
    width:100%;

    input {
        width:100%;
        background: $select_bg;
        color: $select_f;
        outline: none;
        border: none;
        padding: 5px 10px;
        font-size: 14px;
        height:35px;
    }
}
</style>

<script>
import {instrumentModal} from '../../plugins/modals.js';

export default {
    name: 'SelectInstrumentModal',
    methods: {
        showModal() {
            this.$refs['select-instrument-modal'].show()
            setTimeout(() => {
                document.querySelector('#select-instrument-modal___BV_modal_outer_ #select-instrument-modal').classList.toggle('light-theme', this.$store.state.light_theme);
                document.querySelector('#select-instrument-modal___BV_modal_outer_ #select-instrument-modal .modal-dialog .modal-content').style.opacity = '1';
            }, 150);
        },

        hideModal() {
            this.$refs['select-instrument-modal'].hide()
        },

        onStockClick(stock) {
            var s = this.from.split('#')
            if(s.length == 2) { //from not favs
                this.$store.commit('broadcast', {
                    event: 'stock_selected',
                    wndID: s[0],
                    stock: stock.api_name,
                })
                this.hideModal();
                return
            }
            
            this.$emit('stockSelected', stock)
            let th = this
            this.$store.dispatch('addtoFavs', {cat: th.from, symbol: stock.api_name}).catch(error => {
                th.$sound.play('error.mp3')
                  this.$dtoast.pop({
                    preset: "error",
                    heading: `Watchlist Error`,
                    content:  `You have already added this instrument`,
                })
            })
            this.hideModal();
          },
    },
    beforeMount() {
        instrumentModal.event.$on('show-select-instrument-modal', (from) => {
            this.showModal();
            this.from = from;
        })
    },

    data: function() {
        return {
            from: '',
            stocks_search: '',
        }
    },

    computed: {
        filteredList() {
            return this.$store.getters.get_stocks.filter(stock => {
                return stock.name.toLowerCase().includes(this.stocks_search.toLowerCase())
            }).sort((a,b) => a.category.localeCompare(b.category))
        }
    },
}
</script>
