<template>
    <div class="Agents-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>CRM->Agents</h1>
    </div>
</template>

<script>
export default {
    name:"Agents",
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
                    text:'Agents',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .Agents-container {
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