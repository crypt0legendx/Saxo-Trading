<template>
    <div class="Sales-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>CRM->Sales</h1>
    </div>
</template>

<script>
export default {
    name:"Sales",
    data() {
        return {
            breadcrumbItems: [
                {
                    text:'AdminPanel',
                    href:'#'
                },
                {
                    text:'CRM',
                    href:'#'
                },
                {
                    text:'Sales Report',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .Sales-container {
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