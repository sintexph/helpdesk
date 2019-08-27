<template>
    <div>
        <div class="box box-solid">
            <div class="box-body">
                <div class="form-inline">
                    <div class="form-group">
                        <label class="control-label">Search Project </label>
                        <div class="input-group input-group-sm">
                            <input type="text" placeholder="Search" class="form-control"
                                @keydown.enter="$refs.datatable.reload()" v-model="filters.find">
                            <span class="input-group-btn">
                                <button type="button" class="btn" @click.prevent="$refs.datatable.reload()">
                                    <i aria-hidden="true" class="fa fa-search"></i>
                                    <span>Search</span>
                                </button>
                            </span>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label">State</label>
                        <select class="form-control input-sm" v-model="filters.state"
                            @change="$refs.datatable.reload()">
                            <option v-for="(value,key) in select2_task_states" :key="key" :value="value.id">
                                {{ value.text }}</option>
                            <option :value="-1">Show All</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-clipboard" aria-hidden="true"></i> Projects</h3>

                <div class="box-tools pull-right">

                    <button type="button" class="btn btn-box-tool" title="Add New Project"
                        @click.prevent="$refs.createProject.show">
                        <i class="fa fa-plus" aria-hidden="true"></i> Create
                    </button>
                    <a :href="'/projects/download?find='+filters.find+'&state='+filters.state" class="btn btn-box-tool"
                        title="Downlad project list">
                        <i class="fa fa-download" aria-hidden="true"></i> Download
                    </a>

                </div>

            </div>
            <div class="box-body">


                <datatable ref="datatable" :parameters="filters" :buttons="false" url="/projects/list"
                    :columns="columns">
                </datatable>
                <create-project @created="$refs.datatable.reload()" ref="createProject"></create-project>
                <edit-project @updated="$refs.datatable.reload()" ref="editProject"></edit-project>
            </div>
        </div>
    </div>
</template>
<script>
    import createProject from './create';
    import editProject from './edit';

    export default {
        components: {
            'create-project': createProject,
            'edit-project': editProject,
        },
        data() {
            return {
                filters: {
                    find: '',
                    state: '',
                },
                columns: [
                    {
                        label: 'Project',
                        data: 'name',
                        className: 'fit',
                        render(data, meta, row) {
                            return `<strong><a title="` + row.description + `" href="/projects/view/` + row.id +
                                `">` + data + `</a></strong>`;
                        }
                    },
                    {
                        label: 'Description',
                        data: 'description',
                    },


                    {
                        label: 'Start Date',
                        data: 'start_date',
                    },
                    {
                        label: 'Due Date',
                        data: 'due_date',
                    },
                    {
                        label: 'Created At',
                        data: 'created_at',
                    },
                    {
                        label: 'Status',
                        data: 'state',
                        render(data, meta, row) {
                            return `<div class="status ` + row.state_text.toLowerCase().replace(' ', '-') + `">` +
                                row.state_text + `</div>`;
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
                            var btn_view =
                                `<a class="text-blue" title="View" href="/projects/view/` + data +
                                `"><i class="fa fa-eye" aria-hidden="true"></i></a>`;
                            return btn_view + '&nbsp;&nbsp;&nbsp;' + btn_edit + '&nbsp;&nbsp;&nbsp;' + btn_delete;
                        }
                    },
                ],
                submitted: false,
            }
        },
        methods: {
            edit_project(id) {
                this.$refs.editProject.show(id);
            },


        },
        mounted() {
            var vm = this;

            vm.$nextTick(function () {
                $(document).on('click', '.btn-delete', function (e) {
                    e.preventDefault();
                    vm.delete_project($(this).data("id"));
                });

                $(document).on('click', '.btn-edit', function (e) {
                    e.preventDefault();
                    vm.edit_project($(this).data("id"));
                });

            });

        }

    }

</script>
