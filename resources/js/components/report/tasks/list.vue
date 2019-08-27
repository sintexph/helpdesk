<template>
    <div>
        <div class="box box-solid">
            <div class="box-body form-inline">
                <div class="form-group">
                    <label class="control-label">Find Task</label>
                    <input type="text" class="form-control input-sm" v-model="filters.find"
                        @keydown.enter="reload_list">
                </div>
                <div class="form-group">
                    <label class="control-label">From</label>
                    <date-mask v-model="filters.to" class_name="input-sm"></date-mask>
                </div>
                <div class="form-group">
                    <label class="control-label">To</label>
                    <div class="input-group input-group-sm">
                        <date-mask v-model="filters.from"></date-mask>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default" v-text="filters.date"></button>
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li :class="filters.date==='Creation'?'active':''"><a href="#"
                                        @click.prevent="filters.date='Creation'">Creation</a></li>
                                <li :class="filters.date==='Progress'?'active':''"><a href="#"
                                        @click.prevent="filters.date='Progress'">Progress</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Assigned To</label>
                    <div class="input-group input-group-sm">
                        <input-autocomplete className="input-sm" @selected="user_selected" anchor="name"
                            url="/utility/find-user">
                        </input-autocomplete>

                    </div>
                    <button type="button" @click.prevent="reload_list" class="btn btn-default btn-sm">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        Generate</button>

                </div>
            </div>
        </div>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Task List</h3>
            </div>
            <div class="box-body">
                <a :href="download_link" class="btn btn-xs btn-default" title="Downlad list">
                    <i class="fa fa-download" aria-hidden="true"></i>
                </a>
                <datatable :parameters="filters" :buttons="false" ref="datatables" :columns="columns"
                    url="/report/tasks/list">
                </datatable>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                filters: {
                    find: '',
                    from: '',
                    to: '',
                    user: '',
                    date: 'Creation',
                },
                columns: [{
                        label: 'Task',
                        data: 'name',
                        className: 'fit',
                    },
                    {
                        label: 'Project',
                        data: 'project_id',
                        className: 'fit',
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
                        render(data, meta, row) {
                            return row.description_html;
                        }
                    },
                    {
                        label: 'Assigned To',
                        data: 'assigned_to',
                        className: 'fit',
                        render(data, meta, row) {
                            return row.assignee.name;
                        }
                    },
                    {
                        label: 'State',
                        data: 'state',
                        className: 'fit',
                        render(data, meta, row) {
                            return row.state_text;
                        }
                    },

                    {
                        label: 'Start Date',
                        data: 'start_date',
                        className: 'fit',
                    },
                    {
                        label: 'Due Date',
                        data: 'due_date',
                        className: 'fit',
                    },

                    {
                        label: 'Created By',
                        data: 'created_by',
                        className: 'fit',
                        render(data, meta, row) {
                            return row.creator.name;
                        }
                    },
                    {
                        label: 'Created At',
                        data: 'created_at',
                        className: 'fit',
                    }
                ],
            }
        },
        computed: {
            download_link() {
                var param = this.filters;
                return '/report/tasks/download?find=' + param.find + '&from=' + param.from + '&to=' + param.to +
                    '&user=' + param.user + '&date=' + param.date;
            }
        },
        methods: {
            user_selected(data) {
                this.filters.user = data.id;
            },
            reload_list: function () {
                this.$refs.datatables.reload();
            },
        },
    }

</script>
