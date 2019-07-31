import "@sintexph/vue-lib"


Vue.mixin(httpAlert);
Vue.component('setting-list', require('./components/manage/settings/list').default)

new Vue({
    el: '#settings'
});
