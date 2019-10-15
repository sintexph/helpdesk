<template>
    <div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-clipboard" aria-hidden="true"></i> Projects</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" title="Project Calendar"
                        @click.prevent="$refs.projectCalendar.show()">
                        <i class="fa fa-calendar-o" aria-hidden="true"></i> Calendar
                    </button>
                    <button type="button" class="btn btn-box-tool" title="Add New Project"
                        @click.prevent="$refs.createProject.show()">
                        <i class="fa fa-plus" aria-hidden="true"></i> Create
                    </button>
                    <a :href="'/projects/download?find='+filters.find+'&state='+filters.state" class="btn btn-box-tool"
                        title="Downlad project list">
                        <i class="fa fa-download" aria-hidden="true"></i> Download
                    </a>
                </div>
            </div>
            <div class="box-body">
                <div class="pull-left">
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
        </div>

        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">Project List</h3>
            </div>
            <div class="box-body">
                <datatable fixedRightColumns="1" :buttons="false" :parameters="filters" url="/projects/list"
                    :columns="columns" ref="datatable"></datatable>
            </div>
        </div>


        <create-project @created="$refs.datatable.reload()" ref="createProject"></create-project>
        <edit-project @updated="$refs.datatable.reload()" ref="editProject"></edit-project>
        <modal-project-calendar ref="projectCalendar"></modal-project-calendar>


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
                columns: [

                    {
                        data: 'name',
                        name: 'name',
                        label: 'Name',
                        className: 'fit',
                    },
                    {
                        data: 'description',
                        name: 'description',
                        label: 'description', 
                    },

                    {
                        data: 'start_date',
                        name: 'start_date',
                        label: 'Start Date',
                        render(data, meta, row) {
                            return row.human_start_date;
                        }
                    },

                    {
                        data: 'due_date',
                        name: 'due_date',
                        label: 'Due Date',
                        render(data, meta, row) {
                            return row.human_due_date;
                        }
                    },

                    {
                        data: 'completion_rate',
                        name: 'completion_rate',
                        label: 'completion rate',
                        sortable: false,
                        className: 'fit',
                        render(data) {
                            return `
                            <div class="progress-group">
     <div class="progress progress-sm active">
         <div role="progressbar" aria-valuenow="`+data+`" aria-valuemin="0" aria-valuemax="100"
             class="progress-bar progress-bar-success progress-bar-striped" style="width: `+data+`%;"></div>
     </div> 
     <span class="progress-number"><b>`+data+`%</b></span>
 </div>
                            `;
                            return data + '%';
                        }
                    },





                    {
                        data: 'state',
                        name: 'state',
                        label: 'Status',
                        render(data, meta, row) {
                            return `<div class="status ` + row.state_text.toLowerCase() + `">` + row.state_text +
                                `</div>`;
                        }
                    },

                    {
                        data: 'requestor.name',
                        name: 'requestor.name',
                        label: 'Requestor',
                        sortable: false,
                        className: 'fit',
                    },


                    {
                        data: 'id',
                        name: 'id',
                        sortable: false,
                        label: 'ACTION',
                        className: 'fit',
                        render(data, meta, row) {

                            var btn_edit = `<a href="#" class="btn-edit" data-id="` + data +
                                `"><i class="fa fa-pencil text-yellow" aria-hidden="true"></i></a>`;
                            var btn_delete = `<a href="#" class="btn-delete" data-id="` + data +
                                `"><i class="fa fa-trash text-red" aria-hidden="true"></i></a>`;
                            var btn_view = `<a href="/projects/view/` + data + `" data-id="` + data +
                                `"><i class="fa fa-eye" aria-hidden="true"></i></a>`;



                            return btn_view + `&nbsp;&nbsp;` + btn_edit + `&nbsp;&nbsp;` + btn_delete;
                        }
                    },


                ],
                filters: {
                    find: '',
                    state: '',
                },
            }
        },
        methods: {

            edit_project(id) {
                this.$refs.editProject.show(id);
            },

        },
        mounted() {
            var vm = this;
            GlobalEvent.$on('project-deleted', function () {
                vm.$refs.datatable.reload();
            });

            $(document).on('click', '.btn-edit', function (e) {
                e.preventDefault();
                vm.edit_project($(this).data("id"));
            });
            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                vm.delete_project($(this).data("id"));
            });
        }

    }

</script>
<style>
    .project-title-el {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

</style>
