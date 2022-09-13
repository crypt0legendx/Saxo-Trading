<template>
  <v-container>
    <div class="navbar fluid">
      <div class="navbar-brand_">
          <div class="nav-entry  mt-1 --medium">
            <v-icon>mdi-vuejs</v-icon>
          </div>
        </div>

        <div class="icons-top">

        <div class="nav-entry mt-2 pt-2 pb-2" title='Dashboard'>
          <v-icon class="w-100 h-100">mdi-chart-areaspline</v-icon>          
        </div>
        <div class="action_add nav-entry nav-multiple mt-2 pt-1 pb-1 --medium"  title='CRM'>
            <v-icon  @click="openSideBar(0)">mdi-form-select</v-icon>
          <!-- <i class="w-100 h-100"></i> -->
          <div data="sidebar-menu" v-show="nav_side_shown[0]" class="nav-multiple-items" style="margin-top: -5px;">         
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-users">Users</router-link></div>
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-depositors">Depositors</router-link></div>
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-agents">Agents</router-link></div>
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-sales-report">Sales Report</router-link></div>
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-notes">Notes</router-link></div>
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-emails">Emails</router-link></div>
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-calendar">Calendar</router-link></div>           
          </div>
        </div>
        <div class="nav-entry nav-multiple mt-2 pt-1 pb-1 --medium"  title='Trading'>
            <v-icon  @click="openSideBar(1)">mdi-form-select</v-icon>
          <!-- <i class="w-100 h-100"></i> -->
          <div data="sidebar-menu" v-show="nav_side_shown[1]" class="nav-multiple-items" style="margin-top: -5px;">         
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-users">Users</router-link></div>
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-depositors">Depositors</router-link></div>
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-agents">Agents</router-link></div>
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-sales-report">Sales Report</router-link></div>
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-notes">Notes</router-link></div>
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-emails">Emails</router-link></div>
            <div class="nav-multiple-item"><router-link to="/admin-panel/crm-calendar">Calendar</router-link></div>           
          </div>
        </div>

        
      </div>
</div>
</v-container>
</template>

<style lang="scss">
@import '../../../../scss/_variables.scss';

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

.nav-multiple.--nested > .nav-multiple-items
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
import InstrumentSelect from '../../../InstrumentsSelect'
import Dropdown from '../../../Dropdown'

export default {
  name : "Menubar",

  components: {
    InstrumentSelect,
    Dropdown,
  },

  data: function() {
    return {
      minimized_opened : false,
      nav_side_shown1 : false,
      nav_side_shown2 : false,
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
methods : {

 

        openSideBar1() {
          this.nav_side_shown1 = !this.nav_side_shown1 
        },

        closeSideBar() {
          document.querySelectorAll("[data='list']").forEach((e) => {
              e.style.display = 'none'
          })
          
          
        },

    }
}

</script>
