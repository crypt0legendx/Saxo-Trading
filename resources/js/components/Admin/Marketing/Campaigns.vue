<template>
    <div class="Campaigns-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>Marketing->Campaigns</h1>
    </div>
</template>

<script>
export default {
    name:"Campaigns",
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
                    text:'Campaigns',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .Campaigns-container {
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