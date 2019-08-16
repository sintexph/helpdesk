<template>
    <div>
        <div class="form-group">
            <label class="control-label">Project</label>
            <select2 v-model="task.project_id" :options="projects" :required="true" style="width:100%;">
            </select2>
        </div>
        <div class="form-group">
            <label class="control-label">Task</label>
            <input type="text" v-model="task.name" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="control-label">Description</label>
            <textarea v-model="task.description" class="form-control" rows="5" required></textarea>
        </div>


        <div class="form-group">
            <label class="control-label">Assigned To</label>
            <select2 style="width:100%" :required="true" :options="users" v-model="task.assigned_to"></select2>
        </div>



        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Start Date</label>
                    <date-mask type="text" v-model="task.start_date"></date-mask>
                </div>

            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Due Date</label>
                    <date-mask type="text" v-model="task.due_date"></date-mask>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">

                <div class="form-group">
                    <label class="control-label">Status</label>
                    <select2 style="width:100%" :options="select2_task_states" v-model="task.state" :required="true">
                    </select2>
                </div>

            </div>
            <div class="col-sm-6">

                <div class="form-group">
                    <label class="control-label">Label</label>
                    <select2 style="width:100%" :options="labels" v-model="task.label" :required="true"></select2>
                </div>

            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Priority</label>
            <select2 style="width:100%" :options="select2_task_urgencies" v-model="task.priority" :required="true">
            </select2>
        </div>
        <div class="form-group">
            <label class="control-label">Remarks</label>
            <textarea v-model="task.remarks" class="form-control" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label class="control-label">Upload</label>
            <input-file :multiple="true" v-model="task.attachments_input"></input-file>
        </div>
        <div class="form-group">
            <label class="control-label">Attachments</label>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Size</th>
                            <th>Uploaded By</th>
                            <th>Uploaded At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(value,key) in task.attachments">
                            <tr v-if="value.remove!==true" :key="key">
                                <td>
                                    <a :href="value.file_upload.url">{{ value.file_upload.file_name }}</a>
                                </td>
                                <td>{{ value.file_upload.file_size }}</td>
                                <td>{{ value.file_upload.uploaded_by }}</td>
                                <td>{{ value.file_upload.created_at }}</td>
                                <td class="fit">
                                    <a :href="value.file_upload.url"><i class="fa fa-download"
                                            aria-hidden="true"></i></a>
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                    <a href="#" class="text-red" @click.prevent="remove_attachment(key)">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                required: true,
                default () {
                    return new Task;
                }
            }
        },
        data() {
            return {
                task: new Task,
                users: [],
                followers: [],
                tags: '',
                labels: [],
                projects: [],
            }
        },
        watch: {
            tags(value) {
                this.task.tags = value.split(",");
            },
            task: {
                deep: true,
                handler(value) {
                    this.$emit('input', value);

                }
            },
            value: {
                deep: true,
                handler(value) {
                    this.task = value;
                }
            }
        },
        methods: {
            remove_attachment(index) {

                this.task.attachments[index].remove = true;
                this.task.to_be_deleted_attachments.push(this.task.attachments[index].id);
                this.$forceUpdate();
            },
            load_projects() {
                var vm = this;
                axios.post('/utility/projects').then(response => {
                    var data = response.data;
                    data.forEach(element => {
                        vm.projects.push({
                            id: element.id,
                            text: element.name,
                        });
                    });
                });
            },

            load_labels() {
                var vm = this;
                axios.post('/utility/setting', {
                    name: 'TASK_LABEL',
                }).then(response => {

                    vm.labels.push({
                        id: '',
                        text: '-- CHOOSE --',
                    });

                    var temp = response.data.split(",");
                    temp.forEach((la, key) => {
                        vm.labels.push({
                            id: la.replace(" ", ""),
                            text: la.replace(" ", ""),
                        });
                    });
                });
            },
            load_user() {
                var vm = this;
                axios.post('/utility/get_users').then(response => {
                    vm.users.push({
                        id: '',
                        text: '-- CHOOSE --',
                    });

                    response.data.forEach(element => {
                        vm.users.push({
                            id: element.id,
                            text: element.name,
                        });

                        vm.followers.push({
                            id: element.id,
                            text: element.name,
                        });
                    });

                });
            }
        },
        mounted() {
            if (this.value !== null && this.value !== undefined) {

                this.task = this.value;
            }

            this.load_projects();
            this.load_user();
            this.load_labels();

        }
    }

</script>
