<template>
    <div id='dash-main' class="d-flex w-100 p-0 m-0">
        <div id='dash-nav' :class="navOpen ? 'n-open' : 'n-closed'" class='position-fixed'>
            <div :style="{ display: navOpen ? 'none' : 'block' }" class="h-100">
                <div class="d-block text-center mt-2" style="margin-bottom: 60px;">
                    <v-icon
                        id='toggle-nav'
                        @click="toggleNav"
                    >mdi-chevron-{{ navOpen ? 'left' : 'right' }}
                    </v-icon>

                    <v-icon
                        id='change-theme'
                        @click="$emit('changeTheme')"
                    >mdi-{{ isLight ? 'moon-waning-crescent' : 'weather-sunny' }}</v-icon>
                </div>

                <v-icon
                @click="$emit('returnToTrading')"
                class="dash-nav-btn"
                title='SaxoTrading'
                style="width: fit-content !important;"
                >mdi-reply</v-icon>

                <v-icon
                @click="$emit('setActiveNav', 0)"
                class="dash-nav-btn text-left"
                :class="activeNavBtn === 0 ? 'active-nav-btn' : ''"
                title="Account Wallet">
                mdi-account-cash-outline
                </v-icon>

                <v-icon
                    @click="$emit('setActiveNav', 1)"
                    class="dash-nav-btn text-left"
                    :class="activeNavBtn === 1 ? 'active-nav-btn' : ''"
                    title="Accounts">
                mdi-account-group-outline
                </v-icon>

                <b-dropdown id="dropdown-profile" ref="dropdownProfile" dropright no-caret :class="parseInt(activeNavBtn) === 2 ? 'active-dd' : ''">
                    <template
                        v-slot:button-content
                        class="no-focus-outline" title='My profile'>
                        <v-icon>mdi-account-circle-outline</v-icon>
                    </template>
                    <b-dropdown-header id="dropdown-profile-header">My profile</b-dropdown-header>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.1)" :class='activeNavBtn === 2.1 ? "active-item" : " "'>Personal details</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.2)"  :class='activeNavBtn === 2.2 ? "active-item" : ""'>Contact details</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.3)" :class='activeNavBtn === 2.3 ? "active-item" : ""'>Upload documents</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.4)"  :class='activeNavBtn === 2.4 ? "active-item" : ""'>Manage bank details</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.5)" :class='activeNavBtn === 2.5 ? "active-item" : ""'>Manage TAX IDs</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.6)" :class='activeNavBtn === 2.6 ? "active-item" : ""'>Legal documentation</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.7)" :class='activeNavBtn === 2.7 ? "active-item" : ""'>Close account</b-dropdown-item>
                </b-dropdown>

                <b-dropdown id="dropdown-settings" ref="dropdownSettings" dropright no-caret :class="parseInt(activeNavBtn) === 3 ? 'active-dd' : ''">
                    <template
                        v-slot:button-content
                        class="no-focus-outline" title='Settings'>
                        <v-icon>mdi-cog</v-icon>
                    </template>
                    <b-dropdown-header id="dropdown-header-1">Settings</b-dropdown-header>
                    <b-dropdown-item @click="$emit('setActiveNav', 3.1)" :class='activeNavBtn === 3.1 ? "active-item" : " "'>Password</b-dropdown-item>
                    <!--<b-dropdown-item @click="$emit('setActiveNav', 3.2)">Language settings</b-dropdown-item>-->
                    <!--<b-dropdown-item @click="$emit('setActiveNav', 3.3)">Email settings</b-dropdown-item>-->
                    <b-dropdown-item @click="$emit('setActiveNav', 3.2)" :class='activeNavBtn === 3.2 ? "active-item" : " "'>Authentication</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 3.2)" :class='activeNavBtn === 3.2 ? "active-item" : " "'>Language</b-dropdown-item>
                </b-dropdown>

                <v-icon
                    @click="$emit('logOut')"
                    class="dash-nav-btn fixed-bottom"
                    title='Logout'
                    style="width: fit-content !important;"
                    >mdi-logout</v-icon>
            </div>
            <div :style="{ display: !navOpen ? 'none' : 'block' }" class="h-100">
                <div class="d-flex mt-2" style="margin-bottom: 102px;">
                    <v-icon
                        id='change-theme'
                        class='ml-auto'
                        @click="$emit('changeTheme')"
                    >mdi-{{ isLight ? 'moon-waning-crescent' : 'weather-sunny' }}</v-icon>

                    <v-icon
                        id='toggle-nav'
                        class='ml-auto'
                        style="margin: 0px !important;"
                        @click="toggleNav"
                    >mdi-chevron-{{ navOpen ? 'left' : 'right' }}
                    </v-icon>
                </div>

                 <div
                    @click="$emit('returnToTrading')"
                    class="nav-open-btn d-flex">
                    <v-icon class="dash-nav-btn">
                        mdi-reply
                    </v-icon>
                    <span>SaxoTrading</span>
                </div>

                <div
                    @click="$emit('setActiveNav', 0)"
                    class="nav-open-btn d-flex"
                    :class="activeNavBtn === 0 ? 'active-nav-btn text-left' : ''">
                    <v-icon class="dash-nav-btn">
                        mdi-account-cash-outline
                    </v-icon>
                    <span>Wallet</span>
                </div>

                <div
                    @click="$emit('setActiveNav', 1)"
                    class="nav-open-btn d-flex"
                    :class="activeNavBtn === 1 ? 'active-nav-btn text-left' : ''">
                    <v-icon class="dash-nav-btn">
                        mdi-account-group-outline
                    </v-icon>
                    <span>Accounts</span>
                </div>

                <b-dropdown
                    id="dropdown-profile"
                    ref="dropdownProfile"
                    dropright
                    no-caret
                    class="w-100"
                    :class="parseInt(activeNavBtn) === 2 ? 'active-dd' : ''">
                    <template
                        v-slot:button-content
                        class="no-focus-outline" title='My profile'>
                        <v-icon>mdi-account-circle-outline</v-icon>
                        <span class="">My profile</span>
                    </template>
                    <b-dropdown-header id="dropdown-profile-header">My profile</b-dropdown-header>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.1)" :class='activeNavBtn === 2.1 ? "active-item" : " "'>Personal details</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.2)"  :class='activeNavBtn === 2.2 ? "active-item" : ""'>Contact details</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.3)" :class='activeNavBtn === 2.3 ? "active-item" : ""'>Upload documents</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.4)"  :class='activeNavBtn === 2.4 ? "active-item" : ""'>Manage bank details</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.5)" :class='activeNavBtn === 2.5 ? "active-item" : ""'>Manage TAX IDs</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.6)" :class='activeNavBtn === 2.6 ? "active-item" : ""'>Legal documentation</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 2.7)" :class='activeNavBtn === 2.7 ? "active-item" : ""'>Close account</b-dropdown-item>
                </b-dropdown>

                <b-dropdown
                    id="dropdown-settings"
                    ref="dropdownSettings"
                    dropright
                    no-caret
                    class="w-100"
                    :class="parseInt(activeNavBtn) === 3 ? 'active-dd' : ''">
                    <template
                        v-slot:button-content
                        class="no-focus-outline" title='Settings'>
                        <v-icon class=''>mdi-cog</v-icon>
                        <span class="">Settings</span>
                    </template>
                    <b-dropdown-header id="dropdown-header-1">Settings</b-dropdown-header>
                    <b-dropdown-item @click="$emit('setActiveNav', 3.1)" :class='activeNavBtn === 3.1 ? "active-item" : " "'>Password</b-dropdown-item>
                    <!--<b-dropdown-item @click="$emit('setActiveNav', 3.2)">Language settings</b-dropdown-item>-->
                    <!--<b-dropdown-item @click="$emit('setActiveNav', 3.3)">Email settings</b-dropdown-item>-->
                    <b-dropdown-item @click="$emit('setActiveNav', 3.2)" :class='activeNavBtn === 3.2 ? "active-item" : " "'>Authentication</b-dropdown-item>
                    <b-dropdown-item @click="$emit('setActiveNav', 3.3)" :class='activeNavBtn === 3.2 ? "active-item" : " "'>Language</b-dropdown-item>
                </b-dropdown>

                <div
                    id='log-out-open'
                    @click="$emit('logOut')"
                    class="nav-open-btn d-flex fixed-bottom">
                    <v-icon class="dash-nav-btn">
                        mdi-logout
                    </v-icon>
                    <span>Logout</span>
                </div>
            </div>
        </div>
        <div id='dash-window' class="w-100">
            <div id='dash-window-content' class="" :style="{'margin-left': navOpen ? '220px' : '60px'}">
                <Wallet v-if="activeNavBtn === 0"/>
                <Accounts v-if="activeNavBtn === 1"/>
                <PersonalDetails v-if="activeNavBtn === 2.1"/>
                <ContactDetails v-if='activeNavBtn === 2.2'/>
                <UploadDocuments v-if='activeNavBtn === 2.3'/>
                <ManageBankDetails v-else-if="activeNavBtn === 2.4"/>
                <TaxIdentification v-if='activeNavBtn === 2.5'/>
                <LegalDocumentation v-if='activeNavBtn === 2.6'/>
                <CloseAccount @setActiveNav="$emit('setActiveNav', 0)" v-if='activeNavBtn === 2.7'/>
                <PasswordSettings v-if="activeNavBtn === 3.1"/>
                <AuthenticationSettings v-if='activeNavBtn === 3.2'/>
                <LanguageSettings v-if='activeNavBtn === 3.3'/>
            </div>
        </div>
    </div>
