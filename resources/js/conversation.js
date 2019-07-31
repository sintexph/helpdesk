import "@sintexph/vue-lib" 

Vue.mixin(httpAlert);  
Vue.component('ticket-conversations', require('./components/ticket/conversations.vue').default); 
new Vue({
    el: '#conversation'
});
