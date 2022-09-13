<template>
    <b-container id='dashboard' class="w-100 p-0 m-0" :class="lightTheme ? 'light-theme' : ''">
        <DashboardHeader
            :isLight='lightTheme'
            :activeNavBtn='activeNavBtn'
            @changeTheme='changeTheme'
            @setActiveNav='setActiveNav'
            @returnToTrading='returnToTrading'
            @logOut='Logout'
        />

        <DashboardMain
            :isLight='lightTheme'
            :activeNavBtn='activeNavBtn'
            @changeTheme='changeTheme'
            @setActiveNav='setActiveNav'
            @returnToTrading='returnToTrading'
            @logOut='Logout'
        />

        <div class="abs-right-vhcenter">
            <a href='#'><v-icon>mdi-pencil-box</v-icon></a><br/>
            <a href='#'><v-icon>mdi-forum</v-icon></a><br/>
            <a href='#'><v-icon>mdi-phone</v-icon></a>
        </div>
    </b-container>
</template>

<script>
import DashboardHeader from './DashboardHeader.vue';
import DashboardMain from './DashboardMain.vue';

export default {
    name: 'Dashboard',
    components: {
        DashboardHeader,
        DashboardMain
    },
    data() {
        return {
            lightTheme: false,
            activeNavBtn: 0,
        }
    },
    methods: {
        changeTheme() {
            this.lightTheme = !this.lightTheme;
        },
        setActiveNav(i) {
            this.activeNavBtn = i;
        },
        returnToTrading() {
            location.href = '/'
        },
        Logout() {
            localStorage.clear()
            location.href = '/'
        }
    }
}
</script>

<style lang='scss'>
@import "../../../../node_modules/vue-select/src/scss/vue-select.scss";
@import '~/variables.scss';
$whiteBg: #fff;

body,
#dashboard {
    min-width: 100%;
    width: 100%;
    min-height: 100vh;
    background-color: #23282a;
    color: #84949a;
    font-family: Roboto,"Helvetica Neue",Helvetica,Arial,sans-serif !important;

    .abs-right-vhcenter {
        position: absolute !important;
        right: 0;
        top: 40%;

        a {
            background-color: #424a4d;
            color: #f9fafb;
            font-size: 20px !important;
            padding: 8px;
        }
    }

    .text-white {
        color: #f9fafb !important;
    }

    .form-group {
        label {
            color: #84949a;
            font-weight: 500;
        }
    }

    .v-select-valid > .vs__dropdown-toggle {
        border-color: #00b894 !important;
    }

    .v-select-no-valid > .vs__dropdown-toggle {
        border-color: #d63031 !important;
    }

    .v-select {
        background-color: transparent;
        padding: 0px !important;
        cursor: pointer;

        .vs__dropdown-toggle {
            height: 52px !important;
            padding-left: 4px !important;
            padding-right: 4px !important;
            border-color: #424a4d;

            .vs__selected-options .vs__selected,
            .vs__search {
                cursor: pointer;
                color: #f9fafb;
            }

            .vs__actions {
                cursor: pointer;
                color: #f9fafb;

                .vs__clear {
                    display: none !important;
                    line-height: 0 !important;
                    padding-right: 5px !important;
                }

                .vs__clear,
                .vs__open-indicator {
                    fill: #A2A2A2 !important;
                }
            }
        }

        .vs__dropdown-menu {
            background: #2c3235;
            li,
            option {
                cursor: pointer;
                background: transparent !important;
                transition: all .1s ease-in-out;
                padding-top: 15px !important;
                padding-bottom: 15px !important;
                color: #f9fafb;
            }

            li:hover,
            li:focus,
            li:active,
            option:hover,
            option:focus,
            option:active {
                background-color: #424a4d !important;
            }
        }

        .vs__dropdown-menu::-webkit-scrollbar {
            opacity: .4 !important;
            width: 5px;
            transition: all .1s ease-in-out;
        }

        .vs__dropdown-menu::-webkit-scrollbar-track {
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0);
        }

        .vs__dropdown-menu::-webkit-scrollbar-thumb {
            background-color: #424242;
            border-radius: 2px;
        }
    }
}