</template>

<script>
import Wallet from './components/Wallet.vue';
import Accounts from './components/Accounts.vue';
import PersonalDetails from './components/Profile/PersonalDetails.vue';
import ContactDetails from './components/Profile/ContactDetails.vue';
import UploadDocuments from './components/Profile/UploadDocuments.vue';
import ManageBankDetails from './components/Profile/ManageBankDetails.vue';
import TaxIdentification from './components/Profile/TaxIdentification.vue';
import LegalDocumentation from './components/Profile/LegalDocumentation.vue';
import CloseAccount from './components/Profile/CloseAccount.vue';
import PasswordSettings from './components/Settings/PasswordSettings.vue';
import AuthenticationSettings from './components/Settings/AuthenticationSettings.vue';
import LanguageSettings from './components/Settings/LanguageSettings.vue';

export default {
    name: 'DashboardMain',
    components: {
        Wallet,
        Accounts,
        PersonalDetails,
        ContactDetails,
        ManageBankDetails,
        UploadDocuments,
        TaxIdentification,
        LegalDocumentation,
        CloseAccount,
        PasswordSettings,
        AuthenticationSettings,
        LanguageSettings,
    },
    props: {
        isLight: Boolean,
        activeNavBtn: Number,
    },
    data() {
        return {
            navOpen: true,
        }
    },

    mounted() {

    },


    methods: {
        toggleNav() {
            this.navOpen = !this.navOpen;
        },
    }
}
</script>

