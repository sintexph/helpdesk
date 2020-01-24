import "@sintexph/vue-lib"
import VueRouter from 'vue-router'

import {
    User
}
from "./src/User";
window.User = User;


Vue.mixin(toastHelper);Vue.mixin(httpAlert);
//Vue.component('login-form', require('./components/auth/login').default)
//Vue.component('register-form', require('./components/auth/register').default)
//Vue.component('reset-form', require('./components/auth/password_reset').default)


import loginForm from './components/auth/login';
import registerForm from './components/auth/register';
import resetForm from './components/auth/password_reset';



const routes = [

    {
        path: '*',
        component: loginForm,
    },
    {
        path: '/',
        component: loginForm,
    },
    {
        path: '/register',
        component: registerForm,
    },
    {
        path: '/reset',
        component: resetForm,
    },

]

const router = new VueRouter({
    routes: routes,
    history: true,
    hashbang: false, 
})



new Vue({
    el: '#auth',
    router: router,
});
