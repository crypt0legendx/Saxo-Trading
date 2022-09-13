<template>
  <b-modal
  id='kyc-modal'
  ref="kyc-modal"
  size="md"
  hide-footer
  no-close-on-backdrop
  centered no-fade>
  <template #modal-title>Account Verification</template>
  <template #modal-header-close><v-icon>mdi-close</v-icon></template>
  <template #default class='pb-0 mb-0'>

      <div id="acc-vertif-main-page" class="w-100 text-center" v-if="kycDone">
        <h5 class='text-white mt-3'>Success</h5>
        <small>Wow, that was fast, wasn't it?</small>
        <div>
          <v-icon class="shield-logo mt-3 mb-3" style="color: #04AA6B;" >mdi-check-circle-outline</v-icon>
          <h6 class='text-white'>Now you just need to wait until we verify your account. </br>Usually, that happens in a very short time!</h6>
          <button class="submit mt-5 mb-4" @click='$refs["kyc-modal"].hide()'>
            Continue
          </button>
      </div>
    </div>

    <div id="acc-vertif-main-page" class="w-100 text-center" v-if="!kycPage && !Boolean(kycInputs)">
      <h5 class='text-white mt-3'>Please verify your identity in 2 quick steps</h5>
      <small>92% of users are activated within few minutes</small>
      <div>
        <v-icon class="shield-logo mt-3 mb-3" style="color: #2575b3;" >mdi-shield-account</v-icon>

        <h6 class='text-white'>Here's what you need:</h6>
        <p class="mb-0 pb-0">
          <v-icon class="mt-3 mb-3" style='color: rgb(41, 207, 91);'>mdi-check</v-icon>
          Your passport or Driver's License or identity Card
        </p>
        <p>
          <v-icon style='color: rgb(41, 207, 91);'>mdi-check</v-icon>
          Make a Selfie
        </p>

        <button class="submit mt-5 mb-4" @click='goToVertification'>
          Verify your Identity
        </button>
    </div>
  </div>


  <div id="acc-vertif-content" class="w-100 pb-3" v-if="kycPage  && !Boolean(kycInputs)">
    <div class='text-center'>
      <h5 class='text-white mt-3'>Upload one of the documents below</h5>
      <small>Select the type of document you would like to upload</small>
    </div>
    <div style="margin: 50px auto;">
      <div @click="gotoKycInputs(1)" class='vertif-option d-flex'>
        <v-icon class="vertif-option-icon">
          mdi-badge-account
        </v-icon>
        <div class='vertif-option-label'>
          <h6 class="mb-0 font-weight-light">Passport</h6>
          <small class='font-weight-bold'>Face photo page</small>
        </div>
        <v-icon
        class="vertif-option-icon-action ml-auto"
        :style="{color: pasport.verified ? 'rgb(41, 207, 91) !important' : '#2575b3'}"
        >
        mdi-{{ !pasport.verified ? 'chevron-right' : 'check-circle-outline'}}
      </v-icon>
    </div>
    <div   @click="gotoKycInputs(2)" class='vertif-option d-flex'>
      <v-icon class="vertif-option-icon">
        mdi-car-side
      </v-icon>
      <div class='vertif-option-label'>
        <h6 class="mb-0 font-weight-light">Driving License</h6>
        <small class='font-weight-bold'>Front and back</small>
      </div>
      <v-icon
      class="vertif-option-icon-action ml-auto"
      :style="{color: drivingLicense.verified ? 'rgb(41, 207, 91) !important' : '#2575b3'}"
      >
      mdi-{{ !drivingLicense.verified ? 'chevron-right' : 'check-circle-outline'}}
    </v-icon>
  </div>
  <div @click="gotoKycInputs(3)" class='vertif-option d-flex'>
    <v-icon class="vertif-option-icon">
      mdi-card-account-details
    </v-icon>
    <div class='vertif-option-label'>
      <h6 class="mb-0 font-weight-light">Identity Card</h6>
      <small class='font-weight-bold'>Front and back</small>
    </div>
    <v-icon
    class="vertif-option-icon-action ml-auto"
    :style="{color: id.verified ? 'rgb(41, 207, 91) !important' : '#2575b3'}"
    >
    mdi-{{ !id.verified ? 'chevron-right' : 'check-circle-outline'}}
  </v-icon>
</div>
</div>
</div>
<div class='acc-vertif-inputs' v-if="kycPage && kycInputs === 1">
  <div class='text-center mb-5'>
    <v-icon style="font-size:72px" class="vertif-option-icon">
      mdi-badge-account
    </v-icon>
    <h5 class='text-white mt-3'>Verifying by Passport</h5>
    <small>Make sure the image is not blurry and everything on it is visible.</small>
  </div>

  <b-form-group
  id="driving-front"
  class="mb-4"
  label="Passport"
  label-for="driving-front">
  <b-form-file
  accept=".jpg, .png"
  v-model="pasport.front"
  :state="Boolean(pasport.front)"
  placeholder="Choose an image or drop it here..."
  drop-placeholder="Drop image here..."
  ></b-form-file>
