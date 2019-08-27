<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-th-large" aria-hidden="true"></i> Kanban Task</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" @click.prevent="$refs.addTask.show()"><i
                                class="fa fa-plus"></i> Add Task
                        </button>
                    </div>
                </div>
                <div class="box-body form-inline">
                    <div class="form-group">
                        <label class="control-label">Find</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" @keydown.enter="load_tasks()"
                                v-model="filters.find">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-primary"
                                    @click.prevent="load_tasks()">Filter</button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Filter Label</label>
                        <select class="form-control input-sm" v-model="filters.label">
                            <option value="">-- FILTER LABEL --</option>
                            <option v-for="(value,key) in  labels" :key="key" :value="value">{{ value }}
                            </option>
                        </select>
                    </div>


                    <div class="pull-right" style="width:250px;">
                        <task-progress :project_id="project_id"></task-progress>
                    </div>



                </div>






            </div>
        </div>

        <div class="col-xs-3">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title text-yellow">Pending</h3>
                    <span class="label label-warning pull-right">{{ datasource.pending_tasks.length }}</span>
                    <i v-if="reloading.pending_tasks===true"
                        class="pull-right text-gray fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>

                </div>
                <div class="box-body bg-yellow">

                    <draggable style="min-height:250px;" @add="update_pending" v-model="datasource.pending_tasks"
                        group="tasks" @start="drag=true" @end="drag=false">
                        <task-drag @edit="edit_task(value.id)" @view="view_task(value.id)"
                            v-for="(value,key) in datasource.pending_tasks" :key="key"
                            v-model="datasource.pending_tasks[key]">
                        </task-drag>
                    </draggable>
                </div>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title text-blue">Processing</h3>
                    <span class="label label-primary pull-right">{{ datasource.processing_tasks.length }}</span>
                    <i v-if="reloading.processing_tasks===true"
                        class="pull-right text-gray fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>

                </div>
                <div class="box-body bg-blue">

                    <draggable style="min-height:250px;" @add="update_processing" v-model="datasource.processing_tasks"
                        group="tasks" @start="drag=true" @end="drag=false">
                        <task-drag @edit="edit_task(value.id)" @view="view_task(value.id)"
                            v-for="(value,key) in datasource.processing_tasks" :key="key"
                            v-model="datasource.processing_tasks[key]">
                        </task-drag>
                    </draggable>
                </div>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title text-green">Completed</h3>
                    <span class="label label-success pull-right">{{ datasource.completed_tasks.length }}</span>
                    <i v-if="reloading.completed_tasks===true"
                        class="pull-right text-gray fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                </div>
                <div class="box-body bg-green">

                    <draggable style="min-height:250px;" @add="update_completed" v-model="datasource.completed_tasks"
                        group="tasks" @start="drag=true" @end="drag=false">
                        <task-drag @edit="edit_task(value.id)" @view="view_task(value.id)"
                            v-for="(value,key) in datasource.completed_tasks" :key="key"
                            v-model="datasource.completed_tasks[key]">
                        </task-drag>
                    </draggable>
                </div>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Hold</h3>
                    <span class="label label-default pull-right">{{ datasource.hold_tasks.length }}</span>
                    <i v-if="reloading.hold_tasks===true"
                        class="pull-right text-gray fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                </div>
                <div class="box-body bg-gray">


                    <draggable style="min-height:250px;" @add="update_hold" v-model="datasource.hold_tasks"
                        group="tasks" @start="drag=true" @end="drag=false">
                        <task-drag @edit="edit_task(value.id)" @view="view_task(value.id)"
                            v-for="(value,key) in datasource.hold_tasks" :key="key"
                            v-model="datasource.hold_tasks[key]"></task-drag>
                    </draggable>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <edit-drag ref="editTask" @updated="load_tasks"></edit-drag>
            <view-task ref="viewTask"></view-task>
            <add-task :project_id="project_id" ref="addTask"></add-task>
        </div>
    </div>
