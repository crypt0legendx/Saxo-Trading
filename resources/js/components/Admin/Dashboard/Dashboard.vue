<template>
    <div class="Dashboard-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <!-- <h1>Dashboard-></h1> -->
        <div class="card-container d-flex justify-content-between mt-5">
            <dashboard-card icon="currency-usd" title="Current Balance" balance="0" link="#" />
            <dashboard-card icon="wallet-plus" title="Total Deposits" balance="0" link="#" />
            <dashboard-card icon="wallet-outline" title="Total Withdrawals" balance="0" link="#" />
            <dashboard-card icon="cash" title="Total Transactions" balance="0" link="#" />
        </div>
        <div class="chart-section d-flex">
            <dashboard-chart />
            <dashboard-chart />
        </div>
    </div>
</template>

<script>
import DashboardCard from "./DashboardCard.vue";
import DashboardChart from "./DashboardChart.vue";
export default {
    name:"Dashboard",
    components:{DashboardCard, DashboardChart},
    data() {
        return {
            breadcrumbItems: [
                {
                    text:'AdminPanel',
                    href:'#'
                },
                {
                    text:'Dashboard',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .Dashboard-container {
         padding: 20px 20px 20px 60px;
         .breadcrumb{
            background-color: #e5e5e529;
            .breadcrumb-item {
                a{
                    color: #fff;
                }
            }
         }
         
    }
</style>