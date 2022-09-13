<template>
    <div class="Trades-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>Trading->Trades</h1>
    </div>
</template>

<script>
export default {
    name:"Trades",
    data() {
        return {
            breadcrumbItems: [
                {
                    text:'AdminPanel',
                    href:'#'
                },
                {
                    text:'Trading',
                    href:'#'
                },
                {
                    text:'Trades',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .Trades-container {
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