<template>
  <transition name="fasada" class="disable-page">
    <div class="disable-page">
      <div v-if="!$store.state.done.connection_failed" style="width: 3rem; height: 3rem;" role="status">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        width="74px" height="55px" viewBox="0 0 24 30" style="enable-background:new 255 255 255 0;" xml:space="preserve">
        <rect x="0" y="10" width="4" height="10" fill="#fff" opacity="0.2">
          <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0s" dur="0.6s" repeatCount="indefinite" />
          <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0s" dur="0.6s" repeatCount="indefinite" />
          <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0s" dur="0.6s" repeatCount="indefinite" />
        </rect>
        <rect x="8" y="10" width="4" height="10" fill="#fff"  opacity="0.2">
          <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.15s" dur="0.6s" repeatCount="indefinite" />
          <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.15s" dur="0.6s" repeatCount="indefinite" />
          <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.15s" dur="0.6s" repeatCount="indefinite" />
        </rect>
        <rect x="16" y="10" width="4" height="10" fill="#fff"  opacity="0.2">
          <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.3s" dur="0.6s" repeatCount="indefinite" />
          <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.3s" dur="0.6s" repeatCount="indefinite" />
          <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.3s" dur="0.6s" repeatCount="indefinite" />
        </rect>
      </svg>
    </div>
    <div v-else>
      <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
      width="74px" height="55px" viewBox="0 0 130.2 130.2">
      <circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
      <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/>
      <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/>
    </svg>
  </div>

  <span v-if="!$store.state.done.connection_failed"> Estabilishing connection with the server.. </span>
  <span v-else> Failed to connect to the server. </span>
</div>
</transition>
</template>

<style lang="scss">
.path {
  stroke-dasharray: 1000;
  stroke-dashoffset: 0;
  &.circle {
    -webkit-animation: dash .5s ease-in-out;
    animation: dash .5s ease-in-out;
  }
  &.line {
    stroke-dashoffset: 1000;
    -webkit-animation: dash .5s .35s ease-in-out forwards;
    animation: dash .5s .35s ease-in-out forwards;
  }
  &.check {
    stroke-dashoffset: -100;
    -webkit-animation: dash-check .5s .35s ease-in-out forwards;
    animation: dash-check .5s .35s ease-in-out forwards;
  }
}

@-webkit-keyframes dash {
  0% {
    stroke-dashoffset: 1000;
  }
  100% {
    stroke-dashoffset: 0;
  }
}

@keyframes dash {
  0% {
    stroke-dashoffset: 1000;
  }
  100% {
    stroke-dashoffset: 0;
  }
}

@-webkit-keyframes dash-check {
  0% {
    stroke-dashoffset: -100;
  }
  100% {
    stroke-dashoffset: 900;
  }
}

@keyframes dash-check {
  0% {
    stroke-dashoffset: -100;
  }
  100% {
    stroke-dashoffset: 900;
  }
}
.fasada-leave-active,
.fasada-enter-active {
  opacity:0;
  transition: .8s;
}
.fasada-enter {
  opacity: 1;
}

.fasada-leave-to {
  opacity: 0;
}

.disable-page {
  user-select:none;

  i {
    font-size:2.5rem;
  }

  display:flex;
  justify-content:center;
  align-items:center;
  flex-direction:column;
  color:white;
  position:absolute;
  top:0;
  left:0;
  z-index:9999999;
  width:100%;
  height:100%;
  background: rgba(0,0,0,1);
  transition: .5s all;

  span {
    margin-top: 25px;
    margin-left: 6px;
    text-align: center;
    font-size: 18px;
    font-family: 'Roboto';
    font-weight: 200;
  }
}

</style>

<script>
export default {
  name: "ConnectionFailed",
}
</script>
