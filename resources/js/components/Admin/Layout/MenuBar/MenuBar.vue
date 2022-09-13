
<template>
    <div>
        <b-navbar type="dark" variant="dark">
            
            <b-navbar-brand > 
                <v-icon>mdi-vuejs</v-icon>               
                Saxo
            </b-navbar-brand>
            <b-navbar-nav>
                <b-nav-item>
                    <router-link to="/admin-panel/dashboard">Dashboard</router-link>
                </b-nav-item>

                <!-- Navbar dropdowns -->
                <b-nav-item-dropdown text="CRM" right>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/crm-users">Users</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/crm-depositors">Depositors</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/crm-agents">Agents</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/crm-sales-report">Sales Report</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/crm-notes">Notes</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/crm-emails">Emails</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/crm-calendar">Calendar</router-link>
                    </b-dropdown-item>                    
                </b-nav-item-dropdown>

                <b-nav-item-dropdown text="Cashier" right>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/cashier-deposits">Deposits</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/cashier-bonuses">Bonuses</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/cashier-adjustments">Adjustments</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/cashier-withdrawals">Withdrawals</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/cashier-wire-req">Wire Req</router-link>
                    </b-dropdown-item>                    
                </b-nav-item-dropdown>

                <b-nav-item-dropdown text="Trading" right>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/trading-trades">Trades</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/trading-assets">Assets</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/trading-activity-hours">Activity Hours</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/trading-net-positions">Net Positions</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/trading-open-trades">Open Trades</router-link>
                    </b-dropdown-item>                    
                </b-nav-item-dropdown>

                <b-nav-item-dropdown text="Marketing" right>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/marketing-compaigns">Compaigns</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/marketing-partners">Partners</router-link>
                    </b-dropdown-item>
                    <b-dropdown-item >
                        <router-link to="/admin-panel/marketing-push-web">Push to Web</router-link>
                    </b-dropdown-item>                             
                </b-nav-item-dropdown>

                <b-nav-item>
                    <router-link to="/admin-panel/online">Online</router-link>
                </b-nav-item>

                <b-nav-item-dropdown text="System" right>
                    <b-dropdown-item >Payment Gateways</b-dropdown-item>
                    <b-dropdown-item >Platform Admin</b-dropdown-item>
                    <b-dropdown-item >Test CC numbers</b-dropdown-item>                    
                </b-nav-item-dropdown>

                <b-nav-item-dropdown text="Platform type" right>
                    <b-dropdown-item >Tracking</b-dropdown-item>
                    <b-dropdown-item >API Docs</b-dropdown-item>
                    <b-dropdown-item >oAuch</b-dropdown-item>                    
                    <b-dropdown-item >Permisions</b-dropdown-item>                    
                    <b-dropdown-item >Groups</b-dropdown-item>                    
                    <b-dropdown-item >Spread</b-dropdown-item>                    
                    <b-dropdown-item >Promo Code</b-dropdown-item>                    
                    <b-dropdown-item >Auth Assign</b-dropdown-item>                    
                    <b-dropdown-item >Notificaitons</b-dropdown-item>                    
                    <b-dropdown-item >Min/Max Trades</b-dropdown-item>                    
                    <b-dropdown-item >Status</b-dropdown-item>                    
                    <b-dropdown-item >Dynamic Status</b-dropdown-item>                    
                    <b-dropdown-item >Desks</b-dropdown-item>                    
                    <b-dropdown-item >Event log</b-dropdown-item>                    
                    <b-dropdown-item >Forex Comissions</b-dropdown-item>                    
                    <b-dropdown-item >Crypto Comissions</b-dropdown-item>                    
                </b-nav-item-dropdown>

            </b-navbar-nav>
        </b-navbar>
    </div>
</template>

<style lang="scss">
@import '../../../../scss/_variables.scss';

    .navbar-dark {
        .navbar-brand {
            color: #fff;
            font-weight: bold;
        }

        
    } 

    .nav-link  {
        a {
            color:rgba(255, 255, 255, 0.5);
        }
    }
    .dropdown-item{
        a {
            color:#000000;
        }
    }
    // .v-application {
    //     a {
    //         color: #a7a7a7;
    //     }
    // }


</style>

<script>
    export default {
        name : "Menubar",
    }
</script>
