<template>
    <div class="Partners-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>Marketing->Partners</h1>
    </div>
</template>

<script>
export default {
    name:"Partners",
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
                    text:'Partners',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .Partners-container {
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