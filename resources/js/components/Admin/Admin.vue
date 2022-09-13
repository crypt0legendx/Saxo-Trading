<template>
    <div id="admin-panel-container">
        <menu-bar />
        <router-view></router-view>
    </div>
</template>

<style lang="scss">
@import '~/variables.scss';

body {
    background-color: $formsBodyBgColor;
    min-height: 100vh !important;
    margin: 0 !important;
    padding: 0 !important;
}

</style>

<script>
import MenuBar from './Layout/MenuBar/MenuBar.vue';

 export default {
    components: { MenuBar },
    name:"AdminPanel",
 }
</script>
<style lang="stylus" scoped>

</style>