<template>
    <div class="dashboard-card d-flex">
        <div class="card-icon p-4 d-flex align-items-center justify-content-center">
            <v-icon>mdi-{{icon}}</v-icon>
        </div>
        <div class="card-body d-flex flex-column p-5">
            <div class="card-balance">$ {{balance}}</div>
            <div class="card-title">{{title}}</div>
            <b-button variant="dark">Vew All</b-button>
        </div>
    </div>
</template>



<script>
export default{
    props: {
        icon:String,
        title: String,
        balance: Number,
        link:String
    },
    name:"DashboardCard",
}
</script>

<style lang="scss">
    @import url("https://use.typekit.net/lhi1tya.css");

    .dashboard-card {
         border-radius: 5px;
         border: 1px solid #343a40;
         .card-icon{
            font-size: 40px;
            background: #2E86DE;
            color:#fff;
            border-bottom-left-radius: 5px;
            border-top-left-radius: 5px;
         }
         .card-body{
            width: 220px;
            .card-balance{
                font-size: 32px;
                color: #fff;
                font-family: rajdhani, sans-serif;
                font-weight: 700;
                font-style: normal;
            }
            .card-title{
                font-size: 16px;
                color: #ddd;
                line-height: 26px;
            }
         }
    }
</style>
