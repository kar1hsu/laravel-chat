import VueRouter from 'vue-router'

let routes = [
    {
        path : '/',
        component : require('./components/Chat').default
    },
    {
        path : '/',
        component : require('./components/user/login').default
    }
]

export default new VueRouter({
    mode : 'history',
    routes
})
