import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'App',
    component: () => import('../views/Main.vue'),

    meta: {
               requiresAuth: true
           }
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../components/Forms/Login.vue'),
    meta: {
                guest: true
            }
  },
  {
    path: '/signup',
    name: 'Signup',
    component: () => import('../components/Forms/Register.vue'),
    meta: {
                guest: true
            }
  },
  {
    path: '/getting-started',
    name: 'GettingStarted',
    component: () => import('../components/GettingStarted.vue'),
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('../components/Dashboard/Dashboard.vue'),
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})


router.beforeEach((to, from, next) => {
    var token = localStorage.access_token;

    if(token != undefined) {
      token = JSON.parse(token);
    }

    var auth = false;

    if(token && token.expiry > Date.now())
      auth = true;

    if(to.matched.some(record => record.meta.requiresAuth)) {
        if (auth) {
            next()
        } else {
            next('/login')
        }

    } else if(to.matched.some(record => record.meta.guest)) {
        if(!auth) {
            next()
        }
        else{
            next('/')
        }
    } else {
        next()
    }
})


export default router
