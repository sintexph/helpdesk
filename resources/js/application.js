import "@sintexph/vue-lib"


var tagMixin =
{
     data() {
         return {
             start_tag: '|',
             end_tag: '|',
         }
     },
}

import "./src/custom_request/import";

import appConvertMixin from './mixins/application_converter';
Vue.mixin(appConvertMixin);
Vue.mixin(httpAlert);

// Import draggable component for items
Vue.component('draggable', require('vuedraggable'));
 
Vue.component('rep-application', require('./components/manage/application/representation').default).mixin(tagMixin)
Vue.component('click-dn-fields', require('./components/manage/application/src/dynamic_fields').default).mixin(tagMixin)
Vue.component('application-list', require('./components/manage/application/list').default)

new Vue({
    el: '#application'
});
