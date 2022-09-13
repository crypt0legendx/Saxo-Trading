<template>
  <div class="dashboard-component p-2 col-12 col-lg-11 col-md-11">
    <h5 class="mb-4 text-center header-label">Language Settings</h5>

    <div role="group" class="w-100 p-1 mb-1">
      <label>System Language:</label>
      <v-select class="cus-input no-focus-outline" :options="['English']" value="English" required></v-select>
      <OptionBtns @submitEvent='changeLanguage' :hideBack="true" />
    </div>
  </div>
</template>

<script>
import OptionBtns from '../OptionBtns.vue';

export default {
  name: 'PasswordSettings',
  components: {
    OptionBtns
  },
  data() {
    return {
      //
    }
  },
  methods: {
    changeLanguage() {
      this.$dtoast.pop({
              preset: "success",
              heading: `Success!`,
              content:  `System language changed successfully`,
        })
    },
  },
}
</script>

<style>

</style>
