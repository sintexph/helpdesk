import "@sintexph/vue-lib"

Vue.mixin(toastHelper);Vue.mixin(httpAlert); 
 
Vue.component('report-list', require('./components/report/tasks/list.vue').default);



new Vue({
    el: '#report'
});
