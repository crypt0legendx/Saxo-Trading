<template>
    <div class="ActivityHours-container">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <h1>Trading->ActivityHours</h1>
    </div>
</template>

<script>
export default {
    name:"ActivityHours",
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
                    text:'ActivityHours',
                    active:true
                }
            ]
        }
    }
}
</script>

<style lang="scss">
    .ActivityHours-container {
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