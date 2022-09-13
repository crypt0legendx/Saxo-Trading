<template>
  <div>
    <CreateAccount
    @createAccount='createAccount'
    @return='returnToMainWindow'
    :headerLabel="active === 0 ? 'Demo' : 'Trade'"
    v-if="createAccountActive"
    />

    <EditLeverage
    @editLeverage='editLeverage'
    @return='returnToMainWindow'
    :headerLabel="active === 0 ? 'Demo' : 'Trade'"
    :accData="tableData[active === 0 ? 'demos' : 'reals']"
    :accIndex='selectedAccIndex'
    v-if="leverageActive"
    />

    <AddFunds
    @addFunds='addFunds'
    @return='returnToMainWindow'
    :headerLabel="active === 0 ? 'Demo' : 'Trade'"
    :account="tableData[active === 0 ? 'demos' : 'reals'][selectedAccIndex]"
    v-if="fundsActive"
    />

    <div v-if="!leverageActive && !fundsActive && !createAccountActive" class="dashboard-component p-2 col-12 col-lg-11 col-md-11" style='max-width: 1024px !important;'>
      <b-nav tabs :fill='fill'>
        <b-nav-item
        :active='active === 0'
        :style="{ width: !fill ? '220px' : ''}"
        @click="setActive(0)"
        >Demo Accounts</b-nav-item>

        <b-nav-item
        :active='active === 1'
        :style="{ width: !fill ? '220px' : ''}"
        @click="setActive(1)"
        >Trading Accounts</b-nav-item>

        <button id='create-account' class="ml-auto pr-4 pl-4" @click="openCreateAccount" v-if='!fill'>
          <v-icon>mdi-plus</v-icon>Create new account
        </button>
      </b-nav>

      <div id='account-tables'>
        <div class="w-100 p-0 m-0">

          <div class="w-100 text-center" v-if="(active === 0 && $store.getters.get_accounts_by_prop({prop: 'type', val: 'demo'}).length === 0) || (active === 1 && $store.getters.get_accounts_by_prop({prop: 'type', val: 'real'}).length === 0)">
            <h6 class="pt-5 pb-5">You currently have no {{ active === 0 ? 'Demo' : 'Trade' }} Accounts</h6>
          </div>

          <b-table
          borderless
          :fields="tableData.fields"
          :items="tableData.demos"
          v-if="sw > 990 && active === 0 && $store.getters.get_accounts_by_prop({prop: 'type', val: 'demo'}).length > 0">
          <template #cell(uuid)='data'>
            <span class='acc-type'>{{ data.value.sub_type }}</span>{{' / '}}<br/>
            <span>{{data.value.type.toUpperCase()}} - {{ data.value.id }}</span>
          </template>

          <template #cell(actions)='data'>
            <span v-on:click="loginAccount(data.index, 'demo')" title="Log in with current account" class="w-100 d-flex" style="cursor:pointer; font-size:24px; color: #dddd">
              <v-icon >mdi-google-chrome</v-icon>
            </span>
          </template>

          <template #cell(leverage)='data'>
            <span>{{ data.value }} <v-icon @click="openEditLeverage(data.index)" class=" open-in">mdi-pencil</v-icon></span>
          </template>

          <template #cell(balance)="data">
            {{ data.value }}
          </template>

          <template #cell(fmargin)="data">
            <strong>{{ data.value }}</strong>
          </template>

          <template #cell(addfunds)='data'>
            <button @click="openAddFunds(data.index)" class="w-100 add-fund">Add Funds</button>
          </template>
        </b-table>

        <b-table
        stacked
        :fields="tableData.fields"
        :items="tableData.demos"
        v-if="sw <= 990 && active === 0 && $store.getters.get_accounts_by_prop({prop: 'type', val: 'demo'}).length > 0">
        <template #cell(uuid)='data'>
          <span class='acc-type'>{{ data.value.sub_type }}</span>{{' / '}}<br/>
          <span>{{data.value.type.toUpperCase()}} - {{ data.value.id }}</span>
        </template>

        <template #cell(actions)='data'>
        <span v-on:click="loginAccount(data.index, 'demo')" title="Log in with current account" class="w-100 d-flex" style="cursor:pointer; font-size:24px; color: #dddd">
          <v-icon >mdi-google-chrome</v-icon>
        </span>
        </template>

        <template #cell(leverage)='data'>
          <span>{{ data.value }} <v-icon @click="openEditLeverage(data.index)" class=" open-in">mdi-pencil</v-icon></span>
        </template>

        <template #cell(balance)="data">
          {{ data.value }}
        </template>

        <template #cell(fmargin)="data">
          <strong>{{`${data.value} ${tableData.demos[data.index].currency}`}}</strong>
        </template>

        <template #cell(addfunds)='data'>
          <button @click="openAddFunds(data.index)" class="w-100 add-fund">Add Funds</button>
        </template>
      </b-table>

      <b-table
      borderless
      :fields="tableData.fields"
      :items="tableData.reals"
      v-if="sw > 990 && active === 1 && $store.getters.get_accounts_by_prop({prop: 'type', val: 'real'}).length > 0">
      <template #cell(uuid)='data'>
        <span class='acc-type'>{{ data.value.sub_type }}</span>{{' / '}}<br/>
        <span>{{data.value.type.toUpperCase()}} - {{ data.value.id }}</span>
      </template>

      <template #cell(actions)='data'>
      <span v-on:click="loginAccount(data.index, 'real')" title="Log in with current account" class="w-100 d-flex" style="cursor:pointer; font-size:24px; color: #dddd">
        <v-icon >mdi-google-chrome</v-icon>
      </span>
    </template>

      <template #cell(leverage)='data'>
        <span>{{ data.value }} <v-icon @click="openEditLeverage(data.index)" class=" open-in">mdi-pencil</v-icon></span>
      </template>

      <template #cell(balance)="data">
        {{ data.value }}
      </template>

      <template #cell(fmargin)="data">
        <strong>{{ data.value }}</strong>
      </template>

      <template #cell(addfunds)='data'>
        <button @click="openAddFunds(data.index)" class="w-100 add-fund">Add Funds</button>
      </template>
    </b-table>

    <b-table
    stacked
    :fields="tableData.fields"
    :items="tableData.reals"
    v-if="sw <= 990 && active === 1 && $store.getters.get_accounts_by_prop({prop: 'type', val: 'real'}).length > 0">
    <template #cell(uuid)='data'>
      <span class='acc-type'>{{ data.value.sub_type }}</span>{{' / '}}<br/>
      <span>{{data.value.type.toUpperCase()}} - {{ data.value.id }}</span>
    </template>

    <template #cell(actions)='data'>
    <span v-on:click="loginAccount(data.index, 'real')" title="Log in with current account" class="w-100 d-flex" style="cursor:pointer; font-size:24px; color: #dddd">
      <v-icon >mdi-google-chrome</v-icon>
    </span>
  </template>

    <template #cell(leverage)='data'>
      <span>{{ data.value }} <v-icon @click="openEditLeverage(data.index)" class=" open-in">mdi-pencil</v-icon></span>
    </template>

    <template #cell(balance)="data">
      {{ data.value }}
    </template>

    <template #cell(fmargin)="data">
      <strong>{{`${data.value} ${tableData.trading[data.index].currency}`}}</strong>
    </template>

    <template #cell(addfunds)='data'>
      <button @click="openAddFunds(data.index)" class="w-100 add-fund">Add Funds</button>
    </template>
  </b-table>
