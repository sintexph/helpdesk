import "@sintexph/vue-lib"

 
Vue.mixin(httpAlert);
Vue.component('factory-list',require('./components/manage/factories/list').default)

new Vue({
    el: '#factories'
});
