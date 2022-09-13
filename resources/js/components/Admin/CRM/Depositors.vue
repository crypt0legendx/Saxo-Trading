<template>
    <div class="Depositors-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>CRM->Depositors</h1>
    </div>
</template>

<script>
export default {
    name:"Depositors",
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
                    text:'Depositors',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .Depositors-container {
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