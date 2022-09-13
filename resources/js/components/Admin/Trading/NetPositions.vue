<template>
    <div class="NetPositions-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>Trading->NetPositions</h1>
    </div>
</template>

<script>
export default {
    name:"NetPositions",
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
                    text:'NetPositions',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .NetPositions-container {
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