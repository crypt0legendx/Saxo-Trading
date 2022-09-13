<template>
    <div id='dash-header' class="d-flex w-100 col-12 fixed-top">
        <v-icon id='dash-mobile-nav-toggler' class="no-focus-outline mr-auto text-center" v-b-toggle.dashboard-sidebar>
          mdi-menu
        </v-icon>

        <v-icon class="mx-auto text-center">
          mdi-vuejs
        </v-icon>

        <v-icon id='change-theme' class="no-focus-outline ml-auto text-center" @click="$emit('changeTheme')" >
          mdi-{{ isLight ? 'moon-waning-crescent' : 'weather-sunny' }}
        </v-icon>

        <b-sidebar id="dashboard-sidebar" aria-label="Sidebar"  :backdrop-variant="isLight ? 'dark' : 'light'" backdrop shadow width="260px">
            <div class="p-0 m-0">

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
                    :class="parseInt(activeNavBtn) === 0 ? 'active-nav-btn text-left' : ''">
                    <v-icon class="dash-nav-btn">
                        mdi-account-cash-outline
                    </v-icon>
                    <span>Wallet</span>
                </div>

                <div
                    @click="$emit('setActiveNav', 1)"
                    class="nav-open-btn d-flex"
                    :class="parseInt(activeNavBtn) === 1 ? 'active-nav-btn text-left' : ''">
                    <v-icon class="dash-nav-btn">
                        mdi-account-group-outline
                    </v-icon>
                    <span>Accounts</span>
                </div>

                <div>
                    <b-button
                    v-b-toggle.my-profile
                    class="nav-open-btn-toggle no-focus-outline"
                    :class="parseInt(activeNavBtn) === 2 ? 'active-nav-btn' : ''"
                    >
                        <div class="d-flex w-100">
                            <v-icon>mdi-account-circle-outline</v-icon>
                            <span class="w-100">My profile</span>
                        </div>
                    </b-button>
                    <b-collapse id="my-profile">
                        <p @click="$emit('setActiveNav', 2.1)" :class="activeNavBtn === 2.1 ? 'active' : ''">Personal details</p>
                        <p @click="$emit('setActiveNav', 2.2)" :class="activeNavBtn === 2.2 ? 'active' : ''">Contact details</p>
                        <p @click="$emit('setActiveNav', 2.3)" :class="activeNavBtn === 2.3 ? 'active' : ''">Upload documents</p>
                        <p @click="$emit('setActiveNav', 2.4)" :class="activeNavBtn === 2.4 ? 'active' : ''">Manage bank details</p>
                        <p @click="$emit('setActiveNav', 2.5)" :class="activeNavBtn === 2.5 ? 'active' : ''">Manage TAX IDs</p>
                        <p @click="$emit('setActiveNav', 2.6)" :class="activeNavBtn === 2.6 ? 'active' : ''">Legal documentation</p>
                        <p @click="$emit('setActiveNav', 2.7)" :class="activeNavBtn === 2.7 ? 'active' : ''">Close account</p>
                    </b-collapse>
                </div>

                <div>
                    <b-button
                    v-b-toggle.settings
                    class="nav-open-btn-toggle no-focus-outline"
                    style="margin-top: 20px !important;"
                    :class="parseInt(activeNavBtn) === 3 ? 'active-nav-btn' : ''"
                    >
                        <div class="d-flex w-100">
                            <v-icon>mdi-cog</v-icon>
                            <span class="w-100">Settings</span>
                        </div>
                    </b-button>
                    <b-collapse id="settings">
                        <p @click="$emit('setActiveNav', 3.1)" :class="activeNavBtn === 3.1 ? 'active' : ''">
                            Password settings
                        </p>
                        <p @click="$emit('setActiveNav', 3.2)" :class="activeNavBtn === 3.2 ? 'active' : ''">
                            Authentication settings
                        </p>
                    </b-collapse>
                </div>

                <div
                    @click="$emit('logOut')"
                    style="width: 100% !important; margin-top: 10px;"
                    class="nav-open-btn d-flex">
                    <v-icon class="dash-nav-btn">
                        mdi-logout
                    </v-icon>
                    <span>Logout</span>
                </div>
            </div>
        </b-sidebar>
    </div>
</template>

<script>
export default {
    name: 'DashboardHeader',
    props: {
        isLight: Boolean,
        activeNavBtn: Number,
    },
}
</script>

<style lang='scss'>
@import '~/variables.scss';

#dash-header {
    background-color: #2c3235 !important;
    padding: 6px 12px;
    z-index: 999;
    display: none !important;

    .v-icon {
        font-size: 24px !important;
        border: none;
        background-color: transparent;
        color: #b2bec3;
    }

    #dash-mobile-nav-toggler,
    #change-theme {
        transition: .15s ease-in-out;
        border-radius: 50%;
        width: 39px;
    }

    #dash-mobile-nav-toggler:hover,
    #change-theme:hover {
        background-color: #424a4d;
    }

    #dashboard-sidebar {
        background-color: #2c3235 !important;
        color: #84949a !important;

        .b-sidebar-header {
            text-align: center;

            .close {
                margin: 0px !important;
                font-size: 32px !important;
                color: #b2bec3 !important;
                opacity: 1 !important;
                font-weight: 500 !important;
            }
        }

        .nav-open-btn-toggle {
            border-right-color: transparent !important;
            margin-top: 10px;
            margin-bottom: 0px;
            width: 100%;
            text-align: left;
            border-left: 2px solid transparent;
            border-radius: 0px;
            padding-left: 15px;
            background-color: transparent;
            border-top: none;
            border-bottom: none;

            .v-icon {
                transition: color .15s ease-in-out;
            }

            span {
                color: #84949a;
                font-size: 16px !important;
                padding-left: 16px !important;
                line-height: 2.3;
            }
        }

        .nav-open-btn-toggle.not-collapsed {
            background-color: #424a4d !important;

            .v-icon {
                color: #d63031 !important;
            }

            span {
                color: #f9fafb !important;
            }
        }

        .collapse#my-profile,
        .collapse#settings {
            background-color: #424a4d;
            p {
                cursor: pointer;
                user-select: none !important;
                font-size: 14px;
                padding: 10px 56px;
                margin: 0px;

                .v-icon {
                    font-size: 14px !important;
                }
            }

            p.active,
            p:hover {
                color: #3c95d8 !important;
            }
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
        }

        .b-dropdown .dropdown-toggle span {
            font-size: 14px;
            position: absolute;
            padding-left: 16px;
            line-height: 2.5;
        }

        .nav-open-btn-toggle.active-nav-btn {
            .v-icon {
                color: #d63031 !important;
            }
        }

        .nav-open-btn-toggle.active-nav-btn span,
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
            min-width: 240px;

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

        .nav-open-btn-toggle:hover {
            cursor: pointer;
            background-color: #424a4d !important;

            .v-icon {
                color: #d63031 !important;
            }
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
}

@media only screen and (max-width: 991px) {
    #dash-header {
        box-shadow: 0 0 8px rgb(0 0 0 / 40%) !important;
        position: fixed;
        top: 0;
        display: flex !important;
    }
}
</style>
