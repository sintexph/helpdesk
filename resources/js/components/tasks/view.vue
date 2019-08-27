<template>
    <modal :prevent="true" name="update-task" ref="modal">
        <template slot="header">
            View Task
        </template>
        <template slot="body" v-if="task!==null">
            <div class="table-responsive">
                <table class="table no-border table-hover table-striped">
                    <tbody>
                        <tr>
                            <th class="fit">Task</th>
                            <td v-text="task.name"></td>
                        </tr>
                        <tr>
                            <th class="fit">Project</th>

                            <td v-if="task.project!==null" v-text="task.project.name"></td>
                            <td class="text-gray" v-else>--- none ---</td>
                        </tr>
                        <tr>
                            <th class="fit">Description</th>
                            <td>
                                <div v-html="task.description_html"></div>
                            </td>
                        </tr>
                        <tr>
                            <th class="fit">Assigned To</th>
                            <td>
                                <div v-text="task.assignee.name"></div>
                            </td>
                        </tr>
                        <tr>
                            <th class="fit">Start Date</th>
                            <td>
                                <div v-text="task.start_date"></div>
                            </td>
                        </tr>
                        <tr>
                            <th class="fit">Due Date</th>
                            <td>
                                <div v-text="task.due_date"></div>
                            </td>
                        </tr>
                        <tr>
                            <th class="fit">Remarks</th>
                            <td>
                                <div v-html="task.remarks_html"></div>
                            </td>
                        </tr>
                        <tr>
                            <th class="fit">Created By</th>
                            <td>
                                <div v-text="task.creator.name"></div>
                            </td>
                        </tr>
                        <tr>
                            <th class="fit">Created At</th>
                            <td>
                                <div v-text="task.created_at"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table no-border table-hover table-striped">
                    <tbody>
                        <tr>
                            <td :class="'priority-'+task.priority">
                                <i :class="'fa fa-flag priority-'+task.priority" aria-hidden="true"></i>
                                {{task.priority_text}}
                            </td>
                            <td class="text-primary">
                                <i class="fa fa-tag" aria-hidden="true"></i>
                                {{task.label}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Attachments</h3>
                </div>
                <div class="box-body no-padding table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>File</th>
                                <th>Size</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(value,key) in task.attachments">
                                <tr v-if="value.remove!==true" :key="key">
                                    <td>
                                        <a target="_blank"
                                            :href="value.file_upload.url">{{ value.file_upload.file_name }}</a>
                                    </td>
                                    <td>{{ value.file_upload.file_size }}</td>
                                    <td class="fit">
                                        <a :href="value.file_upload.url"><i class="fa fa-download"
                                                aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <div :class="'status '+task.state_text.toLowerCase().replace(' ','-')" v-text="task.state_text"></div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </template>
    </modal>
</template>
<script>
    export default {
        data() {
            return {
                task: null,
                id: null,
            }
        },
        methods: {
            show: function (id) {
                this.id = id;
                this.task = null;
                this.info();
                this.$refs.modal.show();
            },

            info() {
                var vm = this;
                vm.show_wait("Please wait while the system is retrieving the information....");
                axios.post('/tasks/info/' + vm.id).then(response => {

                    vm.task = response.data;

                    vm.hide_wait();

                }).catch(error => {
                    vm.hide_wait();
                });
            }
        }
    }

</script>
