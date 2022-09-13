import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [{
        path: '/',
        name: 'App',
        component: () =>
            import ('../views/Main.vue'),

        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/admin-panel',
        name: 'Admin',
        component: () =>
            import ('../components/Admin/Admin.vue'),
        children: [{
                path: 'dashboard',
                component: () =>
                    import ('../components/Admin/Dashboard/Dashboard.vue'),
            },
            {
                path: 'crm-users',
                component: () =>
                    import ('../components/Admin/CRM/Users.vue'),
            },
            {
                path: 'crm-depositors',
                component: () =>
                    import ('../components/Admin/CRM/Depositors.vue'),
            },
            {
                path: 'crm-agents',
                component: () =>
                    import ('../components/Admin/CRM/Agents.vue'),
            },
            {
                path: 'crm-sales-report',
                component: () =>
                    import ('../components/Admin/CRM/Sales.vue'),
            },
            {
                path: 'crm-notes',
                component: () =>
                    import ('../components/Admin/CRM/Notes.vue'),
            },
            {
                path: 'crm-emails',
                component: () =>
                    import ('../components/Admin/CRM/Emails.vue'),
            },
            {
                path: 'crm-calendar',
                component: () =>
                    import ('../components/Admin/CRM/Calendar.vue'),
            },
            {
                path: 'cashier-deposits',
                component: () =>
                    import ('../components/Admin/Cashier/Deposits.vue'),
            },
            {
                path: 'cashier-bonuses',
                component: () =>
                    import ('../components/Admin/Cashier/Bonuses.vue'),
            },
            {
                path: 'cashier-adjustments',
                component: () =>
                    import ('../components/Admin/Cashier/Adjustments.vue'),
            },
            {
                path: 'cashier-withdrawals',
                component: () =>
                    import ('../components/Admin/Cashier/Withdrawals.vue'),
            },
            {
                path: 'cashier-wire-req',
                component: () =>
                    import ('../components/Admin/Cashier/WireReq.vue'),
            },
            {
                path: 'trading-trades',
                component: () =>
                    import ('../components/Admin/Trading/Trades.vue'),
            },
            {
                path: 'trading-assets',
                component: () =>
                    import ('../components/Admin/Trading/Assets.vue'),
            },
            {
                path: 'trading-activity-hours',
                component: () =>
                    import ('../components/Admin/Trading/ActivityHours.vue'),
            },
            {
                path: 'trading-net-positions',
                component: () =>
                    import ('../components/Admin/Trading/NetPositions.vue'),
            },
            {
                path: 'trading-open-trades',
                component: () =>
                    import ('../components/Admin/Trading/OpenTrades.vue'),
            },
            {
                path: 'marketing-compaigns',
                component: () =>
                    import ('../components/Admin/Marketing/Campaigns.vue'),
            },
            {
                path: 'marketing-partners',
                component: () =>
                    import ('../components/Admin/Marketing/Partners.vue'),
            },
            {
                path: 'marketing-push-web',
                component: () =>
                    import ('../components/Admin/Marketing/PushToWeb.vue'),
            },
            {
                path: 'online',
                component: () =>
                    import ('../components/Admin/Online/Online.vue'),
            },

        ],
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        name: 'Login',
        component: () =>
            import ('../components/Forms/Login.vue'),
        meta: {
            guest: true
        }
    },
    {
        path: '/signup',
        name: 'Signup',
        component: () =>
            import ('../components/Forms/Register.vue'),
        meta: {
            guest: true
        }
    },
    {
        path: '/getting-started',
        name: 'GettingStarted',
        component: () =>
            import ('../components/GettingStarted.vue'),

        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: () =>
            import ('../components/Dashboard/Dashboard.vue'),

        meta: {
            requiresAuth: true
        }
    }
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})


router.beforeEach((to, from, next) => {
    var token = localStorage.access_token;

    if (token != undefined) {
        token = JSON.parse(token);
    }

    var auth = false;

    if (token && token.expiry > Date.now())
        auth = true;

    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (auth) {
            next()
        } else {
            next({
                path: '/login',
                query: to.query
            })
        }

    } else if (to.matched.some(record => record.meta.guest)) {
        if (!auth) {
            next()
        } else {
            next('/')
        }
    } else {
        next()
    }
})


export default router