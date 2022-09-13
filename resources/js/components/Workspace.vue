<template>
    <v-container>
        <div id="workspace">
            <div class="workspace-content w-100 h-100">
              <div v-show="!workspace_loaded" class="grid-empty">
                Your workspace is empty.<br>
                You can start adding windows from the navigation bar on the left.
              </div>

              <BigAlert
                v-show="verif_show && !email_verif_show"
                content="To activate your trading account, please"
                icon="alert-rhombus"
                :buttons="btns"
                >
              </BigAlert>

              <BigAlert
                v-show="email_verif_show"
                content="Your email address is not verified. We trust and privilege verified users."
                icon="alert-rhombus"
                :buttons="btns_email"
                >
              </BigAlert>
              <Grid />
            </div>
        </div>
    </v-container>
</template>

<style lang="scss">
@import '../scss/_variables.scss';
@media (max-width:481px) {
    #workspace {
        background: #282828;
        overflow-y: auto !important;
    }
}

#workspace {
    background: $workspace_bg;
    z-index: -1;
    left: 0;
    top: 0;
    position: absolute;
    width: 100%;
    height: 100vh;
    overflow: hidden;


    .workspace-content {
        padding-top: 42px;
        background: $workspace_bg;
        padding-left: 42px;


        .grid-empty {
          position: absolute;
          text-align:center;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          display: flex;
          justify-content: center;
          align-items: center;
          color: #b8b8b8;
          background: transparent !important;
        }
    }
}
</style>

<script>
import Grid from './Grid/Grid.vue'
import BigAlert from './BigAlert.vue'
export default {
    name: "Workspace",
    components : {
        Grid,
        BigAlert
    },

    data() {
      return {
        verif_show: false,
        email_verif_show: false,



        btns: [
          '<button id="upload_docs" class="alert_btn">Upload your documents</button>',
          '<button id="make_payment" class="alert_btn">Make your first payment</button>'
        ],

        btns_email: [
          '<button id="send_verif_mail" class="alert_btn">Send verification email</button>'
        ],
      }
    },

    watch: {
        '$store.state.user.kyc_user_done' : function() {
          this.verif_show = !this.$store.state.user.kyc_user_done
        },

        '$store.state.user.email_verified' : function() {
          this.email_verif_show = !this.$store.state.user.email_verified
        },
    },

    computed: {
        workspace_loaded() {
          var dd = this.$store.state.workspace[this.$store.state.user.vue_data.current_workspace];
          if(dd.layout == null) return;

        }
    },

    created() {
      let th = this

      if(!this.$store.state.user.kyc_user_done) {
        this.verif_show = true
      }

      if(!this.$store.state.user.email_verified) {
        this.email_verif_show = true
      }

      document.addEventListener('click',function(e){
        if(e.target && e.target.id == "upload_docs") {
          th.$kycModal.show()
        } else if(e.target && e.target.id == "send_verif_mail") {
          th.$http.post('/api/user/everify/send').then((res) => {
            if(res.data.error != undefined) {
              console.log('verification');
              th.$sound.play('error.mp3')
               this.$dtoast.pop({
                  preset: "error",
                  heading: `Workspace Error`,
                  content:  res.data.error,
                 })
              return;
            }

            if(res.data.success != undefined) {
              th.$sound.play('success.mp3')
               this.$dtoast.pop({
                  preset: "info",
                  heading: `Account Verification`,
                  content:  `Verification email sent. Check your inbox`,
                 })
            }

            th.email_verif_show = false
          })
        }
      })
    },

    methods : {

    },


}
</script>
