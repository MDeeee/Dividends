export default [
    {
        path: '/',
        name: 'home',
        component: () => import ('../views/pages/dashboard/index.vue')
    },
    {
        path: '/scrape',
        name: 'Scrape',
        component: () => import('../views/pages/scrape/index')
    },
    {
        path: '/calendar',
        name: 'Calendar',
        component: () => import ('../views/pages/calendar/index.vue')
    },
]