<template>
    <div class="OpenTrades-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>Trading->OpenTrades</h1>
    </div>
</template>

<script>
export default {
    name:"OpenTrades",
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
                    text:'OpenTrades',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .OpenTrades-container {
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