import "@sintexph/vue-lib"
 import "./general.js"

Vue.mixin(httpAlert);
import TicketList from './components/my_tickets/list.vue';
Vue.component('ticket-list', TicketList); 

new Vue({
    el: '#my-tickets'
});
