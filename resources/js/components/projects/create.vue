<template>
    <modal :prevent="true" name="create-project" :extended_width="true" ref="modal">
        <template slot="header">Create Project</template>
        <template slot="body">
            <form-project v-model="project"></form-project>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-primary btn-sm" @click.prevent="create">Create Project</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        </template>
    </modal>
</template>
<script>
    import formProject from "./form";
    export default {
        components: {
            'form-project': formProject,
        },
        data() {
            return {
                project: new Project,
                submitted: false,
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },

            create() {
                var vm = this;

                if (vm.submitted === false) {
                    vm.submitted = true;
                    vm.show_wait("Please wait while the system is saving the project....");


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
                    form.append('due_date', vm.project.due_date==null?'':vm.project.due_date);
                    form.append('followers', vm.project.followers);
                    form.append('tags', vm.project.tags);
                    form.append('state', vm.project.state);
                    form.append('is_public', vm.project.is_public == true ? "1" : "0");


                    form.append('_method', 'PUT');
                    axios.post('/projects/save', form, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function (response) {

                        vm.alert_success(response);
                        vm.$refs.modal.dismiss();
                        vm.name = '';
                        vm.$emit('created');
                        vm.hide_wait();

                    }).catch(function (error) {
                        vm.alert_failed(error);
                        vm.submitted = false;
                        vm.hide_wait();
                    });






                }


            }
        }
    }

</script>
