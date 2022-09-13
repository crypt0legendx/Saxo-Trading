<template>
<div>
    <AddWalletFunds
        @addWalletFunds='addWalletFunds'
        @fund="fund"
        @return='returnToMainWindow'
        v-if='fundsActive'
    />
    <Transfer
        @transfer='transfer'
        @return='returnToMainWindow'
        v-if='transferActive'
    />

    <Withdraw
        @withdraw='withdraw'
        @return='returnToMainWindow'
        v-if='withdrawActive'
    />

    <CreditCardFund
        @fund='fundFromCreditCard'
        @return='returnToFunds'
        v-if='creditCardFundActive'
    />

    <div class="dashboard-component col-12 col-lg-11 col-md-11" style='max-width: 1024px !important;'
    v-if="!fundsActive && !transferActive && !withdrawActive && !creditCardFundActive">
        <div id='wallet' class="w-100 mb-3 p-3">
            <div id="wallet-info" class="w-100 row p-0 m-0 pt-4 pb-4">
                <div id='w-card-div' class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <div id='wallet-card' class="p-3">
                        <div id='wallet-card-header' class="d-flex justify-content-between w-100">
                            <p class="text-right h3" style="font-family: 'Roboto', Times, serif; font-weight: 200">
                                SaxoTrading
                            </p>
                            <p class="text-right" style="font-size: 13px !important;">
                                <span style='color: #c9c9c9'>Trading wallet</span><br/>
                                <strong class="text-white">{{ wallet.virtualNumber }}</strong>
                            </p>
                        </div>
                        <div id='wallet-card-name' class="font-weight-bold d-flex" style="color: rgba(0,0,0,.6) !important;">
                            <p class="align-self-end">{{ $store.state.user.user_obj.name | loaduserdata }}</p>
                        </div>

                    </div>
                </div>
                <div id="wallet-balance" class="col-lg-7 col-md-7 col-sm-12 col-xs-12 pb-0 pt-0 align-items-end">
                    <span class="label-group" style="font-size: 16px !important; font-weight: 400 !important;">Your wallet</span>
                    <h5 class="balance">{{ $store.state.user.user_obj.balance | currency_locale('USD')}}</h5>
                    <div id='virt-number' class="w-100 d-flex">
                        <p class="w-100 text-left" style='font-weight: 400 !important; font-size: 14px !important;'>Trading wallet number</p>
                        <p id='virt-id' class="w-100 text-right">{{ wallet.virtualNumber }}</p>
                    </div>
                </div>
            </div>
            <div id='wallet-btns' class="d-flex">
                <button class="w-100 m-2" @click="openFunds">
                    <v-icon class="p-0 pr-1">mdi-credit-card-plus-outline</v-icon>
                    Fund
                </button>
                <button class="w-100 m-2" @click="openTransfer">
                    <v-icon class="p-0 pr-1">mdi-swap-horizontal</v-icon>
                    Transfer
                </button>
                <button class="w-100 m-2" @click="openWithdraw" :disabled="($store.state.user.user_obj.balance) != 0 ? false : true">
                    <v-icon class="p-0 pr-1">mdi-credit-card-refund-outline</v-icon>
                    Withdraw
                </button>
            </div>
        </div>

        <TransactionHistory/>
    </div>
</div>
</template>

<script>
import AddWalletFunds from './Wallet/AddWalletFunds.vue';
import Transfer from './Wallet/Transfer.vue';
import Withdraw from './Wallet/Withdraw.vue';
import TransactionHistory from './Wallet/TransactionHistory.vue';

import CreditCardFund from './Wallet/CreditCardFund.vue';

export default {
    name: 'Wallet',
    components: {
        AddWalletFunds,
        Transfer,
        Withdraw,
        TransactionHistory,
        CreditCardFund,
    },
    data() {
        return {
            fundsActive: false,
            transferActive: false,
            withdrawActive: false,
            creditCardFundActive: false,
            wallet: {
                virtualNumber: 'W00778089USD'
            }
        }
    },

    methods: {
        fund(method) {
            if(method === "card") {
                this.fundsActive = false;
                this.creditCardFundActive = true;
            }
        },
        addWalletFunds() {
            alert(1);
        },
        transfer() {
            alert(1);
        },
        withdraw() {
            alert(1);
        },
        openFunds() {
            this.fundsActive = true;
        },
        openTransfer() {
            this.transferActive = true;
        },
        openWithdraw() {
            this.withdrawActive = true;
        },
        openCreditCardFund() {
            this.creditCardFundActive = true;
        },
        returnToMainWindow() {
            this.fundsActive = false;
            this.transferActive = false;
            this.withdrawActive = false;
        },
        fundFromCreditCard() {
            alert("Added money");
        },
        returnToFunds() {
            this.creditCardFundActive = false;
            this.fundsActive = true;
        }
    }
}
</script>

<style lang='scss'>
#wallet {
    #wallet-info {
        min-height: 200px;

        #wallet-card {
            max-width: 295px;
            max-height: 170px;
            height: 170px;
            color: #f9fafb;
            background: linear-gradient(153.16deg,#cd9e7a 19.94%,#100906 110.54%);
            box-shadow: 0 8px 12px rgb(0 0 0 / 24%);
            border-radius: 10px;

            #wallet-card-name {
                height: 90px;
            }
        }

        #wallet-balance {
            .balance {
                font-weight: 400 !important;
                color: #f9fafb;
                font-size: 30px;
            }

            #virt-number {
                margin-top: 80px;
            }

            #virt-number #virt-id  {
                color: #f9fafb;
            }
        }
    }

    #wallet-btns {
        button {
            padding: 8px;
            text-align: center !important;
            border-radius: 50px;
            border: 1px solid #0984e3;
            color: #f9fafb;
            background-color: transparent;
            font-weight: 500;
            text-transform: uppercase;
            transition: .15s ease-in-out;

            .v-icon {
                font-size: 22px;
                color: #0984e3;
            }
        }

        button:hover {
            background-color: #0984e3;

            .v-icon {
                color: #f9fafb;
            }
        }

        button:disabled,
        button:disabled:hover {
            color: #424a4d !important;
            border-color: #424a4d !important;
            background-color: transparent !important;

            .v-icon {
                color: #424a4d !important;
            }
        }
    }
}
#wallet,
#transaction-history {
    border: 1px solid #424a4d;
    border-radius: 10px;
}

@media only screen and (min-width: 1024px) {
    #wallet-info {
        #wallet-card {
            max-width: 315px !important;
            max-height: 190px !important;
            height: 190px !important;

            #wallet-card-name {
                height: 120px !important;
            }
        }

        #wallet-balance {
            #virt-number {
                margin-top: 110px !important;
            }
        }
    }
}

@media only screen and (max-width: 767px) {
    #wallet-info {
        #w-card-div {
            padding: 0 !important;

            #wallet-card {
                margin: 0px auto;
            }
        }

        #wallet-balance {
            margin-top: 30px !important;
            text-align: center !important;

            #virt-number {
                margin-top: 30px !important;
                display: block !important;
                text-align: center !important;

                p {
                    text-align: center !important;
                }
            }
        }
    }
    #wallet-btns {
        display: block !important;

        button {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    }
}
</style>
