import "@sintexph/vue-lib"
import "./general.js"


window.UserRole = require('./src/UserRole').default;
Vue.mixin(UserRole);

Vue.mixin(httpAlert);
import TicketList from './components/manage/tickets/list.vue';
import Conversations from './components/ticket/conversations.vue';
import TicketActions from './components/manage/tickets/view/actions.vue';
import TicketNotes from './components/manage/tickets/view/notes';
import TicketForm from './components/ticket/form.vue';


Vue.component('ticket-list', TicketList);
Vue.component('ticket-conversations', Conversations);
Vue.component('ticket-actions', TicketActions);
Vue.component('ticket-notes',TicketNotes);
Vue.component('ticket-form', TicketForm);

new Vue({
    el: '#manage-ticket'
});
