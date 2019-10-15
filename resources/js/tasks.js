import "@sintexph/vue-lib"

import {
    Project
} from "./src/Project";
window.Project = Project;


import {
    Task
} from "./src/Task";
window.Task = Task;

import {
    State
} from "./src/State";
window.State = State;

import {
    Urgency
} from "./src/Urgency";
window.Urgency = Urgency;
Vue.mixin(httpAlert);
Vue.mixin(require('./mixins/task_state_selection').default);

window.GlobalEvent = new Vue();

Vue.component('draggable', require('vuedraggable').default);
Vue.component('kanban-tasks', require('./components/tasks/kanban_tasks').default)
Vue.component('form-task', require('./components/tasks/form').default)
Vue.component('add-task', require('./components/tasks/create').default)
Vue.component('task-progress', require('./components/tasks/progress').default)
Vue.component('task-list', require('./components/tasks/task_list').default)
Vue.component('task-calendar', require('./components/tasks/calendar').default)
Vue.component('modal-task-calendar', require('./components/tasks/modal_calendar').default)


new Vue({
    el: '#tasks'
});
