<template>
    <modal :prevent="true" name="edit-project" ref="modal">
        <template slot="header">Create Project</template>
        <template slot="body">
            <form-project v-model="project"></form-project>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-warning btn-sm" @click.prevent="update">Update Project</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script>
    import formProject from "./form";
    export default {
        props: ['project_id'],
        components: {
            'form-project': formProject,
        },
        data() {
            return {
                id: null,
                project: new Project,
            }
        },
        methods: {
            show: function (id) {
                if (id)
                    this.id = id;
                this.load_project();
                this.$refs.modal.show();
            },
            load_project() {
                var vm = this;
                vm.project = new Project;

                vm.show_wait("Please wait while the system is retrieving the information....");

                axios.post('/projects/info/' + vm.id).then(response => {
                    vm.project.name = response.data.name;
                    vm.project.description = response.data.description;
                    vm.project.requested_by = response.data.requested_by;
                    vm.project.start_date = response.data.start_date;
                    vm.project.due_date = response.data.due_date;

                    response.data.followers.forEach(element => {
                        vm.project.followers.push(element);
                    });

                    vm.project.tags = response.data.tags;
                    vm.project.state = response.data.state;
                    vm.project.is_public = response.data.is_public;

                    vm.hide_wait();

                }).catch(error => {
                    vm.hide_wait();
                });
            },
            update() {
                var vm = this;
                axios.patch('/projects/update/' + vm.id, {
                    name: vm.project.name,
                    description: vm.project.description,
                    requested_by: vm.project.requested_by,
                    start_date: vm.project.start_date,
                    due_date: vm.project.due_date,
                    followers: vm.project.followers,
                    tags: vm.project.tags,
                    state: vm.project.state,
                    is_public :vm.project.is_public,
                }).then(response => {
                    vm.alert_success(response);

                    if (!vm.project_id) {
                        vm.id = null;
                        vm.project = new Project;
                        vm.$refs.modal.dismiss();
                        vm.$emit('updated');
                    } else {
                        location.reload();
                    }
                }).catch(error => {
                    vm.alert_failed(error);
                });
            },
        },
        mounted() {
            this.$nextTick(function () {

                this.id = this.project_id;
            });
        }
    }

</script>
