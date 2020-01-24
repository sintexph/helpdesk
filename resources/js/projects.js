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
Vue.mixin(toastHelper);Vue.mixin(httpAlert);
Vue.mixin(require('./mixins/task_state_selection').default);

window.GlobalEvent = new Vue();

Vue.mixin({
    methods: {
        delete_project(id) {
            if (confirm("Are you sure you want to delete the project?")) {
                axios.delete('/projects/delete/' + id).then(response => {
                    this.alert_success(response);
                    GlobalEvent.$emit('project-deleted');
                }).catch(error => {
                    this.alert_failed(error);
                });
            }
        }
    }
})

import Paginate from 'vuejs-paginate'
Vue.component('paginated', Paginate);

Vue.component('draggable', require('vuedraggable').default);

Vue.component('project-list', require('./components/projects/list').default)
Vue.component('project-calendar', require('./components/projects/calendar').default)
Vue.component('modal-project-calendar', require('./components/projects/modal_calendar').default)
Vue.component('edit-project', require('./components/projects/edit').default)
Vue.component('create-note', require('./components/projects/create_note').default)
Vue.component('histories', require('./components/projects/histories').default)

Vue.component('add-task', require('./components/tasks/create').default)
Vue.component('form-task', require('./components/tasks/form').default)
Vue.component('kanban-tasks', require('./components/tasks/kanban_tasks').default)
Vue.component('task-list', require('./components/tasks/task_list').default)
Vue.component('task-progress', require('./components/tasks/progress').default)



new Vue({
    el: '#projects'
});
