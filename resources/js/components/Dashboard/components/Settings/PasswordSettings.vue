<template>
  <div class="dashboard-component p-2 col-12 col-lg-11 col-md-11">
    <h5 class="mb-4 text-center header-label">Change Password</h5>

    <div role="group" class="w-100 p-1 mb-1">
      <label class='label-group'>Current Password</label>
      <b-form-input
      type="password"
      @input="onMod('pwd')"
      class='cus-input'
      id="password"
      v-model="currPassword"
      :state="currPasswordState"
      aria-describedby="password-feedback"
      placeholder="Enter your new password"
      trim
      required
      ></b-form-input>

      <b-form-invalid-feedback id="password-feedback">
        Field is empty or invalid
      </b-form-invalid-feedback>
    </div>

    <div role="group" class="w-100 p-1 mb-1">
      <label class='label-group'>New Password</label>
      <b-form-input
      type="password"
      class='cus-input'
      id="new-password"
      @input="onMod('npwd')"
      v-model="newPassword"
      :state="newPasswordState"
      aria-describedby="new-password-feedback"
      placeholder="Enter your new password"
      trim
      required
      ></b-form-input>

      <b-form-invalid-feedback id="new-password-feedback">
        Password must contain the following:<br/>
        - at least one digit<br/>
        - one lower case<br/>
        - one upper case<br/>
        - length of 8 characters
      </b-form-invalid-feedback>
    </div>

    <div role="group" class="w-100 p-1 mb-1">
      <label class='label-group'>Repeat Password</label>
      <b-form-input
      type="password"
      class='cus-input'
      id="conf-new-password"
      @input="onMod('npwd2')"
      v-model="confNewPassword"
      :state="confNewPasswordState"
      aria-describedby="conf-new-password-feedback"
      placeholder="Comfirm your new password"
      trim
      required
      ></b-form-input>

      <b-form-invalid-feedback id="conf-new-password-feedback">
        Make sure this field is the same as your new password
      </b-form-invalid-feedback>
    </div>

    <OptionBtns
    @submitEvent='changePassword'
    :hideBack="true"
    />
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
      currPassword: '',
      newPassword: '',
      confNewPassword: '',

      modified : {
        pwd: false,
        npwd: false,
        npwd2: false,
      },
    }
  },
  methods: {
    changePassword() {
      this.$http.post(`/api/user/password/update`, {
        current_password: this.currPassword,
        new_password: this.newPassword,
      }).then((res) => {
        this.currPassword = '';
        this.newPassword = '';
        this.confNewPassword = '';

        this.modified.pwd = false
        this.modified.npwd = false
        this.modified.npwd2 = false

        if(res.data.status == "ERROR") {
          this.$sound.play('error.mp3')
            this.$dtoast.pop({
                preset: "error",
                heading: `Unexpected Error`,
                content:  res.data.reason,
          })
          return;
        }

        this.$sound.play('success.mp3')
        this.$dtoast.pop({
              preset: "success",
              heading: `Success!`,
              content:  res.data.reason,
        })
      })
    },

    onMod(prop) {
      this.modified[prop] = true
    },
  },
  computed: {
    currPasswordState() {
      if(this.modified['pwd'] == false) return null;
      return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test(this.currPassword);
    },
    newPasswordState() {
      if(this.modified['npwd'] == false) return null;
      return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test(this.newPassword);
    },
    confNewPasswordState() {
      if(this.modified['npwd2'] == false) return null;
      return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test(this.confNewPassword) && (this.confNewPassword === this.newPassword);
    }
  }
}
</script>

<style>

</style>
