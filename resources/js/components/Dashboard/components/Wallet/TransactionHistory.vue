<template>
    <div id='transaction-history' class="w-100">
        <b-button v-b-toggle.collapse-history id='toggle-collapse' class="d-flex w-100 no-focus-outline text-center">
            Transaction History
            <span class="sm-txt ml-auto">
                Show details <v-icon>mdi-chevron-down</v-icon>
            </span>
        </b-button>
        <b-collapse id="collapse-history" class="pb-3">
            <div v-if="sw >= 768">
                <div class="history-details-collapse" v-for="(obj, index) in tableData.transactions" :key="index">
                    <b-button @click="toggleCollapse(index)" class="history-details-collapse-btn no-focus-outline">
                        <div class='d-flex'>
                            <v-icon class="mr-2">mdi-chevron-{{ !obj.collapsed  ? 'down' : 'up' }}</v-icon>
                            <span class="date mr-2">{{ obj.date }}</span>
                            <span class="time">{{ obj.time }}</span>
                            <span class="ml-auto">{{ obj.type }}</span>
                            <span class="ml-auto">{{ obj.method }}</span>
                            <span class="ml-auto">
                                <v-icon v-if="!obj.pending" :class="obj.cancelled ? 'cancelled' : 'succ'">
                                    mdi-{{ obj.cancelled ? 'cancel' : 'checkbox-marked-circle'}}
                                </v-icon>
                                <span v-if="obj.cancelled">Cancelled</span>
                                <span v-if="obj.pending">Pending</span>
                                <span v-if="!obj.cancelled & !obj.pending">Success</span>
                            </span>
                            <span class="ml-auto mr-2">
                                {{ obj.famount > 0 ? obj.famount : '--'}} USD
                            </span>
                        </div>
                    </b-button>
                    <b-collapse :id="`collapse-history-${index}`" class="">
                        <b-table
                        :fields="tableData.fields"
                        :items="[...tableData.transactions[index]]"
                        >
                            <template #cell(type)="data">
                                <strong>{{ data.value }}</strong>
                            </template>

                            <template #cell(conv_rate)="data">
                                {{ data.value === null ? '--' : data.value }}
                            </template>

                            <template #cell(famount)='data'>
                                {{ data.value > 0 ? data.value : '--'}} USD
                            </template>
                        </b-table>
                    </b-collapse>
                </div>
            </div>

            <div v-if="sw < 768">
                <div class="history-details-collapse" v-for="(obj, index) in tableData.transactions" :key="index">
                    <b-button @click="toggleCollapse(index)" class="history-details-collapse-btn no-focus-outline">
                        <div class='d-flex'>
                            <v-icon class="mr-2">mdi-chevron-{{ !obj.collapsed  ? 'down' : 'up' }}</v-icon>
                            <span class="date mr-2">{{ obj.date }}</span>
                            <span class="time">{{ obj.time }}</span>
                            <span class="ml-auto">
                                <v-icon :class="obj.cancelled ? 'cancelled' : 'succ'">
                                    mdi-{{ obj.cancelled ? 'cancel' : 'checkbox-marked-circle'}}
                                </v-icon>
                                {{ obj.cancelled ? 'Cancelled' : 'Success' }}
                            </span>
                            <span class="ml-auto mr-2">
                                {{ obj.famount > 0 ? obj.famount : '--'}} USD
                            </span>
                        </div>
                    </b-button>
                    <b-collapse :id="`collapse-history-${index}`" class="">
                        <b-table
                        stacked
                        :fields="tableData.fields_stacked"
                        :items="[...tableData.transactions[index]]"
                        >
                            <template #cell(type)="data">
                                <strong>{{ data.value }}</strong>
                            </template>

                            <template #cell(conv_rate)="data">
                                {{ data.value === null ? '--' : data.value }}
                            </template>

                            <template #cell(famount)='data'>
                                {{ data.value > 0 ? data.value : '--'}} USD
                            </template>
                        </b-table>
                    </b-collapse>
                </div>
            </div>
        </b-collapse>
    </div>
</template>

