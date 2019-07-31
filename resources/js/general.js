import StateMixin from  "./src/State";
Vue.mixin(StateMixin);
import TicketUrgencyMixin from "./src/TicketUrgency";
Vue.mixin(TicketUrgencyMixin);

import RatingMixin from "./src/Rating";
Vue.mixin(RatingMixin);

import {
    Ticket
}
from "./src/Ticket";


window.Ticket = Ticket;
 

import TicketRating from './components/ticket/rating.vue';
Vue.component('ticket-rating', TicketRating);

import TicketProgress from './components/ticket/progress.vue';
Vue.component('ticket-progress', TicketProgress);

