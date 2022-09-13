<template>
    <div class="Online-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>Online-></h1>
    </div>
</template>

<script>
export default {
    name:"Online",
    data() {
        return {
            breadcrumbItems: [
                {
                    text:'AdminPanel',
                    href:'#'
                },
                {
                    text:'Online',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .Online-container {
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