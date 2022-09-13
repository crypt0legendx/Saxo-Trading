<template>
  <b-modal
  id='account-selection-modal'
  ref="account-selection-modal"
  size="sm"
  hide-footer
  no-close-on-backdrop
  centered
  no-fade
  >
  <template v-slot:modal-title>Account Selection</template>
  <template v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
  <template v-slot:default>
    <div class="overlay-dsb"></div>

    <div class="dumbgroup">
    <div class="accountcnt">
      <div @click="chAcc(acc)" v-for="acc in $store.state.user.accounts" class="account">
        <span :class='`badge_ ` + acc.type.toLowerCase()'>{{ acc.sub_type | type}}</span>
        <span style="width:100%;" class="normal">{{`${acc.id} - ${acc.currency}`}}
          <span>{{acc_bals[acc.id]}}</span>
        </span>
      </div>
    </div>

    <div class="bottom-cntrls">
      <button @click="dash">Open New Account</button>
      <button @click="signout">Sign Out</button>
    </div>
  </div>
</template>
</b-modal>
</template>

<style lang='scss'>
@import '~/variables';
@import '~/modal_css';

#account-selection-modal .modal .modal-dialog .modal-content .modal-body {
  padding: 0 !important;
}

#account-selection-modal .modal-body {
  height:300px !important;
}

.dumbgroup {
  display:flex;
  height:100%;
  flex-direction:column;
  justify-content: space-between;
}

.bottom-cntrls {
  display:flex;
  flex-direction:row;

  button {
    background: #3d3d3d;
    border: none;
    font-size: 13px;
    color: #9e9e9e;
    border-radius: 2px;
    font-weight: 500;
    height:35px;
    width:100%;
    margin:2px;

    &:hover {
      background:rgb(71, 71, 71);
    }
  }
}

.overlay-dsb {
  top: 0;
  left: 0;
  position: fixed;
  width: 100vw;
  height: 100vh;
  background: black;
  z-index: -1;
  display: block;
}

.accountcnt {
  display:flex;
  flex-direction:column;
  width: 100%;

  .account {
    padding:12px 15px;
    display:flex;
    flex-direction:row;
    align-items:center;
    cursor:pointer;

    &:hover {
      background: rgba(255,255,255,0.1);
    }

    span {
        margin-left:5px;
        margin-right:5px;

        display:flex;
        justify-content:space-between;
        flex-direction:row;
    }

    .normal {
      font-size:14px;
      color:#afafaf;
    }

    .badge_ {
      width:70px;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 3px;
      padding: 0px 2px;
      color: black;
      height: 18px;
      font-size: 11px;
      background: #3c95d8;
      font-weight: 500;
      margin-right:5px;

      &.demo {
        background: #f17d22 !important;
      }
    }
  }
}

</style>

<script>
import {accSel} from '../../plugins/modals.js';

export default {
  name: 'AccountSelectionModal',
  methods: {
    dash() {
        this.$router.push({ name: 'Dashboard' })
    },

    signout() {
      localStorage.removeItem('access_token');
      localStorage.removeItem('accSelected');
      this.$router.go('/login')
    },

    chAcc(acc) {
      this.$http.post(`/api/user/account/${acc.id}`).then((res) => {
        localStorage.accSelected = {exp: Date.now() + 3600000};
        location.reload()
      })
    },

    acc_balances() {
      this.$store.state.user.accounts.forEach((acc) => {
        this.acc_bals[acc.id] = acc.balance.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
      })
    },

    showModal() {
      this.$refs['account-selection-modal'].show()
    },

    hideModal() {
      this.$refs['account-selection-modal'].hide()
    },
  },

  beforeMount() {
    this.$store.state.user.vue_data.cache.accSelLoaded = true;

    accSel.event.$on('show-account-selection-modal', () => {
      this.showModal();
    })
  },

  data: function() {
    return {
      acc_bals: [],
    }
  },

  computed : {

  },
  filters : {
    type(type) {
      var tt = type.split(' ');
      if(tt.length > 1) {
        return tt[0].toUpperCase()
      } else {
        return type.toUpperCase()
      }
    }
  },
  created() {
    this.$store.subscribe((mutation, state) => {
      if(mutation.type === 'set_user_data'){
        this.acc_balances()
      }
    })
  },
}
</script>
