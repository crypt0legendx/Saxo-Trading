<template>
    <div class="Users-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>CRM->Users</h1>
    </div>
</template>

<script>
export default {
    name:"Users",
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
                    text:'Users',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .Users-container {
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