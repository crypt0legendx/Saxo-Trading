<template>
  <div v-if="!loaded" class="dashboard-component p-2 col-12 col-lg-11 col-md-11 d-flex justify-content-center align-items-center">
    <span style="width: 5rem; height: 5rem; margin-top:10rem" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
  </div>

  <div v-else>
    <div  v-if="!authEnabled" class="dashboard-component p-2 col-12 col-lg-11 col-md-11">
      <h5 class="mb-4 text-center header-label">
        Enable 2-step authentication to secure your account
      </h5>

      <div class="text-center">
        <v-icon id="auth-step-icon" class="mb-4">mdi-shield-off</v-icon>
        <label class='label-group'>
          In addition to your password, set up a secondary verification method to keep your account even safer. When you enable 2-step authentication, you will need to enter your password as well as a verification code sent to your email address.
        </label>
      </div>

      <OptionBtns
      @submitEvent='enableTwoStepAuth'
      :submitText="authEnabled ? 'Turn Off' : 'Turn On'"
      :hideBack="true"
      />
    </div>

    <div  v-else class="dashboard-component p-2 col-12 col-lg-11 col-md-11">
      <h5 class="mb-4 text-center header-label">
        Your account is secured!
      </h5>

      <div class="text-center">
        <v-icon id="auth-step-icon" style="color: #62ff62" class="mb-4">mdi-shield-lock</v-icon>
        <label class='label-group'>
          In addition to your password, set up a secondary verification method to keep your account even safer. When you enable 2-step authentication, you will need to enter your password as well as a verification code sent to your email address.
        </label>
      </div>

      <OptionBtns
      @submitEvent='enableTwoStepAuth'
      :submitText="authEnabled ? 'Turn Off' : 'Turn On'"
      :hideBack="true"
      />
    </div>
  </div>
</template>

<script>
import OptionBtns from '../OptionBtns.vue';

export default {
  name: 'AuthenticationSettings',
  components: {
    OptionBtns
  },
  data() {
    return {
      authEnabled: false,
      loaded: false,
    }
  },

  mounted() {
    this.$http.get(`/api/user`).then((res) => {
      this.authEnabled = res.data.user['2fa']
      this.loaded = true;
    })
  },


  methods: {
    enableTwoStepAuth() {
      this.$http.post(`/api/user/update/2fa`, {
        value: !this.authEnabled
      }).then((res) => {
        res = res.data;
        if(res['2fa'] == !this.authEnabled)
        this.authEnabled = !this.authEnabled;
      })
    }
  }
}
</script>

<style lang='scss'>
#auth-step-icon {
  font-size: 150px !important;
}
</style>
