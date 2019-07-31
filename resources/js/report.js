import "@sintexph/vue-lib"

Vue.mixin(httpAlert); 
 
Vue.component('category-chart', require("./components/report/category_chart").default);
Vue.component('target-chart', require("./components/report/target_chart").default);
Vue.component('rating-chart', require("./components/report/rating_chart").default);
Vue.component('report-list', require('./components/report/list.vue').default);



new Vue({
    el: '#report'
});
