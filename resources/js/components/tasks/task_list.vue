<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid">
                <div class="box-body form-inline">

                    <div class="form-group">
                        <label class="control-label">Find</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" @keydown.enter="$refs.datatable.reload()"
                                v-model="filters.find">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-primary"
                                    @click.prevent="$refs.datatable.reload()">Filter</button>
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

                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select class="form-control input-sm" v-model="filters.state">

                            <option v-for="(value,key) in  select2_task_states" :key="key" :value="value.id">
                                {{ value.text }}
                            </option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="box box-solid">
                <div class="box-body">
                    <a :href="download_link" class="btn btn-xs btn-default" title="Downlad task list">
                        <i class="fa fa-download" aria-hidden="true"></i>
                    </a>
                    <datatable :buttons="false" ref="datatable" :parameters="filters" :columns="columns"
                        url="/tasks/list">
                    </datatable>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <edit-task ref="editTask"></edit-task>
            <view-task ref="viewTask"></view-task>
        </div>

    </div>
</template>
<script>
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
            'edit-task': editTask,
            'view-task': viewTask,
        },
        computed: {
            download_link() {
                return '/tasks/download?project_id=' + this.project_id + '&find=' + this.filters.find + '&state=' + this
                    .filters.state + '&label=' + this.filters.label;
            }
        },
        data() {
            return {
                columns: [{
                        label: 'Task',
                        data: 'name',
                    },
                    {
                        label: 'Project',
                        data: 'project_id',
                        render(data, meta, row) {
                            if (row.project !== null)
                                return row.project.name;
                            else
                                return `<span class="text-gray">--- none ---</span>`
                        }
                    },
                    {
                        label: 'Description',
                        data: 'description',
                    },
                    {
                        label: 'Assigned To',
                        data: 'assigned_to',
                        render(data, meta, row) {
                            return row.assignee.name;
                        }
                    },
                    {
                        label: 'State',
                        data: 'state',
                        render(data, meta, row) {
                            return row.state_text;
                        }
                    },
                    {
                        label: 'Action',
                        data: 'id',
                        className: 'fit',
                        render(data) {
                            var btn_edit =
                                `<a class="text-yellow btn-edit" data-id="` + data +
                                `" title="Edit" href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>`;
                            var btn_delete =
                                `<a class="text-red btn-delete" data-id="` + data +
                                `" title="Delete" href="#"><i class="fa fa-trash" aria-hidden="true"></i></a>`;

                            var btn_view = `<a href="#" class="btn-view" data-id="` + data +
                                `"><i class="fa fa-eye" aria-hidden="true"></i></a>`

                            return btn_view + '&nbsp;&nbsp;&nbsp;' + btn_edit + '&nbsp;&nbsp;&nbsp;' + btn_delete;
                        }
                    },
                ],
                labels: [],
                filters: {
                    state: '',
                    label: '',
                    find: '',
                    project_id: '',
                },
                submitted: false,

            }
        },
        methods: {
            view_task(id) {
                this.$refs.viewTask.show(id);
            },
            edit_task(id) {
                this.$refs.editTask.show(id);
            },
            getLabels() {
                var vm = this;
                axios.post('/utility/setting', {
                    name: 'TASK_LABEL',
                }).then(response => {
                    vm.labels = response.data.replace(" ", "").split(',');
                });
            },
            delete_task(id) {
                var vm = this;
                if (vm.submitted === false) {
                    if (confirm("Are you sure you want to delete the task?")) {
                        vm.submitted = true;
                        axios.delete('/tasks/delete/' + id).then(response => {
                            vm.submitted = false;
                            vm.alert_success(response);
                            GlobalEvent.$emit('task-reload');
                            GlobalEvent.$emit('task-updated');
                        }).catch(error => {
                            vm.submitted = false;
                            vm.alert_failed(error);
                        });
                    }
                }
            }
        },
        mounted() {
            var vm = this;
            vm.filters.project_id = vm.project_id;
            vm.getLabels();
            vm.$nextTick(function () {
                $(document).on('click', '.btn-delete', function (e) {
                    e.preventDefault();
                    vm.delete_task($(this).data("id"));
                });

                $(document).on('click', '.btn-edit', function (e) {
                    e.preventDefault();
                    vm.edit_task($(this).data("id"));
                });

                $(document).on('click', '.btn-view', function (e) {
                    e.preventDefault();
                    vm.view_task($(this).data("id"));
                });

            });

            vm.$nextTick(function () {
                GlobalEvent.$on('task-reload', function () {
                    vm.$refs.datatable.reload();
                });
            });
        }
    }

</script>
