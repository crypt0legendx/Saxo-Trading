<template>
  <transition name="fasada" class="disable-page">
    <div class="disable-page">
        <div>
            <v-icon>mdi-close</v-icon>
        </div>
        <span> Sorry but unfortunately, we don't yet support your browser. </span>
        <span> While we are working on it, please download either Chrome or Firefox </span>

        <div class="icon-group">
          <a href="https://www.google.com/chrome/"><v-icon>mdi-google-chrome</v-icon></a>
          <a href="https://www.mozilla.org/en-US/firefox/new/"><v-icon>mdi-firefox</v-icon></a>
        </div>
    </div>
  </transition>
</template>

<style lang="scss">
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

    .icon-group {
        margin-top:15px;

        a {
          &:first-of-type i{
            color: #4dffb9;

            &:hover {
              color:#4dbaff !important;
            }
          }

          i {
            cursor:pointer;
            margin-left:15px;
            margin-right:15px;
            color: #ffac26;
            transition: .4s;

            &:hover {
              color:#ff5700;
            }

        }
      }
    }

    i {
        font-size:3.5rem;
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
        margin-top: 10px;
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
  name: "BrowserNotSupported",
}
</script>
