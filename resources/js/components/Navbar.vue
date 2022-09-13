<template>
  <v-container>
    <div class="navbar fluid">
      <div class="navbar-brand_">
          <div class="nav-entry  mt-1 --medium">
            <v-icon>mdi-vuejs</v-icon>
          </div>
        </div>

        <div class="icons-top">

        <div id="sidebar-toggle" class="action_add nav-entry nav-multiple mt-2 pt-1 pb-1 --medium">
          <i class="w-100 h-100 font-icon icon_action_add" @click="openSideBar"></i>

          <div data="sidebar-menu" v-show="nav_side_shown" class="nav-multiple-items" style="margin-top: -5px;">
            <div @click="onOrderEntryClick" class="nav-multiple-item nav-multiple"><v-icon>mdi-card-text-outline</v-icon> Order Entry</div>
            <div data="side-toggle" class="nav-multiple-item nav-multiple --nested">
              <v-icon>mdi-clipboard-list-outline</v-icon> Watchlist
              <div data="list" class="nav-multiple-items" >
                <div v-if="new_watchlist" class="nav-multiple-item d-flex justify-content-between flex-row align-items-center w-100">
                  <input v-on:keyup.enter="newWatchCat" class="watchlist-input" type="text" v-model="new_watchlist_input" placeholder="ex. Watchlist 1" autofocus/>
                  <v-icon @click.native="new_watchlist = false">mdi-close</v-icon>
                </div>

                <div v-for="(cat,index) in $store.state.user.vue_data.favourites" class="nav-multiple-item">
                  <template v-if="watchlist_rename_id != index">

                  <v-icon>mdi-clipboard-list-outline</v-icon>
                  <span class="d-flex justify-content-between flex-row w-100 align-items-center">
                     <span style="width:90px" @click="watchClick(cat.name)">{{cat.name}}</span>

                     <span>
                       <v-icon @click.native="renameWatchlist(cat.name)">mdi-pencil-outline</v-icon>
                       <v-icon @click.native="rmWatchlist(cat.name)">mdi-close</v-icon>
                     </span>
                   </span>
                 </template>
                 <template v-else>
                   <div class="d-flex justify-content-between flex-row align-items-center w-100">
                     <input v-on:keyup.enter="renameWatch(cat.name)" class="watchlist-input" type="text" v-model="watchlist_rename_input" placeholder="ex. Watchlist 1" autofocus/>
                     <v-icon @click.native="watchlist_rename_id = -1">mdi-close</v-icon>
                   </div>
                 </template>
                </div>

                <div @click="onNewWatchClick" class="nav-multiple-item"><v-icon>mdi-plus</v-icon> Create New Watchlist</div>
              </div>
            </div>
            <div data="side-toggle" class="nav-multiple-item nav-multiple --nested">
              <v-icon>mdi-finance</v-icon> Chart

              <div data="list" class="nav-multiple-items --list p-1">
                <InstrumentSelect v-on:stockSelected="onChartCreateClick"/>
              </div>

            </div>

            <div class="nav-multiple-item" @click="onOrdersClick"><v-icon>mdi-vector-polyline-plus</v-icon> Positions</div>
            <div class="nav-multiple-item" @click="$nlogs.show()"><v-icon>mdi-bell-alert-outline</v-icon> Notifications Log</div>
            <div class="nav-multiple-item" @click="onHistoryClick"><v-icon>mdi-history</v-icon> History</div>
            <div class="nav-multiple-item" @click="onInstrumentsClick"><v-icon>mdi-format-list-bulleted-triangle</v-icon> Instruments</div>
            <div  data="side-toggle" class="nav-multiple-item nav-multiple --nested">
              <v-icon>mdi-remote-desktop</v-icon> Fundamentals
              <div data="list" class="nav-multiple-items --list p-1">
                <InstrumentSelect filter="fundamentals" v-on:stockSelected="onChartCreateClick"/>

              </div>
            </div>
            <div class="nav-multiple-item" @click="onCalendarClick"><v-icon>mdi-calendar</v-icon> Economic Calendar</div>
            <div class="nav-multiple-item" @click="onClockClick"><v-icon>mdi-clock-outline</v-icon> Clock</div>
            <div class="nav-multiple-item" @click="onNewsClick"><v-icon>mdi-newspaper-variant-multiple-outline</v-icon> News</div>
            <div class="nav-multiple-item" @click="clientPosClick"><v-icon>mdi-poll</v-icon> Client Positions</div>
          </div>
        </div>

        <div @click="$bidModal.show()" class="nav-entry mt-2 pt-2 pb-2" title='Create a new order'>
          <i class="font-icon icon_new_order"></i>
        </div>

        <div class="nav-entry pt-2 pb-2" title='Account Performance' @click="$accModal.show()">
          <i class="font-icon icon_analysis"></i>
        </div>

        <div @click="$router.push({ name: 'Dashboard' }).catch(err => {})"  class="nav-entry pt-2 pb-2" title='Deposit or Withdraw money'>
          <i class="font-icon icon_deposit_withdraw"></i>
        </div>

        <div class="nav-entry pt-2 pb-2" title='Edit price alerts' @click="$alerts_Modal.show()">
          <i class="font-icon icon_bell"></i>
        </div>

        <div v-show="$store.getters.get_minimized_windows.length > 0" class="nav-entry pt-2 pb-2" title='Minimzed Windows' @click="toggleMinimized">
          <v-icon>mdi-minus</v-icon> <b-badge variant="dark">{{$store.getters.get_minimized_windows.length > 9 ? '9+' : $store.getters.get_minimized_windows.length}} <span class="sr-only">m.w.</span></b-badge>
          <div style="position:absolute; margin-left:40px">
            <Dropdown items_style="background:#393939" v-on:handleClick="minItemClicked" :items="minimized_items" v-show="minimized_opened" />
          </div>
        </div>

      </div>

      <div class="icons-bottom">
        <div class="nav-entry pt-3 pb-3" title='Settings' @click="$settModal.show()">
          <i class="font-icon icon_settings "></i>
        </div>

        <!--
        <div class="nav-entry pt-3 pb-3" title='QuickTrade Settings'>
        <v-icon>mdi-gesture-double-tap</v-icon>
      </div>-->

    <div class="nav-entry pt-3 pb-3" title='Switch Themes' @click="toggleTheme()">
      <i class="font-icon icon_light_dark_theme"></i>
    </div>

  <div class="nav-entry pt-3 pb-3" title='LiveChat'>
    <i class="font-icon icon_livechat "></i>
  </div>

  <div @click="openFeedBack" class="nav-entry pt-3 pb-3" title='Feedback'>
    <i class="font-icon icon_news "></i>
  </div>

  <div class="nav-entry pt-3 pb-3" title='Help'>
    <i class="font-icon icon_help_link "></i>
  </div>
