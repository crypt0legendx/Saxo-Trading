<template>
    <div class="dashboard-component p-2 col-12 col-lg-11 col-md-11">
        <h5 class="mb-4 text-center header-label">Choose Funding Method</h5>

        <b-table borderless id='fund-methods' :fields="tableData.fields" :items="tableData.methods" @row-clicked="clickHandler">
            <template #cell(info)='data'>
                <v-icon>mdi-{{ data.item.info.method }}</v-icon>
                <span>{{ data.item.info.name }}</span>
            </template>
            <template #cell(arrow)='data'>
                <a class="next" @click="$emit('fund', data.value)">
                    <v-icon>mdi-chevron-right</v-icon>
                </a>
            </template>
        </b-table>

        <OptionBtns
            :hideSubmit='true'
            :backText='`Back`'
            @returnEvent="$emit('return')"
        />
    </div>
</template>

<script>
import OptionBtns from '../OptionBtns.vue';

export default {
    name: 'AddWalletFunds',
    components: {
        OptionBtns
    },
    data() {
        return {
            tableData: {
                fields: [
                    { key: 'info', label: 'FUNDING METHOD' },
                    { key: 'progressTime', label: 'PROGRESS TIME' },
                    { key: 'fee', label: 'FEE' },
                    { key: 'arrow', label: ' '},
                ],
                methods: [
                    { info: { name: 'Bitcoin', method: 'bitcoin' }, progressTime: 'Instant', fee: '0%', arrow: "btc" },
                    { info: { name: 'Credit Card', method: 'credit-card' }, progressTime: 'Instant', fee: '0%', arrow: "card" },
                    { info: { name: 'Bank Transfer', method: 'bank' }, progressTime: 'Instant', fee: '0%', arrow: "bank" },
                ]
            }
        }
    },
    methods: {
        addWalletFunds() {
            this.$emit('addWalletFunds');
            this.$emit('return');
        },
        clickHandler(record, index) {
          this.$emit('fund', record.arrow)
        }
    }
}
</script>

<style lang='scss'>

.next i {
  cursor:pointer;
  transition: .2s all;
  color:white !important;

  &:hover {
    color: rgb(100,100,200) !important;
  }
}
.fund-method {
    line-height: 60px;

    img {
        max-width: 260px !important;
        height: 60px;
    }
}
#fund-methods {

    th, td {
        border-top: none !important;
    }

    tr {
        border-bottom-width: 1px !important;
        transition: background-color .15s ease-in-out;

        &:hover {
          cursor: pointer;
          background: rgba(0, 0, 0, 0.1)
        }
    }

    tr > td:first-child {
        border-top: none !important;
    }

    /*tr:hover {
        background-color: #2d3538;
        cursor: pointer;
    }*/

    thead {
        border-bottom: none !important;

        th {
            padding: 1.25rem 0.75rem !important;
            font-size: 12px !important;
            color: #b2bec3;
            font-weight: 500;
        }
    }

    tbody {

        tr:last-child {
            border-bottom: none !important;
        }

        tr {
            border-bottom: 1px solid #424a4d;
            margin: 10px 0px;
            td {
                color: #f9fafb;
                font-size: 14px;
                line-height: 60px;

                img {
                    max-width: 260px !important;
                    height: 60px;
                }

                .v-icon::before {
                    padding: 0px !important;
                    margin: 0px !important;
                    font-size: 32px !important;
                }
            }
        }
    }
}
</style>