#dashboard.light-theme {
    background-color: $whiteBg !important;
    color: #84949a !important;

    #dash-header #dashboard-sidebar .b-sidebar-header .close {
        color: #84949a !important;
    }

    .v-select {
        color: #2d3436 !important;
        .vs__dropdown-toggle {
            border-color: #eaeef1;

            .vs__selected-options .vs__selected,
            .vs__search {
                color: #2d3436 !important;
            }

            .vs__actions {
                color: #2d3436 !important;

                .vs__clear,
                .vs__open-indicator {
                    color: #2d3436 !important;
                }
            }
        }

        .vs__dropdown-menu {
            background: #e9e9e9 !important;
            li,
            option {
                color: #3e4649 !important;
            }

            li:hover,
            li:focus,
            li:active,
            option:hover,
            option:focus,
            option:active {
                background-color: #f9fafb !important;
            }
        }
    }

    #dash-header {
        background-color: $whiteBg !important;

        .v-icon {
            color: #84949a !important;
        }

        .nav-open-btn:hover > .dash-nav-btn,
        .dash-nav-btn:hover,
        .nav-open-btn-toggle:hover {
            background-color: #e9e9e9 !important;
        }

        .nav-open-btn:hover,
        .dash-nav-btn:hover {
            span {
                background-color: #e9e9e9 !important;
            }
        }

        .nav-open-btn,
        .nav-open-btn-toggle {
            span {
               color: #636e72 !important
            }
        }

        .nav-open-btn-toggle.not-collapsed {
            background-color: #e9e9e9 !important;
            span {
                color: #3e4649 !important;
            }
        }

        .collapse#my-profile,
        .collapse#settings {
            background-color: #e9e9e9;
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

        #dash-mobile-nav-toggler,
        #change-theme {
            color: #6d7b81 !important;
        }

        #dashboard-sidebar {
            background-color: $whiteBg !important;
            color: #84949a !important;
        }

        #dash-mobile-nav-toggler:hover,
        #change-theme:hover {
            background-color: #f9fafb !important;

        }
    }

    #dash-main {
        #dash-nav {
            background-color: $whiteBg !important;

            #toggle-nav,
            #change-theme {
                color: #636e72 !important;
            }

            #toggle-nav:hover,
            #change-theme:hover {
                background-color: #e9e9e9 !important;
            }

            .nav-open-btn span,
            .b-dropdown .dropdown-toggle span {
                color: #636e72 !important;
            }

            .dash-nav-btn:hover,
            .b-dropdown .dropdown-toggle:hover {
                background-color: #e9e9e9 !important;
            }

            .dropdown-menu {
                background-color: #e9e9e9 !important;

                li {
                    .dropdown-header{
                        color: #3e4649 !important;
                    }

                    .dropdown-item {
                        color: #6d797c;
                    }
                }
            }

            .dropdown-menu::after {
                border-color: transparent #e9e9e9 transparent transparent;
            }

            #dropdown-profile.active-dd .dropdown-toggle span,
            #dropdown-settings.active-dd .dropdown-toggle span,
            .nav-open-btn.active-nav-btn > span {
                color: #3e4649 !important;
            }

            .nav-open-btn:hover > span {
                background-color: #e9e9e9 !important;
                color: #3e4649 !important;
            }

            .nav-open-btn:hover > .dash-nav-btn,
            .dash-nav-btn:hover,
            .b-dropdown .dropdown-toggle:hover {
                background-color: #e9e9e9 !important;

                span {
                    color: #3e4649 !important;
                }
            }
        }
    }

    #dash-nav,
    #dash-window {

        .cus-input {
            border-color: #eaeef1;
            color: #2d3436 !important;
            background-color: transparent !important;
        }
        #dash-window-header {
            background-color: $whiteBg !important;

            .v-icon {
                color: #84949a !important;
            }

            #toggle-nav:hover,
            #change-theme:hover {
                background-color: #e9e9e9 !important;
            }
        }

        #dash-window-content {
            .dashboard-component {

                #collapse-history .history-details-collapse:not(:last-child) {
                    border-bottom: 1px solid #eaeef1 !important;
                }

                #collapse-history .history-details-collapse {
                    background-color: transparent;
                    border-color: #eaeef1 !important;

                    .history-details-collapse-btn {
                        .date {
                            color: #2d3436 !important;
                        }
                    }
                }

                #collapse-history .history-details-collapse > .collapse.show {
                    background-color: #f9fafb !important;
                }

                #toggle-collapse {
                    color: #2d3436 !important;
                }

                #tax-id-checkboxes,
                #close-account-reasons,
                #close-account-choice {
                    .custom-control {
                        background-color: #f9fafb !important;
                        border-color: #f9fafb;

                        .custom-control-label {
                            color: #2d3436 !important;
                        }
                    }
                }

                .document {
                    border-color: #eaeef1 !important;
                }

                #wallet,
                #transaction-history {
                    border-color: #eaeef1 !important;
                }

                #wallet {
                    #wallet-info {
                        #wallet-balance {
                            .balance, #virt-number #virt-id {
                                color: #2d3436 !important;
                            }
                        }
                    }

                    button:disabled,
                    button:disabled:hover {
                        box-shadow: 0px 5px 15px -6px #84949a, 0px 0px 0px 0px rgb(0 0 0 / 0%) !important;
                        border-color: #eaeef1 !important;
                        color: #84949a !important;

                        .v-icon {
                            color: #84949a !important;
                        }
                    }
                }

                #acc-vertif-main-page {
                    color: #2d3436 !important;

                    p {
                        color: #84949a !important;
                    }

                    h5, h6 {
                        color: #2d3436 !important;
                    }
                }

                #acc-vertif-content {
                    div.text-center > h5 {
                        color: #2d3436 !important;
                    }
                }

                .vertif-option {
                    border-color: #eaeef1 !important;

                    .vertif-option-label {
                        h6 {
                            color: #2d3436 !important;
                        }

                        small {
                            color: #84949a !important;
                        }
                    }

                    &:hover {
                        background-color: #e9e9e9 !important;
                    }
                }

                .acc-vertif-inputs {
                    h5,
                    .custom-file-label,
                    .custom-file-label::after {
                        color: #2d3436 !important;
                    }
                }

                #wallet {

                    /*#add-funds {
                        tr:hover {
                            background-color: #f9fafb !important;
                        }
                    }*/
                    #wallet-btns {
                        button {
                            border: 1px solid #0984e3;
                            color: #2d3436;

                            .v-icon {
                                font-size: 22px;
                                color: #0984e3;
                            }
                        }

                        button:hover {
                            color: #f9fafb;

                            .v-icon {
                                color: #f9fafb;
                            }
                        }
                    }
                }

                .header-label {
                    color: #2d3436;
                }

                .nav-tabs {
                    .nav-link {
                        border-color: transparent;
                        border-bottom-color: #eaeef1;
                    }

                    .nav-link:hover,
                    .nav-link.active {
                        color: #2d3436 !important;
                    }

                    .nav-link.active {
                        border-color: #eaeef1 !important;
                        border-bottom-color: $whiteBg !important;
                        background-color: $whiteBg !important;
                    }
                }

                #account-tables {
                    border-color: #eaeef1 !important;
                    background-color: $whiteBg !important;
                }

                .b-table {
                    thead {
                        border-color: #eaeef1 !important;
                    }

                    tbody {
                        tr {
                            border-color: #eaeef1 !important;

                            td {
                                color: #2d3436 !important;

                                .open-in:hover {
                                    background-color: #f9fafb !important;
                                }
                            }
                        }
                    }
                }

                .table {
                    th, td {
                        border-color: #eaeef1 !important;
                    }
                }
            }
        }
    }
}
</style>