</div>
</div>
</v-container>
</template>

<style lang="scss">
@import '../scss/_variables.scss';

.sidebar-leave-active,
.sidebar-active {
  transition: 1s;
}

.sidebar-enter {
  transform: translateX(0%);
}
.sidebar-leave-to {
  transform: translateX(-100%);
}

.watchlist-input {
  border: 1px solid #7d7d7d;
  background: none;
  border-radius: 3px;
  /* width: 100%; */
  color: #aaaaaa !important;
}

.badge {
  position: absolute;
  padding: 3px 5px;
  font-size: 11px;
  margin-left: 19px;
  margin-top: -7px;
  border-radius: 50%;

  &.badge-dark {
    color: #000;
    background-color: #ffde3f;
  }
}


[data='list'] {
  display:none;
}

.--list {
  max-height:200px !important;

}

.list-content {
  max-height:158px;
  overflow-y:auto;

  &::-webkit-scrollbar {
    width: 5px;
  }

  /* Track */
  &::-webkit-scrollbar-track {
    background: none;
  }

  /* Handle */
  &::-webkit-scrollbar-thumb {
    background: #888;
  }

  /* Handle on hover */
  &::-webkit-scrollbar-thumb:hover {
    background: #555;
  }
}

.stock-entry {
  display:flex;
  justify-content:space-between !important;
  padding:0px 6px !important;

  span {
    font-size:13px;
  }

  &:hover {
    background: $navbar_menus_bg_hover;
  }
}

.nav-multiple-item--custom {
  display:flex;
  width:100%;
  height:35px;
  font-size:13px;
  font-family: 'Roboto', sans-serif;
  align-items:center;
  justify-content:center;
}

.search_stocks {
  height:100%;
  width:100%;
  background:$input_search_stocks_bg;
  border:none;
  padding:5px;
  color:$topbar_fcolor;
  transition: .2s all;

  &:focus {
    border:none;
    outline:none;
  }
}