<style lang='scss'>

#dash-main {
    #dash-nav {
        background-color: #2c3235;
        min-height: 100vh !important;
        max-width: 220px !important;
        box-shadow: 0 0 8px rgb(0 0 0 / 27%) !important;
        z-index: 9999;

        #log-out-open {
            max-width: 220px !important;
        }

        #toggle-nav,
        #change-theme {
            transition: .15s ease-in-out;
            border-radius: 50%;
            width: 42px;
            color: #f9fafb;
            background-color: transparent;
            border: none;
            font-size: 26px !important;
        }

        #toggle-nav:hover,
        #change-theme:hover {
            background-color: #424a4d;
        }

        .no-hover-eff:hover {
            background: transparent !important;
        }

        .nav-open-btn {
            .v-icon {
                width: fit-content !important;
            }

            span {
                margin: 10px 0px;
                font-size: 16px;
                line-height: 3;
                width: 100%;
                transition: background-color .15s ease-in-out;
            }
        }

        .dash-nav-btn,
        .b-dropdown .dropdown-toggle {
            transition: background-color .15s ease-in-out;
            background: transparent;
            color: #b2bec3;
            border: none;
            border-left: 2px solid transparent;
            border-right: 2px solid transparent;
            border-radius: 0px !important;
            font-size: 24px;
            padding: 6px 16px;
            outline:none !important;
            outline-width: 0 !important;
            box-shadow: none !important;
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            z-index: 99999;
            margin: 10px 0px;
            width: 100% !important;
            text-align: left !important;

            span {
                color: #84949a;
            }
        }

        .b-dropdown .dropdown-toggle span {
            font-size: 16px;
            position: absolute;
            padding-left: 16px;
            line-height: 2.4;
        }

        #dropdown-profile.active-dd .dropdown-toggle span,
        #dropdown-settings.active-dd .dropdown-toggle span,
        .nav-open-btn.active-nav-btn > span {
            color: #f9fafb !important;
        }

        .nav-open-btn.active-nav-btn > .dash-nav-btn,
        .active-nav-btn,
        .b-dropdown .active-nav-btn,
        #dropdown-profile.active-dd .dropdown-toggle,
        #dropdown-settings.active-dd .dropdown-toggle {
            color: #d63031 !important;
            border-left-color: #d63031 !important;

            span {
                font-weight: 500 !important;
            }
        }

        .dropdown-menu {
            border: none !important;
            margin-left: 15px !important;
            margin-top: -1px !important;
            background-color: #424a4d !important;
            border-radius: 0px;
            padding: 0px;
            padding-bottom: 0.7rem !important;
            min-width: 200px;

            .active-item {
                .dropdown-item {
                    color: #3c95d8 !important;
                }
            }

            li {
                .dropdown-header {
                    padding: 0.7rem 1.2rem !important;
                    color: #f9fafb !important;
                    font-weight: 500;
                }

                .dropdown-item {
                   color: #84949a;
                   padding: 0.5rem 1.2rem !important;
                   font-size: 14px;
                   font-weight: 400;
                }

                .dropdown-item.disabled {
                    opacity: .4 !important;
                }

                .dropdown-item:hover {
                    background: transparent !important;
                    color: #3c95d8 !important;
                }

            }
        }

        .dropdown-menu::after {
            content: " ";
            position: absolute;
            top: 0%;
            right: 100%;
            margin-top: 15px;
            border-width: 10px;
            border-style: solid;
            border-color: transparent #424a4d transparent transparent;
        }

        .nav-open-btn:hover > span {
            cursor: pointer;
            background-color: #424a4d !important;
        }

        .nav-open-btn:hover > .dash-nav-btn,
        .dash-nav-btn:hover,
        .b-dropdown .dropdown-toggle:hover {
            cursor: pointer;
            background-color: #424a4d !important;
            color: #d63031 !important;

            span {
                color: #f9fafb !important;
            }
        }

        .nav-open-btn:hover,
        .dash-nav-btn:hover {
            span {
                color: #f9fafb !important;
            }
        }
    }

    .n-open {
        /*transition: width .15s ease-in-out !important;*/
        width: 220px !important;
    }

    .n-closed {
        width: 60px !important;
    }

    #dash-nav,
    #dash-window {
        #dash-window-content {
            transition: all 0.25s ease-in-out !important;
            padding: 50px 0px !important;
            min-height: 100vh;
        }

        .dashboard-component {
            margin: 50px auto;
            max-width: 560px !important;

            .header-label {
                color: #f9fafb;
            }
        }

        .cus-input {
            font-size: 14px;
            height: 52px;
            padding: 0px 16px;
            transition: all .15s ease-in;
            width: 100%;
            color: #f9fafb;
            background-color: #23282a;
            border-radius: 4px;
            border-color: #424a4d;
        }

        .is-valid {
            border-color: #00b894 !important;
        }

        .is-invalid {
            border-color: #eb3749 !important;
        }

        .was-validated .form-control:valid, .form-control.is-valid {
            border-color: #00b894 !important;
        }

        .was-validated .form-control:valid, .form-control.is-invalid {
            border-color: #eb3749 !important;
        }

        .cus-input:disabled {
            opacity: .6 !important;
        }

        .label-group {
            font-size: 14px !important;
            font-weight: 500;
        }
    }
}

@media only screen and (max-width: 991px) {
    #dash-main {

        #dash-nav {
            display: none !important;
        }

        #dash-window {
            min-height: 100vh !important;

            #dash-window-content {
                margin-left: 0px !important;
            }
        }
    }
}
</style>
