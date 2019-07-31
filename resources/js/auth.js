import "@sintexph/vue-lib"


import {
    User
}
from "./src/User";
window.User = User;


Vue.mixin(httpAlert);
Vue.component('login-form', require('./components/auth/login').default)
Vue.component('register-form', require('./components/auth/register').default)
Vue.component('reset-form', require('./components/auth/password_reset').default)

new Vue({
    el: '#auth'
});
