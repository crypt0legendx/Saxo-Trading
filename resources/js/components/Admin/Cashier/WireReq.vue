<template>
    <div class="WireReq-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>Cashier->WireReq</h1>
    </div>
</template>

<script>
export default {
    name:"WireReq",
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
                    text:'WireReq',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .WireReq-container {
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
