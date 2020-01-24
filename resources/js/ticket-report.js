import "@sintexph/vue-lib"

Vue.mixin(toastHelper);Vue.mixin(httpAlert); 
 
Vue.component('category-chart', require("./components/report/tickets/category_chart").default);
Vue.component('user-efficiencies', require("./components/report/tickets/user_efficiencies").default);
Vue.component('rating-chart', require("./components/report/tickets/rating_chart").default);
Vue.component('efficiency-breakdown', require('./components/report/tickets/efficiency_breakdown.vue').default);
Vue.component('ticket-list', require('./components/report/tickets/list.vue').default);
Vue.component('report', require('./components/report/tickets/index.vue').default);



new Vue({
    el: '#report'
});
