<template>
  <v-container>
    <v-row class="topbar">
      <div class="lnr-section w-100 h-100">
        <div style="cursor:pointer;" class="left-section">
          <div class="full-sized">
            <div @click="$accModal.show()" class="group-item">
              <span>Balance</span>
              <template v-if="$store.state.done.userInfoSet.values_data">
                <span>
                  {{ $store.getters.get_user_data('balance')  | currency_locale($store.getters.get_user_data('symbol')) }}
                </span>
              </template>
              <template v-else>
                <b-skeleton width="100%"></b-skeleton>
              </template>
            </div>

            <div @click="$accModal.show()" class="group-item">
              <span>Equity</span>
              <template v-if="$store.state.done.userInfoSet.values_data">
                <span>{{ $store.getters.get_user_data('equity')  | currency_locale($store.getters.get_user_data('symbol')) }}</span>
              </template>
              <template v-else>
                <b-skeleton width="100%"></b-skeleton>
              </template>
            </div>

            <div @click="$accModal.show()" class="group-item" style="margin-left:30px;">
              <div style="position:absolute; margin-top:-2px; margin-left: -40px">
                <apexchart type="donut" width="74" :options="chartOptions" :series="series"></apexchart>
              </div>
            </div>

            <div @click="$accModal.show()" class="group-item">
              <span>Margin Used / Free</span>
              <template v-if="$store.state.done.userInfoSet.values_data">
                <span><span style="color:rgb(220, 141, 20) !important">{{ $store.getters.get_user_data('margin').used  | currency_locale($store.getters.get_user_data('symbol')) }} </span> / <span style="color: rgb(40, 127, 187) !important"> {{ $store.getters.get_user_data('margin').free  | currency_locale($store.getters.get_user_data('symbol')) }}</span></span>
              </template>
              <template v-else>
                <b-skeleton width="100%"></b-skeleton>
              </template>
            </div>

            <div @click="$accModal.show()" class="group-item">
              <span>Margin Level</span>
              <template v-if="$store.state.done.userInfoSet.values_data">
                <span>{{ parseFloat($store.getters.get_user_data('margin').level).toFixed(2) }}%</span>
              </template>
              <template v-else>
                <b-skeleton width="100%"></b-skeleton>
              </template>
            </div>

            <div @click="$accModal.show()" class="group-item">
              <span>Total Unrealized P/L</span>
              <template v-if="$store.state.done.userInfoSet.values_data">
                <span class="pl" style="font-size: 1.5rem; line-height 20px !important;" :style="$store.getters.get_user_data('pl') | unrealized_color" >{{ $store.getters.get_user_data('pl') | currency_locale($store.getters.get_user_data('symbol')) }}</span>
              </template>
              <template v-else>
                <b-skeleton width="100%"></b-skeleton>
              </template>
            </div>

            <div class="group-item" v-show="$store.state.done.userInfoSet.values_data && !date.includes('NaN') && !time.includes('NaN')">
              <span>{{date | nosingledigit('/')}}</span>
              <span>{{time | nosingledigit(':')}}</span>
            </div>

            <div @click="$searchmodal.show()" class="group-item search_stocks_top">
              <span><v-icon>mdi-magnify</v-icon></span>
            </div>

          </div>

          <div class="compact">
            <b-dropdown id="dropdown-1" class="m-md-2">
              <template slot="button-content"><v-icon>mdi-dots-vertical</v-icon></template>
              <b-dropdown-item>Balance : {{ $store.getters.get_user_data('balance')  | currency_locale($store.getters.get_user_data('symbol')) }}</b-dropdown-item>
              <b-dropdown-item>Equity : {{ $store.getters.get_user_data('equity')  | currency_locale($store.getters.get_user_data('symbol')) }}</b-dropdown-item>
              <b-dropdown-item>Margin Used / Free : {{ $store.getters.get_user_data('margin').used  | currency_locale($store.getters.get_user_data('symbol')) }} / {{ $store.getters.get_user_data('margin').free  | currency_locale($store.getters.get_user_data('symbol')) }}</b-dropdown-item>
              <b-dropdown-item>Margin Level : {{ parseFloat($store.getters.get_user_data('margin').level).toFixed(2) }}%</b-dropdown-item>
              <b-dropdown-item>Total Unrealized P/L : {{ $store.getters.get_user_data('pl') | currency_locale($store.getters.get_user_data('symbol')) }}</b-dropdown-item>
              <b-dropdown-item>{{date | nosingledigit('/')}} : {{time | nosingledigit(':')}}</b-dropdown-item>
            </b-dropdown>
          </div>

        </div>

        <div class="right-section" :style="!data_loaded ? 'width:20%' : ''">
          <template v-if="data_loaded">
            <b-dropdown id="dropdown-1" :text="$store.getters.get_active_workspace_object.name" class="ddown m-md-2">
              <b-dropdown-item @click="setWorkspace(0)"><v-icon>mdi-page-next-outline</v-icon> Default Workspace</b-dropdown-item>
              <b-dropdown-item @click="setWorkspace(1)"><v-icon>mdi-page-next-outline</v-icon> Advanced</b-dropdown-item>
              <b-dropdown-item @click="setWorkspace(2)"><v-icon>mdi-page-next-outline</v-icon> Chart Grid</b-dropdown-item>
            </b-dropdown>

            <b-dropdown id="dropdown-1" :html="`<span class='acc_type ` + $store.state.user.type.toLowerCase() + `'>` + $store.state.user.sub_type.toUpperCase() + `</span>` + $store.getters.get_user_data('id') + ' - ' +  $store.getters.get_user_data('symbol')" class="ddown m-md-2">
              <template v-if="$store.state.user.accounts.length > 0">
                <template v-for="acc in $store.state.user.accounts">
                  <template v-if="acc.active == false">
                    <b-dropdown-item @click="accountChange(acc.id)">
                      <div class="d-flex flex-row justify-content-between align-items-center">
                        <span style="font-size:10px;" :class='`acc_type ` + acc.type.toLowerCase()'>{{acc.sub_type.toUpperCase()}}</span>

                        <div class="d-flex flex-column text-right">
                          <span style="font-size:12px">{{acc.id}} - {{acc.currency}}</span>
                          <span style="font-size:13px">{{ acc_bals[acc.id] }}</span>
                        </div>
                      </div>
                    </b-dropdown-item>
                    <b-dropdown-divider></b-dropdown-divider>
                </template>
              </template>


              </template>
              <b-dropdown-item @click="$kycModal.show()"><v-icon>mdi-check</v-icon> Verification</b-dropdown-item>
              <b-dropdown-item @click="$accModal.show()"><v-icon>mdi-chart-line</v-icon> Account Performance</b-dropdown-item>
              <b-dropdown-item><v-icon>mdi-credit-card-multiple-outline</v-icon> Payments</b-dropdown-item>
              <b-dropdown-item @click="reset_settings()"><v-icon>mdi-restart</v-icon> Reset Settings</b-dropdown-item>
              <b-dropdown-divider></b-dropdown-divider>
              <b-dropdown-item @click="create_account"><v-icon>mdi-plus-circle-outline</v-icon> Create new account</b-dropdown-item>
              <b-dropdown-divider></b-dropdown-divider>
              <b-dropdown-item @click="$sysInfo.show()"><v-icon>mdi-desktop-mac</v-icon> System Information</b-dropdown-item>
              <b-dropdown-item><v-icon>mdi-form-select</v-icon> Privacy Policy</b-dropdown-item>
              <b-dropdown-item><v-icon>mdi-bug</v-icon> Crash Reporting</b-dropdown-item>
              <b-dropdown-item><v-icon>mdi-newspaper-variant-outline</v-icon> What's new</b-dropdown-item>
              <b-dropdown-item><v-icon>mdi-help-circle-outline</v-icon> Help</b-dropdown-item>
              <b-dropdown-divider></b-dropdown-divider>
              <b-dropdown-item v-on:click="signout"><v-icon>mdi-logout</v-icon> Sign Out</b-dropdown-item>
              <b-dropdown-divider></b-dropdown-divider>
            </b-dropdown>
          </template>
          <template v-else>
            <b-skeleton></b-skeleton>
          </template>
        </div>

      </div>
    </v-row>
  </v-container>
