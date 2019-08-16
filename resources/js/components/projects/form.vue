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


        <div class="form-group">
            <label class="control-label">Requested By</label>
            <select2 style="width:100%" :required="true" :options="users" v-model="project.requested_by"></select2>
        </div>



        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Start Date</label>
                    <date-mask type="text" v-model="project.start_date"></date-mask>
                </div>

            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Due Date</label>
                    <date-mask type="text" v-model="project.due_date"></date-mask>
                </div>

            </div>

        </div>



        <div class="form-group">
            <label class="control-label">Status</label>
            <select2 style="width:100%" :options="select2_task_states" v-model="project.state" :required="true"></select2>
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
    </div>
</template>

<script>
    export default {
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
                    this.tags=this.project.tags.join(",");
                }
            }
        },
        methods: {
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

                this.project = this.value;
            }


            this.load_user();

 



        }
    }

</script>
