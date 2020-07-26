import VueRouter from 'vue-router'

let routes = [
    {
        path : '/',
        component : require('./components/Chat').default
    },
    {
        path : '/login',
        component : require('./components/user/Login').default
    },
    {
        path : '/register',
        component : require('./components/user/Register').default
    },
    {
        path : '/friend',
        component : require('./components/Friend').default
    },
    {
        path : '/test',
        component : require('./components/Test').default
    }
]

export default new VueRouter({
    mode : 'history',
    routes
})