</template>

<style lang="scss">
@import '../scss/_variables.scss';

.acc_type {
    line-height: 15px;
    padding: 0px 6px;
    margin-right: 10px;
    border-radius: 5px;
    color: black !important;
    font-family: "Roboto";
    font-size: 11px;
    font-weight: 500;
    background: #3c95d8;

  &.demo {
    background : #f17d22 !important;
  }

  &.mt4 {
    background: #4CAF50 !important;
  }

}

.search_stocks_top {
  display:flex;
  justify-content: center;
  padding: 0 5px;
  cursor:pointer;

  i {
    color: $topbar_fcolor;
    font-size:24px;
  }

  &:hover {
    background: rgba(255,255,255,0.1) !important;
  }
}

.b-skeleton-text {
  height: .8rem !important;
  margin-top: 5px !important;
}

.b-skeleton {
  background-color: rgb(84 84 84) !important;
}

@media screen and (max-width:1185px) {
  .full-sized {
    display:none !important;
  }

  .compact {
    display:flex !important;
  }
}

.compact {
  display:none;
}
.full-sized {
  display:flex;
}

.topbar {
  font-family: 'Roboto', sans-serif;
  position:fixed;
  width:100%;
  height:40px;
  position: fixed;
  left: 55px;

  .btn-secondary:not(:disabled):not(.disabled):active, .btn-secondary:not(:disabled):not(.disabled).active, .show > .btn-secondary.dropdown-toggle {
    /*border:1px solid $bg !important;*/
    border-radius: 0px !important;
  }

  .dropdown-menu {
    padding: 0;

    hr {
      border:1px solid transparent;
      margin: 0;
    }

    a {
      font-size:13px;

      i {
        font-size:15px;
      }
    }

    * {
      font-size:14px;
    }
  }


  .dropdown-toggle {
    border-radius: 0px !important;
    font-size: 1.1rem;
    height: 30px;
    background: inherit;
    border: none;
    display: flex;
    align-items: center;

    /*&:active {
      border:1px solid $bg !important;
      background: $bg !important;
      border-radius:2px;
    }


    &:hover {
      background: $bg !important;
      border-radius:2px;
    } */

    outline:none !important;
    outline-width: 0 !important;
    box-shadow: none !important;
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;

    &:after {
      margin-left: 0.35em;
      margin-top: 0.23em;
    }
  }

  .group-item {
    display:flex;
    flex-direction:column;
    margin:10px;

    span {
      font-family: 'Roboto', sans-serif;
    }

    span:first-of-type {
      font-size:12px;
      color:$topbar_label_fcolor;
    }

    span:last-of-type {
      font-size:14px;
      color:$topbar_fcolor;
      margin: -3px 0;
    }

  }
}

