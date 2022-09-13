<template>
  <transition name="fasada" class="disable-page">
    <div class="pl-2 pr-2 disable-page">
        <span class="mt-3 mb-5" style="font-size:1.85rem"> You can profit even more on our mobile app!  </span>
        <span> Download it from:  </span>

        <div class="mt-4 d-flex justify-content-center w-100">
          <a class="ml-3 mr-3" style="width:180px" href="#">
            <img width="180px" src="/images/gplay.svg"/>
          </a>
          <a class="ml-3 mr-3" href="#">
            <img width="180px" src="/images/astore.svg"/>
          </a>
        </div>
    </div>
  </transition>
</template>

<style lang="scss" scoped>
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
        margin-top:25px;

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
  name: "MobileDevice",
}
</script>
