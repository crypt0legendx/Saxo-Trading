<template>
    <div class="Assets-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>Trading->Assets</h1>
    </div>
</template>

<script>
export default {
    name:"Assets",
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
                    text:'Assets',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .Assets-container {
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