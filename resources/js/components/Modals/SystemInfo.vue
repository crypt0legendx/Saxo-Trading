<template>
  <b-modal
  id='system-info-modal'
  ref="system-info-modal"
  size="sm"
  hide-footer
  no-close-on-backdrop
  centered
  no-fade
  >
  <template v-slot:modal-title><v-icon>mdi-monitor</v-icon> System Information</template>
  <template v-slot:modal-header-close><v-icon>mdi-close</v-icon></template>
  <template v-slot:default>
    <v-container class="sys-info">
      <div class="group">
        <h4>Connection Status</h4>
        <span style="text-transform: uppercase">
          <span :style="$store.state.done.connected ? 'color: green' : 'color: red'">
            {{$store.state.done.connected && !$store.state.done.connetion_failed ? 'Connected' : 'Not Connected'}}
          </span>
        </span>
      </div>

      <div class="group">
        <h4>Latency</h4>
        <span v-html="latency">
        </span>
      </div>

    <hr/>

    <div class="group">
      <h4>Account ID</h4>
      <span>
          #{{$store.state.user.id}}
      </span>
    </div>

    <div class="group">
      <h4>Username</h4>
      <span>
          {{$store.state.user.email}}
      </span>
    </div>

    <hr/>

    <div class="group">
      <h4>Application Version</h4>
      <span>
          {{$store.state.application_ver}}
      </span>
    </div>

    <div class="group">
      <h4>Browser Version</h4>
      <span>
          {{browser_version}}
      </span>
    </div>

    <div class="group">
      <h4>Platform</h4>
      <span>
          {{os}}
      </span>
    </div>

  </v-container>
</template>
</b-modal>
</template>

<style lang='scss'>
@import '~/variables';
@import '~/modal_css';

#system-info-modal .modal-dialog .modal-content {
  opacity: 0;
}

.sys-info {
  margin:2px 0 !important;
  font-family: 'Roboto';
  padding:0 !important;
  display:flex;
  flex-direction:column;

  hr {
    border-top: 1px solid rgb(177 177 177 / 14%);
    width: 100%;
  }

  .group {
    margin:8px 0;
    padding:0px 20px !important;
    display:flex;
    flex-direction:row;
    justify-content:space-between;
    width:100%;

    h4 {
      font-size:13px;
      margin: 0 !important;
    }

    span {
      font-size:12px;
    }
  }
}

</style>

<script>
import {sysInfo} from '../../plugins/modals.js';

export default {
  name: 'SystemInfoModal',
  methods: {
    showModal() {
      this.$refs['system-info-modal'].show()
      setTimeout(() => {
        document.querySelector('#system-info-modal___BV_modal_outer_ #system-info-modal').classList.toggle('light-theme', this.$store.state.light_theme);
        document.querySelector('#system-info-modal___BV_modal_outer_ #system-info-modal .modal-dialog .modal-content').style.opacity = '1';
      }, 150);
    },

    hideModal() {
      this.$refs['system-info-modal'].hide()
    },

    system() {
      var nVer = navigator.appVersion;
      var nAgt = navigator.userAgent;
      var browserName  = navigator.appName;
      var fullVersion  = ''+parseFloat(navigator.appVersion);
      var majorVersion = parseInt(navigator.appVersion,10);
      var nameOffset,verOffset,ix;

      if ((verOffset=nAgt.indexOf("Opera"))!=-1) {
       browserName = "Opera";
       fullVersion = nAgt.substring(verOffset+6);
       if ((verOffset=nAgt.indexOf("Version"))!=-1)
         fullVersion = nAgt.substring(verOffset+8);
      }
      else if ((verOffset=nAgt.indexOf("MSIE"))!=-1) {
       browserName = "Microsoft Internet Explorer";
       fullVersion = nAgt.substring(verOffset+5);
      }

      else if ((verOffset=nAgt.indexOf("Chrome"))!=-1) {
       browserName = "Chrome";
       fullVersion = nAgt.substring(verOffset+7);
      }
      else if ((verOffset=nAgt.indexOf("Safari"))!=-1) {
       browserName = "Safari";
       fullVersion = nAgt.substring(verOffset+7);
       if ((verOffset=nAgt.indexOf("Version"))!=-1)
         fullVersion = nAgt.substring(verOffset+8);
      }
      else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) {
       browserName = "Firefox";
       fullVersion = nAgt.substring(verOffset+8);
      }
      else if ( (nameOffset=nAgt.lastIndexOf(' ')+1) <
                (verOffset=nAgt.lastIndexOf('/')) )
      {
       browserName = nAgt.substring(nameOffset,verOffset);
       fullVersion = nAgt.substring(verOffset+1);
       if (browserName.toLowerCase()==browserName.toUpperCase()) {
        browserName = navigator.appName;
       }
      }
      if ((ix=fullVersion.indexOf(";"))!=-1)
         fullVersion=fullVersion.substring(0,ix);
      if ((ix=fullVersion.indexOf(" "))!=-1)
         fullVersion=fullVersion.substring(0,ix);

      majorVersion = parseInt(''+fullVersion,10);
      if (isNaN(majorVersion)) {
       fullVersion  = ''+parseFloat(navigator.appVersion);
       majorVersion = parseInt(navigator.appVersion,10);
      }

      var OSName="Couldn't identify your OS";
      if (navigator.appVersion.indexOf("Win")!=-1) OSName="Windows";
      if (navigator.appVersion.indexOf("Mac")!=-1) OSName="MacOS";
      if (navigator.appVersion.indexOf("X11")!=-1) OSName="UNIX";
      if (navigator.appVersion.indexOf("Linux")!=-1) OSName="Linux";

      this.browser_version = browserName + ' ' + fullVersion
      this.os = OSName
    }
  },
  beforeMount() {
    this.system()
    sysInfo.event.$on('show-system-info-modal', () => {
      this.showModal();
    })
  },

  data: function() {
    return {
      browser_version : '',
      os: '',
    }
  },

  computed : {
    latency() {
      let lat = this.$store.state.latency

      if(lat == 0)
        lat = `<span style='color: yellow'>${lat}ms</span>`
      if(lat > 0 && lat < 80)
        lat = `<span style='color: green'>${lat}ms</span>`
      if(lat > 80)
        lat = `<span style='color: red'>${lat}ms</span>`

        return lat
    }
  },
}
</script>
