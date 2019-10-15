<template>
    <div>

        <div class="form-group">
            <label class="control-label">Project</label>
            <input type="text" v-model="project.name" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="control-label">Description</label>
            <textarea v-model="project.description" class="form-control" rows="5" required></textarea>
        </div>


        <div class="form-group clearfix">
            <div class="pull-right">
                <bootstrap-toggle v-model="project.is_public" :options="is_public_options" :disabled="false" />
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">Requested By</label>
            <select2 style="width:100%" :required="true" :options="users" v-model="project.requested_by"></select2>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Start Date</label>
                    <date-picker type="text" v-model="project.start_date"></date-picker>
                </div>

            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Due Date</label>
                    <date-picker type="text" v-model="project.due_date"></date-picker>
                </div>

            </div>

        </div>



        <div class="form-group">
            <label class="control-label">Status</label>
            <select2 style="width:100%" :options="select2_task_states" v-model="project.state" :required="true">
            </select2>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Followers</label>
                    <select2 style="width:100%" :multiple="true" :options="followers" v-model="project.followers">
                    </select2>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Tags</label>
                    <input-tag v-model="tags"></input-tag>
                </div>
            </div>
        </div>







        <div class="box box-solid">
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label">Upload</label>
                    <input-file :multiple="true" v-model="project.attachments_input"></input-file>
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
                                <template v-for="(value,key) in project.attachments">
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

        </div>



    </div>
</template>


<script>
    import BootstrapToggle from 'vue-bootstrap-toggle'

    export default {
        components: {
            BootstrapToggle
        },
        props: {
            value: {
                required: true,
                default () {
                    return new Project;
                }
            }
        },
        data() {
            return {
                project: new Project,
                users: [],
                followers: [],
                states: [],
                tags: '',
                is_public_options: {
                    size: 'small',
                    on: `<i class="fa fa-globe" aria-hidden="true"></i> Public`,
                    off: '<i class="fa fa-lock" aria-hidden="true"></i> Private',
                    width: 100,
                }
            }
        },
        watch: {
            tags(value) {
                this.project.tags = value.split(",");
            },
            project: {
                deep: true,
                handler(value) {
                    this.$emit('input', value);
                }
            },
            value: {
                deep: true,
                handler(value) {
                    this.project = value;
                    this.tags = this.project.tags.join(",");
                }
            }
        },
        methods: {
            load_user() {
                var vm = this;
                axios.post('/utility/get-users').then(response => {
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
            },
            remove_attachment(index) {
                this.project.attachments[index].remove = true;
                this.project.to_be_deleted_attachments.push(this.project.attachments[index].id);
                this.$forceUpdate();
            },
        },
        mounted() {
            if (this.value !== null && this.value !== undefined) {

                this.project = this.value;
            }


            this.load_user();





        }
    }

</script>
