<template>
    <div class="bonuses-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>Cashier->Bonuses</h1>
    </div>
</template>

<script>
export default {
    name:"Bonuses",
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
                    text:'Bonuses',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .bonuses-container {
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