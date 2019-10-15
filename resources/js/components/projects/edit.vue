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
                submitted: false,
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

                    vm.project.attachments = response.data.attachments;


                    vm.hide_wait();

                }).catch(error => {
                    vm.hide_wait();
                });
            },
            update() {
                var vm = this;


                if (vm.submitted === false) {
                    vm.submitted = true;
                    vm.show_wait("Please wait while the system is saving the project.....");
                    let form = new FormData();


                    if (vm.project.attachments_input !== null) {
                        for (var i = 0; i < vm.project.attachments_input.length; i++) {
                            let file = vm.project.attachments_input[i];
                            form.append('attachments_input[' + i + ']', file);
                        }
                    }


                    form.append('name', vm.project.name);
                    form.append('description', vm.project.description);
                    form.append('requested_by', vm.project.requested_by);
                    form.append('start_date', vm.project.start_date);
                    form.append('due_date', vm.project.due_date == null ? '' : vm.project.due_date);
                    form.append('followers', vm.project.followers);
                    form.append('tags', vm.project.tags);
                    form.append('state', vm.project.state);
                    form.append('is_public', vm.project.is_public == true ? "1" : "0");
                    form.append('to_be_deleted_attachments', vm.project.to_be_deleted_attachments);



                    form.append('_method', 'PATCH');

                    axios.post('/projects/update/' + vm.id, form, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        vm.alert_success(response);

                        vm.submitted = false;
                        if (!vm.project_id) {
                            vm.id = null;
                            vm.project = new Project;
                            vm.$refs.modal.dismiss();
                            vm.$emit('updated');
                            vm.hide_wait();
                        } else {
                            location.reload();
                        }
                    }).catch(error => {
                        vm.alert_failed(error);
                        vm.hide_wait();
                        vm.submitted = false;
                    });


                }


            },
        },
        mounted() {
            this.$nextTick(function () {

                this.id = this.project_id;
            });
        }
    }

</script>
