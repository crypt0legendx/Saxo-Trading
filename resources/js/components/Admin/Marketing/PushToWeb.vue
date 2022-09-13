<template>
    <div class="PushToWeb-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>Marketing->PushToWeb</h1>
    </div>
</template>

<script>
export default {
    name:"PushToWeb",
    data() {
        return {
            breadcrumbItems: [
                {
                    text:'AdminPanel',
                    href:'#'
                },
                {
                    text:'Marketing',
                    href:'#'
                },
                {
                    text:'PushToWeb',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .PushToWeb-container {
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