</b-form-group>

<div class='d-flex justify-content-center align-center'>
  <button
  class="submit mt-4 mb-4 mx-auto"
  @click='gotoKycInputs(0)'
  >Back
  </button>

  <button
  class="submit mt-4 mb-4 mx-auto"
  @click='verifyClick("passport")'
  :disabled='!pasport.front'
  :class="!pasport.front ? 'disabled' : ''"
  >Verify
</button>

</div>
</div>
</div>
<div class='acc-vertif-inputs' v-if="kycPage && kycInputs === 2">
  <div class='text-center mb-5'>
    <v-icon style="font-size:72px" class="vertif-option-icon">
      mdi-car-side
    </v-icon>
    <h5 class='text-white mt-3'>Verifying by Driving License</h5>
    <small>Make sure both images are not blurry and the corners of the card are visible.</small>
  </div>

  <b-form-group
  id="driving-front"
  class="mb-4"
  label="Driving License - Front"
  label-for="driving-front">
  <b-form-file
  accept=".jpg, .png"
  v-model="drivingLicense.front"
  :state="Boolean(drivingLicense.front)"
  placeholder="Choose an image or drop it here..."
  drop-placeholder="Drop image here..."
  ></b-form-file>
</b-form-group>

<b-form-group
id="driving-back"
class="mb-4"
label="Driving License - Back"
label-for="driving-back">
<b-form-file
accept=".jpg, .png"
v-model="drivingLicense.back"
:state="Boolean(drivingLicense.back)"
placeholder="Choose an image or drop it here..."
drop-placeholder="Drop file here..."
></b-form-file>
</b-form-group>
<div class='d-flex justify-content-center align-center'>
  <button
  class="submit mt-4 mb-4 mx-auto"
  @click='gotoKycInputs(0)'
  >Back
  </button>

  <button
  class="submit mt-4 mb-4 mx-auto"
  @click='verifyClick("drivinglicense")'
  :disabled='!(drivingLicense.front && drivingLicense.back)'
  >Verify
</button>
</div>
</div>
<div class='acc-vertif-inputs' v-if="kycPage && kycInputs === 3">
  <div class='text-center mb-5'>
    <v-icon style="font-size:72px" class="vertif-option-icon">
      mdi-card-account-details
    </v-icon>
    <h5 class='text-white mt-3'>Verifying by Identity Card</h5>
    <small>Make sure both images are not blurry and the corners of the card are visible.</small>
  </div>

  <b-form-group
  id="id-front"
  class="mb-4"
  label="Identity Card - Front"
  label-for="id-front">
  <b-form-file
  accept=".jpg, .png"
  v-model="id.front"
  :state="Boolean(id.front)"
  placeholder="Choose an image or drop it here..."
  drop-placeholder="Drop image here..."
  @change="changeProgress"
  ></b-form-file>
</b-form-group>

<b-form-group
id="id-back"
class="mb-4"
label="Identity Card - Back"
label-for="id-back">
<b-form-file
accept=".jpg, .png"
v-model="id.back"
:state="Boolean(id.back)"
placeholder="Choose an image or drop it here..."
drop-placeholder="Drop file here..."
@change="changeProgress"
></b-form-file>
</b-form-group>
<div class='d-flex justify-content-center'>
  <button
  class="submit mt-4 mb-4 mx-auto"
  @click='gotoKycInputs(0)'
  >Back
  </button>

  <button
  class="submit mt-4 mb-4 mx-auto"
  @click='verifyClick("id")'
  :disabled='!(id.front && id.back)'
  >Verify
</button>
</div>
</div>
</template>
</b-modal>
</template>


<style lang="scss">
@import '~/variables';
@import '~/modal_css';