</div>
</div>

<div class="w-100 d-flex">
  <button id='create-account' class="mx-auto mb-2 pr-4 pl-4" @click="openCreateAccount" v-if='fill'>
    <v-icon>mdi-plus</v-icon>Create new account
  </button>
</div>
</div>
</div>
</template>

<script>
import EditLeverage from './Accounts/EditLeverage.vue';
import AddFunds from './Accounts/AddFunds.vue';
import CreateAccount from './Accounts/CreateAccount.vue';

export default {
  name: 'Accounts',
  components: {
    EditLeverage,
    AddFunds,
    CreateAccount,
  },
  data() {
    return {
      active: 0,
      selectedAccIndex: undefined,
      createAccountActive: false,
      fundsActive: false,
      leverageActive: false,
      fill: false,
      sw: window.innerWidth,
      tableData: {
        fields: [
          { key: 'uuid', label: 'ACCOUNT#' },
          { key: 'actions', label: 'ACTIONS' },
          { key: 'status', label: 'STATUS' },
          { key: 'leverage', label: 'LEVERAGE' },
          { key: 'balance', label: 'BALANCE' },
          { key: 'fmargin', label: 'FREE MARGIN' },
          { key: 'currency', label: 'CURRENCY' },
          { key: 'addfunds', label: 'FUNDS'}
        ],

        demos : [],
        reals : [],
      },
    }
  },



  created() {
    this.accs_prep()
  },

  methods: {
    loginAccount(id, type) {
        var account = null;

        if(type == 'demo')
          account = this.tableData.demos[id]

        if(type == 'real')
          account = this.tableData.reals[id]

        if(account == null) return

        id = account.uuid.id


        this.$http.post(`/api/user/account/${id}`).then((res) => {
          if(res.data == undefined) return;
          location.href = "/"

        }).catch(error => {
          this.$sound.play('error.mp3')
          this.$dtoast.pop({
                preset: "error",
                heading: `Unexpected Error`,
                content:  `Something went wrong, please, try again`,
          })
        })
    },

    async accs_prep() {
      var accs = this.$store.state.user.accounts
      var fin = []
      var fin_demo = []

      let th = this


      accs.forEach(async (acc) => {
        var isdemo = false

        if(acc.type.toLowerCase() == 'demo')
          isdemo = true;
        else
          isdemo = false;

        var bal = acc.balance; // usd

        const res = await this.$store.dispatch('manualConvert', {from: 'USD', to: acc.currency, load: acc.balance}).then(res)

        bal = res.toLocaleString('en-US', { style: 'currency', currency: acc.currency })

        var temp = {
          uuid: {type: acc.type.toUpperCase(), sub_type: acc.sub_type.toUpperCase(), id: acc.id},
          status: acc.active == 1 ? 'Active' : 'Disabled',
          leverage: '1:' + acc.leverage,
          balance: bal,
          fmargin: 0, //todo
          currency: acc.currency,
          addfunds: 100, //todo
        }

        if(isdemo == true)
          fin_demo.push(temp)
        else
          fin.push(temp)
      })

      this.tableData.demos = fin_demo
      this.tableData.reals = fin
    },

    setActive(i) {
      this.active = i;
    },
    returnToMainWindow() {
      this.selectedAccIndex = undefined;
      this.createAccountActive = false;
      this.leverageActive = false;
      this.fundsActive = false;
    },
    createAccount(accountObj) {
      let th = this
      this.$http.post(`/api/user/create/account`, accountObj).then((res) => {
        if(res.data.id != undefined) {
          th.$sound.play('success.mp3')
          this.$dtoast.pop({
              preset: "success",
              heading: `Success!`,
              content:  `You successfully created a new account.`,
          })

          this.$store.state.user.accounts.push(res.data)
          this.accs_prep()
        }
      }).catch(error => {
        th.$sound.play('error.mp3')
          this.$dtoast.pop({
                preset: "error",
                heading: `Account Error`,
                content:  `We were unable to create you a new account`,
          })
      })
    },
    editLeverage(index, newLeverage) {
      let th = this
      var accountObj = {
        id: index,
        value: newLeverage,
      }
      this.$http.post(`/api/user/update/account/leverage`, accountObj).then((res) => {
        if(res.data.id != undefined) {
          th.$sound.play('success.mp3')
            this.$dtoast.pop({
                preset: "success",
                heading: `Success!`,
                content:  `Leverage updated successfully`,
          })

          this.$store.state.user.accounts[this.$store.state.user.accounts.findIndex(a => a.id === index)].leverage = newLeverage
          this.accs_prep()
        }
      }).catch(error => {
        th.$sound.play('error.mp3')
          this.$dtoast.pop({
                preset: "error",
                heading: `Account Error`,
                content:  `We were unable to update your leverage`,
          })
      })
    },
    addFunds(obj) {
      var acc = obj.account.uuid.id
      var funds = obj.funds

      let th = this

      var accountObj = {
          value: funds
      }

      this.$http.post(`/api/user/accounts/${acc}/addfunds`, accountObj).then((res) => {
        if(res.data.id != undefined) {
          th.$sound.play('success.mp3')
            this.$dtoast.pop({
                preset: "success",
                heading: `Success!`,
                content:  `Successfully added funds to your account.`,
          })

          //nope dont ever think of using this //this.tableData[res.data.type.toLowerCase() == 'demo' ? 'demos' : 'reals'][this.tableData[res.data.type.toLowerCase() == 'demo' ? 'demos' : 'reals'].findIndex(acc_ => acc_.uuid.id === acc)].balance += funds;

          this.$store.state.user.accounts[this.$store.state.user.accounts.findIndex(a => a.id === acc)].balance += funds
          this.accs_prep()

        }
      }).catch(error => {
        console.log(error)
        th.$sound.play('error.mp3')
          this.$dtoast.pop({
                preset: "error",
                heading: `Funding Error`,
                content:  `We were unable to add funds to your account`,
          })
      })

      //this.state.user.accounts[this.state.user.accounts.findIndex(x => x.id === '45');
    },
    openCreateAccount() {
      this.createAccountActive = true;
    },
    openEditLeverage(accountIndex) {
      this.selectedAccIndex = accountIndex;
      this.leverageActive = true;
    },
    openAddFunds(accountIndex) {
      this.selectedAccIndex = accountIndex;
      this.fundsActive = true;
    },
    onResize(e) {
      this.fill = window.innerWidth < 1024 ? true : false;
      this.sw = window.innerWidth;
    }
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

#create-account {
  height: 40px;
  margin-top: 10px;
  background-color: transparent;
  font-size: 14px;
  color: #00b894;
  border: none;
  transition: .15s ease-in-out;
  font-weight: 500;
}

#create-account:hover {
  background-color: rgba(#00b894, .05);

}

.nav-tabs {
  border-bottom: none !important;

  .nav-item {
    .nav-link {
      font-size: 14px;
      padding: 20px 0px !important;
      background: transparent !important;
      border-color: transparent;
      border-bottom-color: #424a4d;
      border-radius: 0px !important;
      text-align: center;
      color: #b2bec3;
      font-weight: 500 !important;
    }

    .nav-link:hover {
      color: #f9fafb;
    }

    .nav-link.active {
      border-color: #424a4d !important;
      color: #f9fafb !important;
      border-bottom-color: #2c3235 !important;
      background-color: #2c3235 !important;
    }
  }
}

#account-tables {
  border: 1px solid #424a4d;
  background-color: #2c3235 !important;
  border-bottom-right-radius: 0px;
  border-bottom-left-radius: 0px;

  .b-table {

    thead {
      border-bottom: 1px solid #424a4d;

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
        td {
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

@media only screen and (max-width: 990px) {
  .table {
    th, td {
      border-top: 1px solid #424a4d;
    }

    tr {
      border-bottom-width: 2px;
    }

    tr > td:first-child {
      border-top: none !important;
    }

    td::before {
      text-align: left !important;
      font-weight: 500 !important;
      font-size: 12px !important;
    }

    td {
      div {
        text-align: right;
        width: 100%;

        .acc-type {
          color: #b2bec3 !important;
        }
      }

      .add-fund {
        max-width: 120px !important;
        margin-top: 0px !important;
        height: 30px !important;
      }
    }
  }
}


@media only screen and (min-width: 769px) {

}
</style>