.--nested:after {
  position:absolute;
  right:10px;
  content: ' ';
  border: 1px solid  #a7a7a7;
  border-width: 0 1px 1px 0;
  display: inline-block;
  padding: 3px;
  transform: rotate(-45deg);
  -webkit-transform: rotate(-45deg);
}

.nav-multiple.--nested > .nav-multiple-items,
{
  left:180px !important;
}

.nav-multiple-items {
  user-select:none;
  position:absolute;
  left:40px;
  border-radius: 0px;

  -webkit-box-shadow: 1px 1px 5px 0px rgba(0,0,0,0.45);
  -moz-box-shadow: 1px 1px 5px 0px rgba(0,0,0,0.45);
  box-shadow: 1px 1px 5px 0px rgba(0,0,0,0.45);

  .nav-multiple-item {
    display:flex;
    width:180px;
    height:25px;
    font-size:13px;
    font-family: 'Roboto', sans-serif;
    padding:0px 5px;
    align-items:center;

    i {
      margin-right:5px;
      font-size:18px;
    }

    &:hover {
      background: $navbar_menus_bg_hover;
    }
  }
}


.navbar {
  left:0;
  top:0;
  position:fixed;
  width:40px;
  height:100%;
  padding:0 !important;

  display:flex;
  flex-direction: column;

  justify-content:flex-start;
  align-items:center;



  .navbar-brand_, .icons-top, .icons-bottom {
    width:100%;

    .nav-entry {
      text-align: center;
    }
  }

  .navbar-brand_ {
    flex-grow:0.1;
    margin-top:0px;
    .nav-entry {
      padding: 5px 0;
    }

    i {
      font-size:2rem !important;
    }
  }

  .icons-top {
    flex-grow:1;
  }


  .icons-bottom {
    .nav-entry {
      padding-left: 10px;
      padding-right:10px;
    }
  }

  .nav-entry.action_add {
    margin-bottom:20px;
  }


  .nav-entry {
    cursor:pointer;;
    display:flex;
    justify-content:center;
    transition: .2s all;

    i {
      font-size:1.4rem;
    }

    i.icon_action_add {
      font-size:1.8rem;
    }
  }
}

</style>

<script>
import InstrumentSelect from './InstrumentsSelect'
import Dropdown from './Dropdown'