</template>
<script>
    import taskDrag from "./draggble_src/task";
    import editTask from "./edit_task";
    import viewTask from "./view";
    export default {
        props: {
            project_id: {
                default () {
                    return null;
                }
            }
        },
        components: {
            'task-drag': taskDrag,
            'edit-drag': editTask,
            'view-task': viewTask,
        },
        data() {
            return {
                datasource: {
                    pending_tasks: [],
                    processing_tasks: [],
                    hold_tasks: [],
                    completed_tasks: [],
                },
                reloading: {
                    pending_tasks: false,
                    processing_tasks: false,
                    hold_tasks: false,
                    completed_tasks: false,
                },
                labels: [],
                filters: {
                    label: '',
                    find: '',
                }

            }
        },
        methods: {
            view_task(id) {
                this.$refs.viewTask.show(id);
            },

            edit_task(id) {
                this.$refs.editTask.show(id);
            },
            update_pending(data) {
                this.updateState(State.PENDING, this.datasource.pending_tasks[data.newIndex].id);
            },
            update_processing(data) {
                this.updateState(State.PROCESSING, this.datasource.processing_tasks[data.newIndex].id);
            },
            update_completed(data) {
                this.updateState(State.COMPLETED, this.datasource.completed_tasks[data.newIndex].id);
            },
            update_hold(data) {
                this.updateState(State.HOLD, this.datasource.hold_tasks[data.newIndex].id);
            },

            updateState(state, task_id) {
                var vm = this;
                axios.patch('/tasks/update-state/' + task_id, {
                    state: state
                }).then(response => {
                    GlobalEvent.$emit('task-updated');
                });
            },
            getLabels() {
                var vm = this;
                axios.post('/utility/setting', {
                    name: 'TASK_LABEL',
                }).then(response => {
                    vm.labels = response.data.replace(" ", "").split(',');
                });
            },
            load_tasks() {
                var vm = this;
                vm.datasource = {
                    pending_tasks: [],
                    processing_tasks: [],
                    hold_tasks: [],
                    completed_tasks: [],
                };

                vm.reloading = {
                    pending_tasks: true,
                    processing_tasks: true,
                    hold_tasks: true,
                    completed_tasks: true,
                };

                axios.post('/tasks/kanban', {
                    filter_label: vm.filters.label,
                    filter_find: vm.filters.find,
                    filter_state: State.PENDING,
                    project_id: vm.project_id,
                }).then(response => {
                    response.data.forEach(element => {
                        vm.datasource.pending_tasks.push(element);
                    });

                    vm.reloading.pending_tasks = false;
                });

                axios.post('/tasks/kanban', {
                    filter_label: vm.filters.label,
                    filter_find: vm.filters.find,
                    filter_state: State.PROCESSING,
                    project_id: vm.project_id,
                }).then(response => {
                    response.data.forEach(element => {
                        vm.datasource.processing_tasks.push(element);
                    });

                    vm.reloading.processing_tasks = false;
                });

                axios.post('/tasks/kanban', {
                    filter_label: vm.filters.label,
                    filter_find: vm.filters.find,
                    filter_state: State.HOLD,
                    project_id: vm.project_id,
                }).then(response => {
                    response.data.forEach(element => {
                        vm.datasource.hold_tasks.push(element);
                    });

                    vm.reloading.hold_tasks = false;
                });

                axios.post('/tasks/kanban', {
                    filter_label: vm.filters.label,
                    filter_find: vm.filters.find,
                    filter_state: State.COMPLETED,
                    project_id: vm.project_id,
                }).then(response => {
                    response.data.forEach(element => {
                        vm.datasource.completed_tasks.push(element);
                    });

                    vm.reloading.completed_tasks = false;
                });
            }
        },
        mounted() {

            var vm = this;
            vm.$nextTick(function () {
                GlobalEvent.$on('task-reload', function () {
                    vm.load_tasks();
                });
            });

            vm.getLabels();
            vm.load_tasks();

        }
    }

</script>