#kyc-modal .modal-dialog .modal-content {
  max-width: 500px !important;
  width: 100% !important;

  .modal-body {
    /*min-height: 400px !important;*/

    .submit {
      border: none !important;
      font-size: 14px !important;
      font-weight: 500;
      color: #c4c4c4;
      background-color: #414141 !important;
      height: 40px;
      width: 200px;
      border-radius: 10px;

      transition: .3s all;
    }


    .submit:hover {
      background-color: #4b4b4b !important;
    }

    #acc-vertif-main-page {
      small,
      p {
        color: #a7a7a7 !important;
      }

      .shield-logo::before {
        background-color: #242424 !important;
        line-height: 1 !important;
        border-radius: 50%;
        padding: 10px;
        font-size: 150px;
        margin: 50px auto;
        margin-bottom: 30px;
      }
    }

    #acc-vertif-content  {
      .vertif-option {
        margin: 30px auto;
        padding: 5px;
        width: 100%;
        border: 1px solid #303030;
        border-radius: 5px;
        cursor:pointer;

        &:hover {
          background: rgba(255,255,255,0.12);
        }

        .vertif-option-label {
          margin-left: 15px;
          color: #fff;
          padding: 10px 0px;
        }

        .vertif-option-icon,
        .vertif-option-icon-action {
          padding: 5px !important;
          line-height: 1.2 !important;
          font-size: 42px;
        }

        .vertif-option-icon-action {
          background: transparent;
          transition: .2s ease-in-out;
          border: none;
          color: #2575b3;
          cursor: pointer;
          border-radius: 50px;
        }

        .vertif-option-icon-action::before {
          padding: 0px 5px;
        }

        .vertif-option-icon-action:hover {
          background-color: #303030;
        }
      }
    }

    .acc-vertif-inputs .form-group {
      label {
        font-size: 14px;
        color: #A2A2A2 !important;
      }
      .custom-file {
        color: #cccccc !important;
        border-radius: 2px !important;
        font-size: 13px !important;
        height: 52px;

        .custom-file-label,
        .custom-file-label::after {
          height: 52px;
          line-height: 2.5;
          border-radius: 2px;
          background-color: #282828;
          transition: all 0.05s ease-in;
          width: 100%;
          color: #a7a7a7 !important;
        }

        .custom-file-label::after {
          content: none;
        }

        .custom-file-label:hover {
          cursor: pointer;
          background-color: #2e2e2e !important;
        }
      }

      .cus-input {
        font-size: 13px;
        height: 52px;
        padding: 0px 16px;
        transition: all .05s ease-in;
        width: 100%;
        color: $topbar_secondary_fcolor;
        background-color: #282828;
        border-radius: 2px;
      }
    }

    #steps {
      max-width: 768px !important;
      margin: auto;
      padding: 15px;
      position: relative;

      .progress {
        max-height: 3px !important;
        .progress-bar {
          background-color: #2688d3;
        }
      }

      #step-btns {
        .v-icon {
          transition: all .15s ease-in-out;
          border: none;
          padding: 0 !important;
          margin: 0 !important;
          background: none;
        }

        .v-icon::before {
          z-index: 999;
          font-size: 32px;
          border-radius: 100px;
          background-color: #1e1e1e !important;
        }
      }
    }
  }
}

</style>

<script>
import {  kycModal } from '../../plugins/modals.js';
export default {
  name: 'KYCModal',
  data() {
    return {
      kycDone: false,
      kycPage: false,
      kycInputs: 0,
      identityVerified: false,
      maxSteps: 3,
      activeStep: 0,
      pasport: {
        verified: false,
        front: null,
      },
      drivingLicense: {
        verified: false,
        front: null,
        back: null,
      },
      id: {
        verified: false,
        front: null,
        back: null,
      },
    }
  },
  methods: {
    c() {
      kycModal.hideModal()
    },

    verifyClick(at) {
      let th = this
      let formData = new FormData();
      formData.append('at', at)

      if(at == 'passport') {
        formData.append('front', this.pasport.front)
      } else if(at == 'drivinglicense') {
        formData.append('front', this.drivingLicense.front)
        formData.append('back', this.drivingLicense.back)
      } else if(at == 'id') {
        formData.append('front', this.id.front)
        formData.append('back', this.id.back)
      }

      this.$http.post('/api/user/kyc/update',
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then((res) => {
          if(res.status == 200) {
            th.kycPage = false
            th.kycDone = true

            th.$store.state.user.kyc_user_done = true
          }
        }).catch(err => {
          if(err.status == 400) {
            // bad
          }
        })

    },

    test() {
      alert(1)
    },
    gotoKycInputs(c) {
      this.kycInputs = c;
    },
    goToVertification() {
      this.kycPage = !this.kycPage;
    },
    changeProgress(event) {
      const {length} = event.target.files;

      if(length === 0)
      this.activeStep -= 1;
      else if(length === 1) {
        if(this.activeStep < this.maxSteps) {
          this.activeStep += 1;
        }
      }
    },
    showModal() {
      this.kycPage = false;
      this.kycInputs = 0;
      this.id.front = null;
      this.id.back = null;
      this.drivingLicense.front = null;
      this.drivingLicense.back = null;
      this.$refs['kyc-modal'].show()
      this.kycDone = !this.$store.getters.get_user_data('kyc').verified
    }
  },
  created (){
    let that = this
  },
  beforeMount() {
    let that = this
    kycModal.event.$on('show-kyc-modal', () => this.showModal())
  },
}
</script>
