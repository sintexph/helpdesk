import "@sintexph/vue-lib"

 
Vue.mixin(toastHelper);Vue.mixin(httpAlert);
Vue.component('category-list',require('./components/manage/categories/list').default)

new Vue({
    el: '#categories'
});