.lnr-section {
  padding-left:5px;
  padding-right:45px;
}


</style>

<script>
export default {
  name : "TopBar",

  components: {

  },

  computed : {

  },

  filters: {
      formatCurrency(val, currency) {
        return val.toLocaleString('en-US', { style: 'currency', currency: currency })
      },

      unrealized_color(pl) {
        if(pl < 0)
          return "color: #d81720 !important"

        if(pl == 0)
          return "color: gray !important"

        if(pl > 0)
          return "color: #19a337 !important"
      }
  },

  created() {
    let th = this

    console.log('mutation');
    this.$store.subscribe((mutation, state) => {
      
      if(mutation.type === 'set_user_data'){
        this.data_loaded = true;
        this.acc_balances()


      }

      if(mutation.type === 'calcDone'){
        this.updateChart()
      }
    })

  },


  mounted() {
    console.log('topbar mounted');
    this.updateNow()
    setInterval(this.updateNow.bind(this) , 1000)

  },

  methods: {
    acc_balances() {
      this.$store.state.user.accounts.forEach((acc) => {
        this.acc_bals[acc.id] = acc.balance.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
      })
    },

    accountChange(idx) {
      this.$http.post(`/api/user/account/${idx}`).then((res) => {
        localStorage.accSelected = {exp: Date.now() + 3600000};
        location.reload()
      })
    },

    create_account() {
      this.$router.push({ name: 'Dashboard' })
    },

    reset_settings () {
      this.$store.state.user.sound = 'Enabled'
      this.$store.state.user.locale = 'en-GB'
      this.$store.state.time_zone = 'UTC'
      this.$store.dispatch('reset_workspace')

       this.$dtoast.pop({
                  preset: "info",
                  heading: `Settings Updated`,
                  content:  `Your settings were resetted.`,
                 })
    },


    signout() {
      localStorage.removeItem('access_token');
      localStorage.removeItem('accSelected');
      this.$router.go('/login')
    },
    updateNow() {
      var date = new Date();
      var dateR = new Date(date.getTime() + date.getTimezoneOffset() * 60000 + (3600000 * this.$store.getters.get_user_data('time_zone')))

      this.date = dateR.getDate() + '/' + (dateR.getMonth() + 1) + '/' + dateR.getFullYear();
      this.time = dateR.getHours() + ':' + dateR.getMinutes() + ':' +   dateR.getSeconds();

      this.$store.state.current_time = this.time;
      this.$store.state.current_date = this.date;

    },

    setWorkspace(wid) {
      this.$store.commit('set_active_workspace', wid)
    },

    updateChart() {
      let free = this.$store.getters.get_user_data('margin').free
      let used = this.$store.getters.get_user_data('margin').used


      var used_ = (used / (free + used)) * 100
      var free_ = (free / (free + used)) * 100


      if(free_ == 0)
        used_ = 100;

      if(used_ == 0)
        free_ = 100;


      if(used_ != NaN && free_ != NaN
          && used_ >= 0 && free_ >= 0)
        this.series = [used_,free_]
    },
  },



  data: function() {
    return {
      data_loaded: false,
      date: "",
      time : "",

      acc_bals: [],

      series: [0,100],
      chartOptions: {
        fill: {
          colors: ['rgb(220, 141, 20)', 'rgb(40, 127, 187)']
        },
        legend: {
          show: false
        },
        tooltip : {
          enabled: false
        },
        stroke: {
          show: false,
          width:0
        },
        dataLabels: {
          enabled: false,
        },
        plotOptions: {

          pie: {
            customScale: 1,
            donut: {
              size: '75%'
            }
          }
        }
      },
    }
  },


}
</script>
