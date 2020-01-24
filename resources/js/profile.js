import "@sintexph/vue-lib" 

import {
    User
}
from "./src/User";
window.User = User;

Vue.mixin(toastHelper);Vue.mixin(httpAlert);
Vue.component('edit-profile', require('./components/profile/edit').default);

new Vue({
    el: '#profile'
});