export default {
  name : "Navbar",

  components: {
    InstrumentSelect,
    Dropdown,
  },

  data: function() {
    return {
      minimized_opened : false,
      nav_side_shown : false,
      minimized_items : [],

      new_watchlist: false,
      new_watchlist_input : '',

      watchlist_rename_id : -1,
      watchlist_rename_input : '',
    }
  },

  mounted() {
    let th = this
    document.querySelectorAll("[data='side-toggle']").forEach(function(ele){

      if(/Mobi/.test(navigator.userAgent)) {
        ele.addEventListener('click', function(e){
          e.stopPropagation()
          if(e.target.className.includes('watchlist-input') ||
             e.target.className.includes('v-icon'))  {
               return;
             }

          if(!e.target.getAttribute('data')) return

          let list = ele.querySelector("[data='list']");
          if(list.style.display == 'none' || list.style.display == '')
          list.style.display = 'block'
          else
          list.style.display = 'none'
        });
      } else {
        ele.addEventListener('click', function(e){
          e.stopPropagation()
          e.preventDefault()
          if(e.target.className.includes('watchlist-input') ||
             e.target.className.includes('v-icon'))  {
               return;
             }

          if(!e.target.getAttribute('data')) return
          var lists = document.querySelectorAll("[data='list']")

          let list = ele.querySelector("[data='list']");
          if(list.style.display == 'none' || list.style.display == '') {
          lists.forEach(e => e.style.display = 'none');
          list.style.display = 'block'
        }
          else {
          list.style.display = 'none'
        }
        });
  }
})

document.addEventListener('click', function(event) {

  var contained = false
  if(event.target.className.includes('watchlist-input') ||
     event.target.className.includes('v-icon'))  {
       return;
     }
  document.querySelectorAll("[data='sidebar-menu']").forEach(function(e) {

    if(e.contains(event.target)) contained = true
  })


  if (!contained && event.target.id != 'sidebar-toggle' && event.target.parentElement.id != undefined && event.target.parentElement.id != 'sidebar-toggle') {
    th.closeSideBar()
  }
});
},

created () {
  let th = this
  this.$store.subscribe((mutation, state) => { //fires when stock is updated
    if(mutation.type === 'min_window' || mutation.type === 'rm_from_min_window')
    th.popMinimizedItems()
  })
},

methods : {
  renameWatch(cat) {
    this.$store.commit('rename_fav_cat', {cat: cat, new_name: this.watchlist_rename_input})
    this.watchlist_rename_id = -1;
  },


  newWatchCat() {
    if(this.new_watchlist) {
      this.$store.commit('create_fav_cat', {name: this.new_watchlist_input});
      this.new_watchlist = false;
    }
  },

  renameWatchlist(cat) {
    this.watchlist_rename_id = this.$store.state.user.vue_data.favourites.findIndex(f => f.name == cat)
  },

  rmWatchlist(cat) {
    this.$store.commit('rm_fav_cat', cat);
  },

  onNewWatchClick() {
      this.new_watchlist = true;
  },

  watchClick(cat) {
    let th = this
    if(this.$store.state.user.vue_data.favourites.findIndex(e => e.name == cat) == -1) return;

    this.$grid.add({
      component: 'Favourites',
      additional : {
        data: cat,
      }
    })

    this.closeSideBar()
  },

  openFeedBack() {
      Userback.open();
  },
  toggleTheme() {
    this.$store.commit('toggleTheme')
    let app = document.querySelector('#app .v-application--wrap #app');
    app.classList.toggle('light-theme', this.$store.state.light_theme);
  },
  popMinimizedItems() {
    this.minimized_items = []

    var items = this.$store.getters.get_minimized_windows
    let th = this
    items.forEach((wnd) => {
      var str = `<div id='${wnd.id}' style="display:flex; justify-content:space-between; align-items:center">`
      str += `<span>${wnd.loaded_component.component}`;
      if(wnd.loaded_component.additional != undefined
        && wnd.loaded_component.additional.data != undefined)
        str += ` - ${wnd.loaded_component.additional.data}`

        if(wnd.loaded_component.additional != undefined
          && wnd.loaded_component.additional.symbol != undefined)
          str += ` - ${wnd.loaded_component.additional.symbol}`

          str += "</span>"
          str += '<span> <i aria-hidden="true" class="v-icon notranslate mdi mdi-arrow-expand theme--light"></i> </span></div>'

          th.minimized_items.push(str)
        })
      },

      minItemClicked(did) {
        var id = did.id

        var wnds = this.$store.getters.get_minimized_windows
        var wnd = wnds[id]

        this.$grid.add(wnd.loaded_component)
        this.$store.commit('rm_from_min_window', id)
      },

      toggleMinimized() {
        this.minimized_opened = !this.minimized_opened
      },

      clientPosClick() {
        let th = this
        /*
        this.$store.dispatch('addWindow', {
        width: 5,
        height:6,
        component: 'ClientPositions',
      }).then((res) => {
      // window opened
    }).catch(error => {
    if(error == 'wnd_exist') {
    th.$toast.error({title: 'Error', message: 'This window is already opened.'})
  } else {
  th.$toast.error({title: 'Error', message: 'This window can\'t be opened right now'})
}

th.$sound.play('error.mp3')
})*/

this.$grid.add({
  component: 'ClientPositions',
  orientation: 'horizontal'
})

this.closeSideBar()
},

onOrderEntryClick() {
  let th = this
  /*
  this.$store.dispatch('addWindow', {
  width: 8,
  height: 11,
  component: 'OrderEntry'
}).then((res) => {
// window opened
}).catch(error => {
if(error == 'wnd_exist') {
th.$toast.error({title: 'Error', message: 'This window is already opened.'})
} else {
th.$toast.error({title: 'Error', message: 'This window can\'t be opened right now'})
}

th.$sound.play('error.mp3')
})
*/

this.$grid.add({
  component: 'OrderEntry',
})

this.closeSideBar()
},

onClockClick() {
  let th = this

  this.$grid.add({
    component: 'Clock',
  })

  /*
  this.$store.dispatch('addWindow', {
  width: 4,
  height:5,
  component: 'Clock',
}).then((res) => {
// window opened
}).catch(error => {
if(error == 'wnd_exist') {
th.$toast.error({title: 'Error', message: 'This window is already opened.'})
} else {
th.$toast.error({title: 'Error', message: 'This window can\'t be opened right now'})
}

th.$sound.play('error.mp3')
})
*/

this.closeSideBar()
},

openSideBar() {
  this.nav_side_shown = !this.nav_side_shown
},

closeSideBar() {
  document.querySelectorAll("[data='list']").forEach((e) => {
    e.style.display = 'none'
  })
  this.nav_side_shown = false
},

onInstrumentsClick() {
  let th = this
  /*
  this.$store.dispatch('addWindow', {
  width: 3,
  height: 12,
  component: 'Instruments',
}).then((res) => {
// window opened
}).catch(error => {
if(error == 'wnd_exist') {
th.$toast.error({title: 'Error', message: 'This window is already opened.'})
} else {
th.$toast.error({title: 'Error', message: 'This window can\'t be opened right now'})
}

th.$sound.play('error.mp3')
})*/

this.$grid.add({
  component: 'Instruments',
})

this.closeSideBar()
},

onCalendarClick() {
  let th = this
  /*
  this.$store.dispatch('addWindow', {
  width: 12,
  height: 8,
  component: 'Calendar',
}).then((res) => {
// window opened
}).catch(error => {
if(error == 'wnd_exist') {
th.$toast.error({title: 'Error', message: 'This window is already opened.'})
} else {
th.$toast.error({title: 'Error', message: 'This window can\'t be opened right now'})
}

th.$sound.play('error.mp3')
})*/

this.$grid.add({
  component: 'Calendar',
})

this.closeSideBar()
},

onHistoryClick() {
  let th = this
  /*
  this.$store.dispatch('addWindow', {
  width: 12,
  height: 7,
  component: 'History',
}).then((res) => {
// window opened
}).catch(error => {
if(error == 'wnd_exist') {
th.$toast.error({title: 'Error', message: 'This window is already opened.'})
} else {
th.$toast.error({title: 'Error', message: 'This window can\'t be opened right now'})
}

th.$sound.play('error.mp3')
})*/

this.$grid.add({
  component: 'History',
  orientation: 'horizontal'
})

this.closeSideBar()
},

onOrdersClick() {
  let th = this
  /*
  this.$store.dispatch('addWindow', {
  width: 12,
  height: 7,
  component: 'Orders',
}).then((res) => {
// window opened
}).catch(error => {
if(error == 'wnd_exist') {
th.$toast.error({title: 'Error', message: 'This window is already opened.'})
} else {
th.$toast.error({title: 'Error', message: 'This window can\'t be opened right now'})
}

th.$sound.play('error.mp3')
})*/

this.$grid.add({
  component: 'Orders',
  orientation: 'horizontal'
})

this.closeSideBar()
},


onNewChartMenuClick() {
  let th = this

  /*
  this.$store.dispatch('addWindow', {
  width: 5,
  height: 12,
  component: 'NewOrder',
}).then((res) => {
// window opened
}).catch(error => {
if(error == 'wnd_exist') {
th.$toast.error({title: 'Error', message: 'This window is already opened.'})
} else {
th.$toast.error({title: 'Error', message: 'This window can\'t be opened right now'})
}

th.$sound.play('error.mp3')
})
*/
this.closeSideBar()
},

onNewsClick() {
  let th = this

  this.$grid.add({
    component: 'News',
  })

  /*
  this.$store.dispatch('addWindow', {
  width: 5,
  height: 9,
  component: 'News',
}).then((res) => {
// window opened
}).catch(error => {
if(error == 'wnd_exist') {
th.$toast.error({title: 'Error', message: 'This window is already opened.'})
} else {
th.$toast.error({title: 'Error', message: 'This window can\'t be opened right now'})
}

th.$sound.play('error.mp3')
})*/

this.closeSideBar()
},

onChartCreateClick(symbol) {
  let th = this
  this.$grid.add({
    component: 'Chart',
    additional : {
      symbol: symbol.api_name,
      overlays: []
    }
  })

  /*
  symbol = symbol.name;
  this.$store.dispatch('addWindow', {
  width: 6,
  height: 7,
  component: 'Chart',
  additional : {
  'symbol': symbol
}
}).then((res) => {
// window opened
}).catch(error => {
if(error == 'wnd_exist') {
th.$toast.error({title: 'Error', message: 'This window is already opened.'})
} else {
th.$toast.error({title: 'Error', message: 'This window can\'t be opened right now'})
}

th.$sound.play('error.mp3')
})*/

this.closeSideBar()
}
},

}

</script>
