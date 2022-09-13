<template>
   <transition name="slide">
  <div v-show="shown" @click="shown = false" class="bigalert">
    <span class="icon">
      <v-icon>mdi-{{icon}}</v-icon>
    </span>

    <span class="content">
        {{content}}
    </span>

    <div class="buttons d-flex flex-row ml-2 mr-2">
        <div v-for="button in buttons">
          <div v-html="button"></div>
        </div>
    </div>

    <span @click="hide" class="close_btn">
      <v-icon>mdi-close</v-icon>
    </span>

  </div>
</transition>
</template>

<script>
export default {
  name: 'BigAlert',
  data() {
    return {
      shown: true,
    }
  },

  created() {

  },

  methods: {
    hide() {
      this.shown = false;
    },
  },

  props: {
    buttons: Array,
    icon: String,
    content: String,
  },
}
</script>

<style lang="scss">
.slide-leave-active,
.slide-enter-active {
  transition: .3s;
}
.slide-enter {
  transform: translate(0, -100%);
}
.slide-leave-to {
  transform: translate(0, -100%);
}

.light-theme .bigalert {
  background: rgb(254, 242, 218) !important;

  .content {
    color:black !important;
  }

  .close_btn {
    color:black;
  }

  .alert_btn {
    border: 1px solid #ffc251;
    color: #ff7b00;
    transition: .2s all;

    &:hover {
      background: #ffe8a0;
      color: #896800;
    }
  }

}


.bigalert:hover {
    opacity:.9;
    transition:.5s;
}

.bigalert {
  cursor:pointer;
  font-family: 'Roboto';
  display:flex;
  align-items:center;
  position:absolute;
  z-index:9999;
  width:100%;
  height:55px;
  padding: 0 20px;
  background: rgb(45 45 45);

  .close_btn {
    justify-self: self-end;
    right: 60px;
    position: absolute;
    color:white;
    cursor:pointer;

    &:hover {
      opacity:.5;
    }
  }

  .content {
    font-size:15px;
    color: #d5d5d5;
  }

  .alert_btn {
    border: 1px solid #888888;
    background: none;
    padding: 9px 30px;
    border-radius: 5px;
    text-transform: uppercase;
    font-size: 13px;
    font-weight: 600;
    color: #bcbcbc;
    transition: .2s all;

    &:hover {
      background: #494949;
      color: #ffffff;
    }
  }

  .buttons {
    div {
      margin: 0 3px;
    }
  }

  .icon {
    color: #ffc150;
    font-size:30px;
    margin-right:20px;
  }
}
</style>
