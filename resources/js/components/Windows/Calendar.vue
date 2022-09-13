<template>
  <div class="fluid pa-0 w-100 h-100">
    <portal :to="windID">
      <i class="font-icon icon_economic_calendar"></i>
      <span>Economic Calendar</span>
    </portal>
    <iframe
      onload="this.width=screen.width;this.height=screen.height;"
      frameborder="0"
      class="economic-calendar"
      :src='`https://economic-calendar.fxpro.com/en/Calendar#offset=${timezone}&theme=${$store.state.light_theme ? "light" : "dark"}&timeFormat=DD%2FMM%2FYYYY%20HH:mm`'
    ></iframe>
  </div>
</template>

<style lang="scss">
.economic-calendar {
  height: 100%;
}
</style>

<script>
  export default {
      name : "Calendar",

      data () {
        return {
          topbar: "asd",
          timezone: this.$store.state.user.time_zone,
          controls : {
            left : `
              <i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-chart-bar theme--light"></i>
              <span style="font-size:13px;">Economic Calendar</span>
            `,

            right : ``,
          },
        }
      },

      props : {
        windID : Number,
      },

      mounted() {

      },

      created() {

      },
  }
</script>
