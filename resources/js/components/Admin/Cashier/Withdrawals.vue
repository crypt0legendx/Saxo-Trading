<template>
    <div class="withdrawals-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>Cashier->Withdrawals</h1>
    </div>
</template>

<script>
export default {
    name:"Withdrawals",
    data() {
        return {
            breadcrumbItems: [
                {
                    text:'AdminPanel',
                    href:'#'
                },
                {
                    text:'Cashier',
                    href:'#'
                },
                {
                    text:'Withdrawals',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .withdrawals-container {
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