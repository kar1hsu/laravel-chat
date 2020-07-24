import VueRouter from 'vue-router'

let routes = [
    {
        path : '/',
        component : require('./components/Chat').default
    },
    {
        path : '/login',
        component : require('./components/user/login').default
    },
    {
        path : '/register',
        component : require('./components/user/register').default
    }
]

export default new VueRouter({
    mode : 'history',
    routes
})
