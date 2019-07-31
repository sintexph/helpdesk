import VueRouter from 'vue-router'

let routes = [
    {
        path: '/',
        component: require('../components/sender/tickets.vue').default
    },
    {
        path: '/create',
        component: require('../components/sender/ticket-create.vue').default
    },
     {
         path: '/application',
         component: require('../components/sender/application/form.vue').default
     }
];


export default new VueRouter({
    routes,
    linkActiveClass: 'active',
    linkExactActiveClass:'active'
});
