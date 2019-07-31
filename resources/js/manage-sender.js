import "@sintexph/vue-lib"
import "./general.js"
import "./src/custom_request/import";

Vue.mixin(httpAlert);
Vue.mixin(require('./mixins/application_converter').default);

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import Conversations from './components/ticket/conversations.vue';
import Close from './components/sender/close.vue';
import Cancel from './components/sender/cancel.vue';
import Open from './components/sender/open.vue';

Vue.component('ticket-conversations', Conversations);
Vue.component('close-ticket', Close);
Vue.component('cancel-ticket', Cancel);
Vue.component('open-ticket', Open);
Vue.component('create-form', require('./components/ticket/form.vue').default);
Vue.component('manage-sender', require('./components/sender/manage.vue').default);

Vue.component('alert-age-tickets', require('./components/sender/alert_age_tickets').default);


new Vue({
    el: '#sender',
    router: require('./routes/sender').default
});
