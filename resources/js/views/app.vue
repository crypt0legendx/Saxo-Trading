<template>
    <v-app id="app">
        <BrowserNotSupported v-show="$store.state.unsupported_browser"/>
        <MobileDevice v-show="$store.state.mobile_device"/>
        <inst-info-modal/>
        <pos-info-modal/>
        <nlogs-modal/>
        <account-selection-modal/>
        <search-modal/>
        <system-info-modal/>
        <acc-perf-modal/>
        <kyc-modal/>
        <settings-modal/>
        <select-instrument-modal/>
        <bid-ask-modal/>
        <alerts-modal/>
        <edit-position-modal/>
        <router-view></router-view>
    </v-app>

</template>

<style lang="scss">
@import '../scss/_variables.scss';

html,body {
    overflow: auto;
    font-size: 12px;
}

#app {
    width:100%;
    height:100%;
}

.no-bg {
    background:none !important;
}

.pa-0 {
    padding: 0 !important;
}

.--big {
    font-size: 30px !important;
}

.--medium {
    font-size: 24px !important;
}


.w-100 {
    width: 100% !important;
}

.h-100 {
    height: 100% !important;
}


.lnr-section {
    display: flex;
    justify-content: space-between;
    align-items: center;

    .left-section {
        display: flex;
        align-items: center;
    }
}

.absolute-wh-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.select-workspace {
    padding: 0px 5px;
    height: 22px;
    font-size: 12px;
    margin-left:10px;

}
.form-select {
    background:$select_bg !important;
    color: $select_f;
    border:   none;

    &:active, &:focus {
        outline:none !important;
        border:none !important;
    }
}

.no-focus-outline {
    outline:none !important;
    outline-width: 0 !important;
    box-shadow: none !important;
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
}

.no-user-selection {
    -webkit-user-select: none; /* Safari */
    -ms-user-select: none; /* IE 10 and IE 11 */
    user-select: none; /* Standard syntax */
}

.act {
    background: #808080 !important;
    color: black !important;
}

.noselect {
    -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
    -khtml-user-select: none; /* Konqueror HTML */
    -moz-user-select: none; /* Old versions of Firefox */
    -ms-user-select: none; /* Internet Explorer/Edge */
    user-select: none; /* Non-prefixed version, currently
    supported by Chrome, Edge, Opera and Firefox */
}

.b-form-datepicker {
    height: 52px;
    background-color: #282828 !important;
    border-radius: 2px;

    .dropdown-menu {
        background-color: transparent !important;
        border: none !important;

        .b-form-date-calendar {
            background-color: $formsBodyBgColor !important;
            box-shadow: rgb(0 0 0 / 45%) 1px 1px 5px 0px !important;

            .b-calendar-inner {
                .b-calendar-header {
                    background-color: $formsBodyBgColor !important;
                    .form-control {
                        background-color: #282828 !important;
                        font-size: 13px !important;
                        line-height: 1.5 !important;
                        border: none !important;
                        color: $topbar_secondary_fcolor !important;
                        border-radius: 0px !important;
                    }
                }
                .b-calendar-nav {
                    .btn {
                        color: $topbar_secondary_fcolor !important;
                        outline:none !important;
                        outline-width: 0 !important;
                        box-shadow: none !important;
                        -moz-box-shadow: none !important;
                        -webkit-box-shadow: none !important;
                    }

                    .btn:hover,
                    .btn:focus {
                        background: transparent;
                    }

                }

                .b-calendar-grid,
                .b-calendar-grid-body {
                    background-color: #282828 !important;
                    color: $topbar_secondary_fcolor !important;
                    border: 1px solid #404040 !important;
                    outline:none !important;
                    outline-width: 0 !important;
                    box-shadow: none !important;
                    -moz-box-shadow: none !important;
                    -webkit-box-shadow: none !important;
                }

                .b-calendar-grid-body .row .col span {
                    background-color: #282828 !important;
                    color: $topbar_secondary_fcolor !important;
                }

                .b-calendar-grid-help {
                    background-color: #282828 !important;
                    color: $topbar_secondary_fcolor !important;
                }
            }
        }
    }

    .form-control {
        font-size: 13px !important;
        line-height: 3;
        color: $topbar_secondary_fcolor;
        border: none !important;
    }
}

</style>

<script>
import UserData from '../components/Mutators/UserData.js'
import BrowserNotSupported from '../components/Status/BrowserNotSupported.vue'
import MobileDevice from '../components/Status/MobileDevice.vue'
export default {

  components: {
      BrowserNotSupported,
      MobileDevice,
  },

  data () {
    return {
      block: false,
      loaded: false,
    }
  },


    beforeMount() {

        if(localStorage.access_token) {
            let token = JSON.parse(localStorage.access_token)
            this.$http.defaults.headers.post['Content-Type'] ='application/x-www-form-urlencoded';
            this.$http.defaults.params = {}
            this.$http.defaults.params['token'] = token.value

            this.$router.onReady(this.routerReady, null)
        }
    },

    watch: {
      '$route': async function() {
        var route = this.$router.currentRoute.name
        if(route != 'Login' &&
           route != 'Signup' &&
           route != 'GettingStarted')
             if(!this.block && this.loaded)
               await UserData.sync(this)
          }
    },

    mounted() {
      this.loaded = true;
    },


    methods: {
      routerReady(r) {

      },
    },

    created() {
      this.block = this.$browser.performCheck();
    },
}

</script>
