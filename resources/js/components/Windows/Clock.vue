<template>
  <v-container class="w-100 h-100 fluid pa-0" style="max-width:100%; height: 1px;">
     <!-- SENDING TOPBAR !-->
     <portal :to="windID">
      <i class="font-icon icon_clock"></i>
      <span class="topbar-heading">Clock</span>

      <div class="topbar-component select">
          <select v-model="tz">
          <option value="0" selected>UTC+0</option>
          <option value="-12">UTC-12</option>
          <option value="-11">UTC-11</option>
          <option value="-10">UTC-10</option>
          <option value="-9">UTC-9</option>
          <option value="-8">UTC-8</option>
          <option value="-7">UTC-7</option>
          <option value="-6">UTC-6</option>
          <option value="-5">UTC-5</option>
          <option value="-4">UTC-4</option>
          <option value="-3">UTC-3</option>
          <option value="-2">UTC-2</option>
          <option value="-1">UTC-1</option>
          <option value="1">UTC+1</option>
          <option value="2">UTC+2</option>
          <option value="3">UTC+3</option>
          <option value="4">UTC+4</option>
          <option value="5">UTC+5</option>
          <option value="6">UTC+6</option>
          <option value="7">UTC+7</option>
          <option value="8">UTC+8</option>
          <option value="9">UTC+9</option>
          <option value="10">UTC+10</option>
          <option value="11">UTC+11</option>
          <option value="12">UTC+12</option>
          <option value="13">UTC+13</option>
        </select>
      </div>
    </portal>
    <!-- END OF TOPBAR !-->

    <div ref="clock" class="center-clock" :style="'font-size: ' + clock_size + 'px'">
      <span>{{current_time}}</span>
    </div>
  </v-container>
</template>


<style lang="scss">
@import '~/variables.scss';

.center-clock {
  height:100%;
  width: 100%;
  display:flex;
  justify-content:center;
  align-items:center;
  font-family: 'Roboto';
  font-size: 100px;
  user-select:none;
  color: $clock_component_color;
}

</style>

<script>
export default {
  name : "Clock",


  data () {
    return {
      tz: '0',
      current_time : 0,
      clock_size: 128,
    }
  },

  components: {

  },

  props : {
    windID : Number,
  },

  methods: {
    updateNow() {
      var date = new Date();
      var dateR = new Date(date.getTime() + date.getTimezoneOffset() * 60000 + (3600000 * this.tz))

      this.date = dateR.getDate() + '/' + (dateR.getMonth() + 1) + '/' + dateR.getFullYear();
      this.time = dateR.getHours().toLocaleString('en-US', {minimumIntegerDigits: 2}) + ':' + dateR.getMinutes().toLocaleString('en-US', {minimumIntegerDigits: 2}) + ':' +   dateR.getSeconds().toLocaleString('en-US', {minimumIntegerDigits: 2});

      this.current_time = this.time

    },
  },

  watch: {
      '$store.state.done.window_resized' : function() {
        var w = this.$refs.clock.offsetWidth
        var h = this.$refs.clock.offsetHeight

        this.clock_size = w / 6.5561
      }
  },

  mounted() {
    var w = this.$refs.clock.offsetWidth
    var h = this.$refs.clock.offsetHeight

    this.clock_size = w / 6.5561
    this.updateNow()
    setInterval(this.updateNow.bind(this) , 1000)
  },

  created() {
    this.tz = this.$store.getters.get_user_data('time_zone')
  }
}

</script>
