<template>
    <div class="adjustments-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>Cashier->Adjustments</h1>
    </div>
</template>

<script>
export default {
    name:"Adjustments",
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
                    text:'Adjustments',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .adjustments-container {
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