import "@sintexph/vue-lib"

Vue.mixin(httpAlert); 
 
Vue.component('category-chart', require("./components/report/tickets/category_chart").default);
Vue.component('target-chart', require("./components/report/tickets/target_chart").default);
Vue.component('rating-chart', require("./components/report/tickets/rating_chart").default);
Vue.component('report-list', require('./components/report/tickets/list.vue').default);



new Vue({
    el: '#report'
});
