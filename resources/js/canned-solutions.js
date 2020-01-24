import "@sintexph/vue-lib"

Vue.mixin({
    data() {
        return {
            SOLUTION_TYPE: {
                'TEXT': 1,
                'URL': 2,
            }
        }
    }
})

Vue.mixin(toastHelper);Vue.mixin(httpAlert);
Vue.component('canned-solution-list', require('./components/manage/canned/solutions/list').default)

new Vue({
    el: '#canned-solutions'
});
