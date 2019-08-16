 

import RatingMixin from "./src/Rating";
Vue.mixin(RatingMixin);

import {
    Ticket
}
from "./src/Ticket";
window.Ticket = Ticket;
 
import {
    State
}
from "./src/State";
window.State = State;

 import {
     Urgency
 }
 from "./src/Urgency";
 window.Urgency = Urgency;


 Vue.mixin({

     computed: {
         State() {
             return window.State;
         }
     }
 });

 
import TicketRating from './components/ticket/rating.vue';
Vue.component('ticket-rating', TicketRating);

import TicketProgress from './components/ticket/progress.vue';
Vue.component('ticket-progress', TicketProgress);

