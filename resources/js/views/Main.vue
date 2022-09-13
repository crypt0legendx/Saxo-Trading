<template>
    <v-app id="app">
      <template v-if="!$store.state.done.connected || $store.state.unsupported_browser || $store.state.mobile_device">
        <ConnectionFailed v-show="!$store.state.done.connected"/>
      </template>
      <template v-else>
        <TopBar/>
        <Navbar/>
        <Workspace/>
      </template>
    </v-app>

</template>

<style lang="scss">
@import "vue-select/src/scss/vue-select.scss";
@import '~/variables.scss';
@import '~/themes/light_theme.scss';
@import '~/themes/dark_theme.scss';

.userback-button {
    display: none !important;
}

</style>

<script>
import Navbar from '../components/Navbar.vue'
import TopBar from '../components/TopBar.vue'
import Workspace from '../components/Workspace.vue'
import ConnectionFailed from '../components/Status/ConnectionFailed.vue'

//import UserData from '../components/Mutators/UserData.js'
import Events from '../components/Mutators/Events.js'
export default {
    name: "Main",
    components: {
        Navbar,
        TopBar,
        Workspace,
        ConnectionFailed,
    },

    mounted() {
        if(localStorage.light_theme != undefined)
            this.$store.state.light_theme = JSON.parse(localStorage.light_theme) //somehow ends as string? weird af thats why json parse

        let app = document.querySelector('#app .v-application--wrap #app');
        app.classList.toggle('light-theme', this.$store.state.light_theme)
    },

    created () {

        let th = this
        document.body.addEventListener('click', function () {
            th.$store.state.done.user_interact = true
        }, true);

        //UserData.sync(this);
        Events.start(this);
    },
}

</script>
