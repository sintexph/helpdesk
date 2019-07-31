import "@sintexph/vue-lib"
Vue.mixin(httpAlert);

 

Vue.component('dashboard-index',require('./components/dashboard/index.vue').default);

new Vue({
    el: '#dashboard'
});