<script>
export default {
    name: 'transactions',
    data() {
        return {
            fill: false,
            sw: 0,
            tableData: {
                fields: [
                    { key: 'from', label: 'From' },
                    { key: 'amount', label: 'Amount' },
                    { key: 'to', label: 'To' },
                    { key: 'fee', label: 'Fee' },
                    { key: 'conv_rate', label: 'Conversion rate' },
                    { key: 'famount', label: 'Final amount' },
                ],
                fields_stacked: [
                    { key: 'type', label: 'Type' },
                    { key: 'method', label: 'Method' },
                    { key: 'from', label: 'From' },
                    { key: 'amount', label: 'Amount' },
                    { key: 'to', label: 'To' },
                    { key: 'fee', label: 'Fee' },
                    { key: 'conv_rate', label: 'Conversion rate' },
                    { key: 'famount', label: 'Final amount' },
                ],
                transactions: [

                ]
            },
        }
    },
    methods: {
        toggleCollapse(collapseId) {
            this.$root.$emit('bv::toggle::collapse', `collapse-history-${collapseId}`);
            const { collapsed } = this.tableData.transactions[collapseId];
            this.tableData.transactions[collapseId].collapsed = !collapsed;
        },
        onResize(e) {
            this.fill = window.innerWidth < 1024 ? true : false;
            this.sw = window.innerWidth;
        }
    },

    created() {
      this.$http.get(`/api/user/transaction/history`).then((res) => {

        const transactions = res.data

        if(!transactions.length)
        {
          return
        }

        transactions.forEach((trans) => {
          trans.type = trans.type.toUpperCase();

          var from = trans.type
          var to = "HOLDER"
          var fee = 0
          var conv_rate = 0

          if(trans.from != undefined)
            from = trans.from
          if(trans.account_from != undefined)
            from = "#" + trans.account_from.id

          if(trans.to != undefined)
            to = trans.to
          if(trans.account_from != undefined)
            to = "#" + trans.account_to.id

          let created = new Date(trans.created_at);
          this.tableData.transactions.push({
            collapsed: false,
            date: created.toLocaleDateString(),
            time: created.toLocaleTimeString(),
            type: 'Fund',
            method: trans.type,
            cancelled: trans.status == 'declined',
            pending: trans.status == 'pending',
            from: from,
            amount: trans.amount,
            to: to,
            fee: fee,
            conv_rate: conv_rate,
            famount: trans.amount - fee - conv_rate
          })
        })

      }).catch(error => {
        
      })
    },

    mounted() {
        window.addEventListener('resize', this.onResize);
        this.onResize();
    },
    beforeDestroy() {
        window.removeEventListener('resize', this.onResize);
    },
}
</script>

<style lang='scss'>
#toggle-collapse {
    font-size: 16px;
    background-color: transparent;
    color: #f9fafb;
    border: none;
    padding: 16px;
    font-weight: 500;

    .sm-txt {
        font-size: 12px;
        color: #84949a;
        font-weight: 400;
        padding-top: 2px;
    }
}

#collapse-history {
    margin: 0px !important;
    padding: 0px !important;

    .history-details-collapse:not(:last-child) {
        border-bottom: 1px solid #424a4d;
    }

    .history-details-collapse:last-child > .collapse.show {
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .history-details-collapse:last-child {
        .history-details-collapse-btn {
            border-bottom: 1px solid transparent !important;
        }
    }

    .history-details-collapse > .collapse.show {
        background-color: #2c3235 !important;
    }

    .history-details-collapse {

        .history-details-collapse-btn {
            color: #84949a;
            padding: 16px;
            width: 100%;
            border: none;
            border-radius: 0;
            background-color: transparent;
            font-size: 14px;

            .date {
                color: #f9fafb;
            }

            .time {
                color: #b2bec3;
            }

            .cancelled {
                color: #eb3749 !important;
            }

            .succ {
                color: #00b894 !important;
            }
        }

        .b-table {
            margin-bottom: 0px !important;

            thead {
                border-bottom: none !important;

                th {
                    border-top: none !important;
                    font-size: 12px !important;
                    color: #b2bec3;
                    font-weight: 500;
                    border-bottom: none !important;
                }
            }

            tbody {
                tr:last-child {
                    border-bottom: none !important;
                }

                tr {
                    border-bottom: none !important;
                    td {
                        border-top: none !important;
                        color: #f9fafb;
                        font-size: 14px;

                        .acc-type {
                            color: #b2bec3 !important;
                        }

                        .open-in {
                            font-size: 18px !important;
                            color: #b2bec3;
                            border: none;
                            background-color: transparent;
                            border-radius: 50%;
                            transition: .15s ease-in-out;
                        }

                        .open-in:hover {
                            background-color: #424a4d;
                        }

                        .add-fund {
                            background-color: #00b894;
                            font-size: 14px;
                            color: #f9fafb;
                            height: 40px;
                            border: none;
                            border-radius: 4px;
                            transition: .15s ease-in-out;
                            font-weight: 500;
                        }

                        .add-fund:hover {
                            background-color: #00a181;
                        }
                    }
                }
            }
        }
    }
}
</style>
