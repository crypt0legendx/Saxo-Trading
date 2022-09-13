<template>
    <div class="Emails-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>CRM->Emails</h1>
    </div>
</template>

<script>
export default {
    name:"Emails",
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
                    text:'Emails',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .Emails-container {
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